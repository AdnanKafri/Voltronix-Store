<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDownload;
use App\Events\OrderStatusChanged;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display user's order history
     */
    public function index(Request $request): View
    {
        // Require authentication for orders
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('info', __('app.orders.login_required'));
        }

        $query = Order::forUser(auth()->id());

        // Filter by status if provided
        if ($request->filled('status')) {
            $query->withStatus($request->get('status'));
        }

        $orders = $query->with(['items.product', 'downloads'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get order statistics
        $stats = [
            'total' => Order::forUser(auth()->id())->count(),
            'pending' => Order::forUser(auth()->id())->withStatus('pending')->count(),
            'approved' => Order::forUser(auth()->id())->withStatus('approved')->count(),
            'rejected' => Order::forUser(auth()->id())->withStatus('rejected')->count(),
        ];

        return view('orders.index', compact('orders', 'stats'));
    }

    /**
     * Display order details
     */
    public function show(Order $order): View
    {
        // Ensure user can only see their own order
        if ($order->user_id !== auth()->id()) {
            abort(404);
        }

        $order->load([
            'items.product.category',
            'items.order',
            'items.delivery',
            'downloads',
            'approvedBy',
            'rejectedBy',
            'coupon',
        ]);

        return view('orders.show', compact('order'));
    }

    /**
     * Display print-friendly invoice view
     */
    public function invoice(Order $order): View
    {
        if ($order->user_id !== auth()->id()) {
            abort(404);
        }

        $order->load([
            'items.product.category',
            'coupon',
        ]);

        return view('orders.invoice', compact('order'));
    }

    /**
     * Cancel an order (if pending and within 1 hour)
     */
    public function cancel(Request $request, Order $order)
    {
        // Ensure user can only cancel their own order
        if ($order->user_id !== auth()->id()) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Order not found'], 404);
            }
            abort(404);
        }

        // Check if order can be cancelled (pending + within 1 hour)
        if (!$order->canBeCancelled()) {
            $message = $order->isPending() 
                ? __('orders.cancellation_expired') 
                : __('orders.cannot_cancel_status');
                
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $message
                ], 422);
            }
            
            return redirect()->back()->with('error', $message);
        }

        // Cancel the order
        $previousStatus = $order->status;
        $order->update([
            'status' => Order::STATUS_CANCELLED,
            'cancelled_at' => now()
        ]);
        if ($previousStatus !== Order::STATUS_CANCELLED) {
            OrderStatusChanged::dispatch($order->fresh(), $previousStatus, Order::STATUS_CANCELLED);
        }

        $successMessage = __('orders.cancelled_successfully');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $successMessage,
                'order' => [
                    'id' => $order->id,
                    'status' => $order->status,
                    'localized_status' => $order->localized_status
                ]
            ]);
        }

        return redirect()->route('orders.index')->with('success', $successMessage);
    }

    /**
     * Secure download handler
     */
    public function download(Order $order, string $token)
    {
        // Verify order ownership
        if ($order->user_id !== auth()->id()) {
            abort(404);
        }

        // Find the download by token
        $download = OrderDownload::where('download_token', $token)
            ->where('order_id', $order->id)
            ->where('user_id', auth()->id())
            ->first();

        if (!$download) {
            abort(404);
        }

        // Check if download is available
        if (!$download->isAvailable()) {
            return redirect()->route('orders.show', $order)
                ->with('error', __('app.orders.download_not_available'));
        }

        // Check if file exists
        if (!Storage::disk('private')->exists($download->file_path)) {
            return redirect()->route('orders.show', $order)
                ->with('error', __('app.orders.file_not_found'));
        }

        // Record the download
        $download->recordDownload(request()->ip());

        // Return the file
        return Storage::disk('private')->download(
            $download->file_path,
            $download->file_name
        );
    }

    /**
     * Toggle credential visibility (AJAX)
     */
    public function toggleCredentials(Order $order, Request $request)
    {
        // Verify order ownership and approval
        if ($order->user_id !== auth()->id() || $order->status !== 'approved') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $itemId = $request->get('item_id');
        $item = $order->items()->find($itemId);

        if (!$item || !$item->hasCredentials()) {
            return response()->json(['error' => 'Invalid item'], 400);
        }

        $showReal = $request->boolean('show_real');
        
        if ($showReal) {
            $credentials = $item->delivery_content;
        } else {
            $credentials = $item->getMaskedCredentials();
        }

        return response()->json([
            'success' => true,
            'credentials' => $credentials
        ]);
    }

    /**
     * View payment receipt
     */
    public function viewReceipt(Order $order)
    {
        // Verify order ownership
        if ($order->user_id !== auth()->id()) {
            abort(404);
        }

        if (!$order->payment_proof_path) {
            abort(404, 'No payment proof found');
        }

        $filePath = $order->payment_proof_path;
        
        // Handle both old and new path formats
        if (Storage::disk('private')->exists($filePath)) {
            return Storage::disk('private')->response($filePath);
        } elseif (Storage::exists($filePath)) {
            return Storage::response($filePath);
        }

        abort(404, 'Payment proof file not found');
    }

    /**
     * Download payment receipt
     */
    public function downloadReceipt(Order $order)
    {
        // Verify order ownership
        if ($order->user_id !== auth()->id()) {
            abort(404);
        }

        if (!$order->payment_proof_path) {
            abort(404, 'No payment proof found');
        }

        $filePath = $order->payment_proof_path;
        $fileName = 'payment_proof_' . $order->order_number . '.' . pathinfo($filePath, PATHINFO_EXTENSION);
        
        // Handle both old and new path formats
        if (Storage::disk('private')->exists($filePath)) {
            return Storage::disk('private')->download($filePath, $fileName);
        } elseif (Storage::exists($filePath)) {
            return Storage::download($filePath, $fileName);
        }

        abort(404, 'Payment proof file not found');
    }
}
