<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDelivery;
use App\Models\DeliveryLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DeliveryController extends Controller
{
    /**
     * Display deliveries for an order
     */
    public function show(Order $order)
    {
        $order->load(['items.product', 'deliveries.logs' => function($query) {
            $query->latest()->limit(10);
        }]);

        return view('admin.orders.deliveries.show', compact('order'));
    }

    /**
     * Create delivery for order item
     */
    public function create(Request $request, Order $order)
    {
        $orderItemId = $request->get('item_id');
        $orderItem = $order->items()->with('product')->findOrFail($orderItemId);

        return view('admin.orders.deliveries.create', compact('order', 'orderItem'));
    }

    /**
     * Store new delivery
     */
    public function store(Request $request, Order $order)
    {
        try {
            \Log::info('Delivery creation started', [
                'user_id' => auth()->id(),
                'order_id' => $order->id,
                'request_data' => $request->except(['delivery_file'])
            ]);

            $validationRules = [
                'order_item_id' => 'required|exists:order_items,id',
                'type' => 'required|in:file,credentials,license,service',
                'title' => 'required|string|max:255',
                'description' => 'nullable|string',
                'expires_at' => 'nullable|date|after:now',
                'max_downloads' => 'nullable|integer|min:1',
                'max_views' => 'nullable|integer|min:1',
                'require_otp' => 'boolean',
                'view_duration' => 'nullable|integer|min:10|max:300',
                'admin_notes' => 'nullable|string',
            ];

            // Add conditional validation based on delivery type
            $deliveryType = $request->input('type');
            
            if ($deliveryType === 'file') {
                $validationRules['delivery_file'] = 'required|file|max:102400'; // 100MB max
            } elseif (in_array($deliveryType, ['credentials', 'license'])) {
                $validationRules['credentials'] = 'required|array';
                $validationRules['credentials_type'] = 'required|string|max:100';
                
                if ($deliveryType === 'credentials') {
                    $validationRules['credentials.username'] = 'required|string|max:255';
                    $validationRules['credentials.password'] = 'required|string|max:255';
                } elseif ($deliveryType === 'license') {
                    $validationRules['credentials.license_key'] = 'required|string|max:255';
                }
            }

            $validated = $request->validate($validationRules);
            \Log::info('Delivery validation passed', ['validated_fields' => array_keys($validated)]);

        $orderItem = $order->items()->findOrFail($request->order_item_id);

        // Create delivery
        $delivery = new OrderDelivery([
            'order_id' => $order->id,
            'order_item_id' => $orderItem->id,
            'user_id' => $order->user_id,
            'type' => $request->type,
            'title' => $request->title,
            'description' => $request->description,
            'expires_at' => $request->expires_at,
            'max_downloads' => $request->max_downloads,
            'max_views' => $request->max_views,
            'require_otp' => $request->boolean('require_otp'),
            'view_duration' => $request->view_duration,
            'created_by' => null,
            'admin_notes' => $request->admin_notes
        ]);

        // Handle file upload
        if ($request->type === 'file' && $request->hasFile('delivery_file')) {
            $file = $request->file('delivery_file');
            $fileName = $file->getClientOriginalName();
            $filePath = 'deliveries/' . $order->order_number . '/' . Str::random(32) . '_' . $fileName;
            
            // Store file in private disk
            $file->storeAs('', $filePath, 'private');
            
            $delivery->file_path = $filePath;
            $delivery->file_name = $fileName;
            $delivery->file_type = $file->getMimeType();
            $delivery->file_size = $file->getSize();
        }

        // Handle credentials
        if ($request->type === OrderDelivery::TYPE_LICENSE) {
            $delivery->license_key = $request->input('credentials.license_key');
            $delivery->encrypted_credentials = null;
            $delivery->credentials_type = $request->credentials_type ?: OrderDelivery::TYPE_LICENSE;
        } elseif ($request->type === OrderDelivery::TYPE_CREDENTIALS && $request->credentials) {
            $delivery->setCredentials($request->credentials);
            $delivery->license_key = null;
            $delivery->credentials_type = $request->credentials_type ?: OrderDelivery::TYPE_CREDENTIALS;
        }

        $delivery->save();

        // Log creation
        $delivery->recordAccess('created', request()->ip(), request()->userAgent(), [
            'created_by' => Auth::user()->name,
            'type' => $request->type
        ]);

            \Log::info('Delivery created successfully', [
                'delivery_id' => $delivery->id,
                'order_id' => $order->id,
                'type' => $delivery->type
            ]);

            // Handle AJAX requests
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => __('admin.deliveries.created_successfully'),
                    'delivery' => $delivery,
                    'redirect' => route('admin.orders.deliveries.show', $order)
                ]);
            }

            return redirect()
                ->route('admin.orders.deliveries.show', $order)
                ->with('success', __('admin.deliveries.created_successfully'));

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('Delivery validation failed', [
                'errors' => $e->errors(),
                'user_id' => auth()->id(),
                'order_id' => $order->id
            ]);

            // Handle AJAX requests
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => __('admin.deliveries.validation_error'),
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()
                ->back()
                ->withInput()
                ->withErrors($e->errors());

        } catch (\Exception $e) {
            \Log::error('Delivery creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
                'order_id' => $order->id
            ]);

            // Handle AJAX requests
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => __('admin.deliveries.creation_failed') . ': ' . $e->getMessage(),
                    'error' => $e->getMessage()
                ], 500);
            }

            return redirect()
                ->back()
                ->withInput()
                ->with('error', __('admin.deliveries.creation_failed') . ': ' . $e->getMessage());
        }
    }

    /**
     * Edit delivery
     */
    public function edit(Order $order, OrderDelivery $delivery)
    {
        return view('admin.orders.deliveries.edit', compact('order', 'delivery'));
    }

    /**
     * Update delivery
     */
    public function update(Request $request, Order $order, OrderDelivery $delivery)
    {
            $rules = [
            'title' => 'required|string|max:255',
            'type' => 'required|in:file,credentials,license,service',
            'description' => 'nullable|string',
            'expires_at' => 'nullable|date',
            'max_downloads' => 'nullable|integer|min:1',
            'max_views' => 'nullable|integer|min:1',
            'require_otp' => 'boolean',
            'view_duration' => 'nullable|integer|min:10|max:300',
            'admin_notes' => 'nullable|string',
            'credentials' => 'nullable|array',
            'credentials_type' => 'nullable|string|max:100'
            ];

            if ($request->type === OrderDelivery::TYPE_CREDENTIALS) {
                $rules['credentials.username'] = 'required|string|max:255';
                $rules['credentials.password'] = 'required|string|max:255';
            }

            if ($request->type === OrderDelivery::TYPE_LICENSE) {
                $rules['credentials.license_key'] = 'required|string|max:255';
            }

            $request->validate($rules);

        $oldData = $delivery->toArray();

        $delivery->update([
            'type' => $request->type,
            'title' => $request->title,
            'description' => $request->description,
            'expires_at' => $request->expires_at,
            'max_downloads' => $request->max_downloads,
            'max_views' => $request->max_views,
            'require_otp' => $request->boolean('require_otp'),
            'view_duration' => $request->view_duration,
            'updated_by' => null,
            'admin_notes' => $request->admin_notes
        ]);

        // Update credentials if provided
        if ($delivery->type === OrderDelivery::TYPE_LICENSE) {
            $delivery->license_key = $request->input('credentials.license_key');
            $delivery->encrypted_credentials = null;
            $delivery->credentials_type = $request->credentials_type ?: OrderDelivery::TYPE_LICENSE;
            $delivery->save();
        } elseif ($request->credentials && $delivery->type === OrderDelivery::TYPE_CREDENTIALS) {
            $delivery->setCredentials($request->credentials);
            $delivery->license_key = null;
            $delivery->credentials_type = $request->credentials_type ?: OrderDelivery::TYPE_CREDENTIALS;
            $delivery->save();
        }

        // Log update
        $delivery->recordAccess('updated', request()->ip(), request()->userAgent(), [
            'updated_by' => Auth::user()->name,
            'changes' => array_diff_assoc($delivery->toArray(), $oldData)
        ]);

        return redirect()
            ->route('admin.orders.deliveries.show', $order)
            ->with('success', __('admin.deliveries.updated_successfully'));
    }

    /**
     * Regenerate token
     */
    public function regenerateToken(Order $order, OrderDelivery $delivery)
    {
        try {
            $oldToken = $delivery->token;
            $newToken = $delivery->regenerateToken();

            return response()->json([
                'success' => true,
                'message' => __('admin.delivery.token_regenerated_success'),
                'new_token' => $newToken,
                'download_url' => $delivery->getDownloadUrl(),
                'credentials_url' => $delivery->getCredentialsUrl()
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to regenerate delivery token', [
                'delivery_id' => $delivery->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => __('admin.delivery.token_regenerated_error')
            ], 500);
        }
    }

    /**
     * Extend expiration
     */
    public function extend(Request $request, Order $order, OrderDelivery $delivery)
    {
        try {
            $request->validate([
                'days' => 'required|integer|min:1|max:365'
            ]);

            $delivery->extendExpiration($request->days);

            return response()->json([
                'success' => true,
                'message' => __('admin.delivery.expiration_extended_success', ['days' => $request->days]),
                'new_expiry' => $delivery->expires_at->format('M d, Y H:i')
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Failed to extend delivery expiration', [
                'delivery_id' => $delivery->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => __('admin.delivery.expiration_extended_error')
            ], 500);
        }
    }

    /**
     * Revoke delivery
     */
    public function revoke(Request $request, Order $order, OrderDelivery $delivery)
    {
        try {
            $request->validate([
                'reason' => 'nullable|string|max:500'
            ]);

            $delivery->revoke($request->reason);

            return response()->json([
                'success' => true,
                'message' => __('admin.delivery.revoked_success')
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Failed to revoke delivery', [
                'delivery_id' => $delivery->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => __('admin.delivery.revoked_error')
            ], 500);
        }
    }

    /**
     * Restore revoked delivery
     */
    public function restore(Order $order, OrderDelivery $delivery)
    {
        try {
            $delivery->update([
                'revoked' => false,
                'updated_by' => null
            ]);

            $delivery->recordAccess('restored', request()->ip(), request()->userAgent(), [
                'restored_by' => Auth::user()->name
            ]);

            return response()->json([
                'success' => true,
                'message' => __('admin.delivery.restored_success')
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to restore delivery', [
                'delivery_id' => $delivery->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => __('admin.delivery.restored_error')
            ], 500);
        }
    }

    /**
     * Reset counts
     */
    public function resetCounts(Order $order, OrderDelivery $delivery)
    {
        try {
            $delivery->resetCounts();

            return response()->json([
                'success' => true,
                'message' => __('admin.delivery.counts_reset_success')
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Failed to reset delivery counts', [
                'delivery_id' => $delivery->id,
                'order_id' => $order->id,
                'error' => $e->getMessage()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => __('admin.delivery.counts_reset_error')
            ], 500);
        }
    }

    /**
     * Delete delivery
     */
    public function destroy(Order $order, OrderDelivery $delivery)
    {
        // Delete file if exists
        if ($delivery->file_path && Storage::disk('private')->exists($delivery->file_path)) {
            Storage::disk('private')->delete($delivery->file_path);
        }

        // Log deletion before deleting
        $delivery->recordAccess('deleted', request()->ip(), request()->userAgent(), [
            'deleted_by' => Auth::user()->name
        ]);

        $delivery->delete();

        return response()->json([
            'success' => true,
            'message' => __('admin.deliveries.deleted_successfully')
        ]);
    }

    /**
     * View delivery logs
     */
    public function logs(Order $order, OrderDelivery $delivery)
    {
        $logs = $delivery->logs()
            ->with('user')
            ->latest()
            ->paginate(50);

        return view('admin.orders.deliveries.logs', compact('order', 'delivery', 'logs'));
    }

    /**
     * Global deliveries management
     */
    public function index(Request $request)
    {
        $query = OrderDelivery::with(['order', 'user', 'orderItem.product']);

        // Filters
        if ($request->type) {
            $query->where('type', $request->type);
        }

        if ($request->status === 'active') {
            $query->active();
        } elseif ($request->status === 'expired') {
            $query->expired();
        } elseif ($request->status === 'revoked') {
            $query->revoked();
        }

        if ($request->automation === 'auto') {
            $query->automated();
        } elseif ($request->automation === 'manual') {
            $query->manual();
        }

        if ($request->search) {
            $query->where(function($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%")
                  ->orWhereHas('order', function($orderQuery) use ($request) {
                      $orderQuery->where('order_number', 'like', "%{$request->search}%");
                  });
            });
        }

        $deliveries = $query->latest()->paginate(20);

        return view('admin.deliveries.index', compact('deliveries'));
    }

    /**
     * Bulk operations
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:extend,revoke,delete',
            'delivery_ids' => 'required|array',
            'delivery_ids.*' => 'exists:order_deliveries,id',
            'days' => 'required_if:action,extend|integer|min:1|max:365',
            'reason' => 'required_if:action,revoke|string|max:500'
        ]);

        $deliveries = OrderDelivery::whereIn('id', $request->delivery_ids)->get();
        $count = 0;

        foreach ($deliveries as $delivery) {
            switch ($request->action) {
                case 'extend':
                    $delivery->extendExpiration($request->days);
                    $count++;
                    break;
                    
                case 'revoke':
                    if (!$delivery->revoked) {
                        $delivery->revoke($request->reason);
                        $count++;
                    }
                    break;
                    
                case 'delete':
                    if ($delivery->file_path && Storage::disk('private')->exists($delivery->file_path)) {
                        Storage::disk('private')->delete($delivery->file_path);
                    }
                    $delivery->delete();
                    $count++;
                    break;
            }
        }

        return response()->json([
            'success' => true,
            'message' => "{$count} deliveries processed successfully."
        ]);
    }
}
