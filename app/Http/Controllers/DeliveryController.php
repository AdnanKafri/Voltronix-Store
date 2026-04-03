<?php

namespace App\Http\Controllers;

use App\Models\OrderDelivery;
use App\Models\DeliveryAccessRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class DeliveryController extends Controller
{
    /**
     * Download file by token
     */
    public function download(Request $request, string $token)
    {
        $delivery = OrderDelivery::where('token', $token)
            ->where('type', OrderDelivery::TYPE_FILE)
            ->first();

        if (!$delivery) {
            return $this->handleAccessDenied($token, 'Invalid token', $request->ip());
        }

        // Check if user owns this delivery
        if (!$this->canAccessDelivery($delivery)) {
            return $this->handleAccessDenied($token, 'Unauthorized access', $request->ip());
        }

        // Check if delivery is accessible
        if (!$delivery->isAccessible()) {
            $reason = $delivery->revoked ? 'Delivery revoked' : 
                     ($delivery->expires_at && $delivery->expires_at->isPast() ? 'Delivery expired' : 'Download limit exceeded');
            
            return $this->handleAccessDenied($token, $reason, $request->ip());
        }

        // Check IP restrictions
        if (!$delivery->isIpAllowed($request->ip())) {
            return $this->handleAccessDenied($token, 'IP not allowed', $request->ip());
        }

        // Check if file exists
        if (!$delivery->fileExists()) {
            return $this->handleAccessDenied($token, 'File not found', $request->ip());
        }

        // Record download
        $delivery->recordDownload($request->ip(), $request->userAgent());

        // Stream file
        $filePath = $delivery->file_path;
        $fileName = $delivery->file_name;
        
        return Storage::disk('private')->download($filePath, $fileName);
    }

    /**
     * View credentials by token
     */
    public function credentials(Request $request, string $token)
    {
        $delivery = OrderDelivery::where('token', $token)
            ->whereIn('type', [OrderDelivery::TYPE_CREDENTIALS, OrderDelivery::TYPE_LICENSE])
            ->first();

        if (!$delivery) {
            abort(404, 'Delivery not found');
        }

        // Check if user owns this delivery
        if (!$this->canAccessDelivery($delivery)) {
            abort(403, 'Unauthorized access');
        }

        // Check if delivery is accessible
        if (!$delivery->isAccessible()) {
            $reason = $delivery->revoked ? 'Access revoked' : 
                     ($delivery->expires_at && $delivery->expires_at->isPast() ? 'Access expired' : 'View limit exceeded');
            
            return view('delivery.access-denied', compact('delivery', 'reason'));
        }

        // Check IP restrictions
        if (!$delivery->isIpAllowed($request->ip())) {
            return view('delivery.access-denied', [
                'delivery' => $delivery,
                'reason' => 'Access from this IP address is not allowed'
            ]);
        }

        // Record view
        $delivery->recordView($request->ip(), $request->userAgent());

        // Get credentials
        $credentials = $delivery->getCredentials();
        $maskedCredentials = $delivery->getMaskedCredentials();

        return view('delivery.credentials', compact('delivery', 'credentials', 'maskedCredentials'));
    }

    /**
     * Reveal credentials (AJAX)
     */
    public function revealCredentials(Request $request, string $token)
    {
        $delivery = OrderDelivery::where('token', $token)
            ->whereIn('type', [OrderDelivery::TYPE_CREDENTIALS, OrderDelivery::TYPE_LICENSE])
            ->first();

        if (!$delivery || !$this->canAccessDelivery($delivery) || !$delivery->isAccessible()) {
            return response()->json(['error' => 'Access denied'], 403);
        }

        // Record credential reveal
        $delivery->recordAccess('reveal_credentials', $request->ip(), $request->userAgent());

        return response()->json([
            'credentials' => $delivery->getCredentials(),
            'view_duration' => $delivery->view_duration ?? 60 // Default 60 seconds
        ]);
    }

    /**
     * Request new access (when expired/limit reached)
     */
    public function requestAccess(Request $request, string $token)
    {
        $delivery = OrderDelivery::where('token', $token)->first();

        if (!$delivery || !$this->canAccessDelivery($delivery)) {
            abort(404);
        }

        return view('delivery.request-access', compact('delivery'));
    }

    /**
     * Submit access request
     */
    public function submitAccessRequest(Request $request, string $token)
    {
        try {
            // Handle both JSON and form data
            $data = $request->isJson() ? $request->json()->all() : $request->all();
            
            $validator = \Validator::make($data, [
                'reason' => 'required|string|max:500'
            ]);
            
            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validator->errors()->first(),
                    'errors' => $validator->errors()
                ], 422);
            }

            $delivery = OrderDelivery::where('token', $token)->first();

            if (!$delivery || !$this->canAccessDelivery($delivery)) {
                return response()->json([
                    'success' => false,
                    'message' => __('app.delivery.invalid_request')
                ], 400);
            }

            // Create access request record
            $accessRequest = DeliveryAccessRequest::create([
                'delivery_id' => $delivery->id,
                'user_id' => auth()->id(),
                'reason' => $data['reason'],
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'status' => 'pending'
            ]);

            // Also log the request for audit trail (now with proper enum value)
            $delivery->recordAccess('access_request', $request->ip(), $request->userAgent(), [
                'reason' => $data['reason'],
                'user_id' => auth()->id(),
                'request_id' => $accessRequest->id,
                'timestamp' => now()->toISOString()
            ]);

            return response()->json([
                'success' => true,
                'message' => __('app.delivery.request_submitted_success')
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Access request submission failed', [
                'token' => $token,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all(),
                'user_id' => auth()->id(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => __('app.delivery.request_error')
            ], 500);
        }
    }

    /**
     * Check if current user can access delivery
     */
    private function canAccessDelivery(OrderDelivery $delivery): bool
    {
        if (Auth::check()) {
            return Auth::id() === $delivery->user_id;
        }

        // For guest orders, check session
        return session()->getId() === $delivery->order->session_id;
    }

    /**
     * Handle access denied scenarios
     */
    private function handleAccessDenied(string $token, string $reason, string $ip)
    {
        // Log the denied access attempt
        DeliveryLog::create([
            'delivery_id' => OrderDelivery::where('token', $token)->value('id'),
            'user_id' => Auth::id(),
            'action' => 'access_denied',
            'status' => 'denied',
            'details' => json_encode(['reason' => $reason]),
            'ip_address' => $ip,
            'user_agent' => request()->userAgent(),
            'session_id' => session()->getId()
        ]);

        return view('delivery.access-denied', [
            'reason' => $reason,
            'token' => $token
        ]);
    }
}
