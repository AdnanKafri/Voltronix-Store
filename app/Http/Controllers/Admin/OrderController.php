<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderDownload;
use App\Events\OrderStatusChanged;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class OrderController extends Controller
{
    /**
     * Display orders dashboard
     */
    public function index(Request $request): View
    {
        $query = Order::with(['user', 'items.product']);

        // Filter by status
        if ($request->filled('status')) {
            $query->withStatus($request->get('status'));
        }

        // Search by order number or customer
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get statistics
        $stats = [
            'total' => Order::count(),
            'pending' => Order::withStatus('pending')->count(),
            'approved' => Order::withStatus('approved')->count(),
            'rejected' => Order::withStatus('rejected')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    /**
     * Show order details
     */
    public function show(Order $order): View
    {
        $order->load(['user', 'items.product', 'downloads', 'approvedBy', 'rejectedBy']);

        return view('admin.orders.show', compact('order'));
    }

    /**
     * Approve an order
     */
    public function approve(Request $request, Order $order)
    {
        $request->validate([
            'admin_notes' => 'nullable|string|max:1000',
            'download_days' => 'nullable|integer|min:1|max:365',
        ]);

        if ($order->status !== 'pending') {
            return redirect()->back()
                ->with('error', __('app.admin.orders.cannot_approve'));
        }

        // Approve the order
        $order->approve(
            auth()->user(),
            $request->get('admin_notes')
        );

        // Set custom download expiration if provided
        if ($request->filled('download_days')) {
            $order->enableDownloads($request->get('download_days'));
        }

        // Create download tokens for downloadable items
        $this->createDownloadTokens($order);

        // Send approval email (implement later)
        // Mail::to($order->customer_email)->send(new OrderApprovedMail($order));

        return redirect()->route('admin.orders.show', $order)
            ->with('success', __('app.admin.orders.approved_successfully'));
    }

    /**
     * Reject an order
     */
    public function reject(Request $request, Order $order)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        if ($order->status !== 'pending') {
            return redirect()->back()
                ->with('error', __('app.admin.orders.cannot_reject'));
        }

        // Reject the order
        $order->reject(
            auth()->user(),
            $request->get('rejection_reason'),
            $request->get('admin_notes')
        );

        // Send rejection email (implement later)
        // Mail::to($order->customer_email)->send(new OrderRejectedMail($order));

        return redirect()->route('admin.orders.show', $order)
            ->with('success', __('app.admin.orders.rejected_successfully'));
    }

    /**
     * Enable/disable downloads for an order
     */
    public function toggleDownloads(Request $request, Order $order)
    {
        $request->validate([
            'enable' => 'required|boolean',
            'days' => 'nullable|integer|min:1|max:365',
        ]);

        if ($request->boolean('enable')) {
            $days = $request->get('days', 7);
            $order->enableDownloads($days);
            
            // Create download tokens if they don't exist
            $this->createDownloadTokens($order);
            
            $message = __('app.admin.orders.downloads_enabled');
        } else {
            $order->disableDownloads();
            $message = __('app.admin.orders.downloads_disabled');
        }

        return redirect()->back()->with('success', $message);
    }

    /**
     * Download payment receipt
     */
    public function downloadReceipt(Order $order)
    {
        if (!$order->payment_proof_path) {
            abort(404, 'Receipt not found');
        }

        $filePath = $order->payment_proof_path;
        
        // Handle both old and new path formats
        if (Storage::disk('private')->exists($filePath)) {
            return Storage::disk('private')->download($filePath, 'receipt-' . $order->order_number . '.' . pathinfo($filePath, PATHINFO_EXTENSION));
        } elseif (Storage::exists($filePath)) {
            return Storage::download($filePath, 'receipt-' . $order->order_number . '.' . pathinfo($filePath, PATHINFO_EXTENSION));
        }

        abort(404, 'Receipt file not found');
    }

    /**
     * View payment receipt
     */
    public function viewReceipt(Order $order)
    {
        if (!$order->payment_proof_path) {
            abort(404, 'Receipt not found');
        }

        $filePath = $order->payment_proof_path;
        
        // Handle both old and new path formats
        if (Storage::disk('private')->exists($filePath)) {
            return Storage::disk('private')->response($filePath);
        } elseif (Storage::exists($filePath)) {
            return Storage::response($filePath);
        }

        abort(404, 'Receipt file not found');
    }

    /**
     * Update order status via AJAX
     */
    public function updateStatus(Request $request, Order $order)
    {
        \Log::info('=== ORDER STATUS UPDATE START ===', [
            'order_id' => $order->id,
            'current_status' => $order->status,
            'requested_status' => $request->get('status'),
            'admin_id' => auth('admin')->id(),
            'request_data' => $request->all()
        ]);

        $request->validate([
            'status' => 'required|in:pending,approved,rejected,cancelled'
        ]);

        $status = $request->get('status');
        $currentStatus = $order->status;

        \Log::info('Validation passed, proceeding with status update', [
            'order_id' => $order->id,
            'from_status' => $currentStatus,
            'to_status' => $status
        ]);

        try {
            if ($status === 'approved') {
                if ($currentStatus === 'pending') {
                    \Log::info('Attempting to approve pending order', ['order_id' => $order->id]);
                    $admin = auth('admin')->user();
                    \Log::info('Admin user retrieved', ['admin_id' => $admin->id, 'admin_email' => $admin->email]);
                    
                    $result = $order->approve($admin, 'Status updated via admin panel');
                    \Log::info('Approve method result', ['result' => $result, 'new_status' => $order->fresh()->status]);
                } else {
                    \Log::info('Direct status update to approved', ['order_id' => $order->id]);
                    $order->update(['status' => $status]);
                    \Log::info('Direct update completed', ['new_status' => $order->fresh()->status]);
                }
            } elseif ($status === 'rejected') {
                if ($currentStatus === 'pending') {
                    \Log::info('Attempting to reject pending order', ['order_id' => $order->id]);
                    $admin = auth('admin')->user();
                    \Log::info('Admin user retrieved', ['admin_id' => $admin->id, 'admin_email' => $admin->email]);
                    
                    $result = $order->reject($admin, 'Status updated via admin panel', 'Status updated via admin panel');
                    \Log::info('Reject method result', ['result' => $result, 'new_status' => $order->fresh()->status]);
                } else {
                    \Log::info('Direct status update to rejected', ['order_id' => $order->id]);
                    $order->update(['status' => $status]);
                    \Log::info('Direct update completed', ['new_status' => $order->fresh()->status]);
                }
            } else {
                \Log::info('Direct status update to other status', ['order_id' => $order->id, 'status' => $status]);
                $order->update(['status' => $status]);
                \Log::info('Direct update completed', ['new_status' => $order->fresh()->status]);
            }

            $finalOrder = $order->fresh();
            \Log::info('Final order status after update', [
                'order_id' => $order->id,
                'final_status' => $finalOrder->status,
                'update_successful' => $finalOrder->status === $status
            ]);

            // Fire event for automation processing
            if ($currentStatus !== $finalOrder->status) {
                OrderStatusChanged::dispatch($finalOrder, $currentStatus, $finalOrder->status);
            }

            return response()->json([
                'success' => true,
                'message' => __('admin.orders.status_updated'),
                'status' => $finalOrder->status,
                'status_badge' => $finalOrder->status_badge_class
            ]);
        } catch (\Exception $e) {
            \Log::error('=== ORDER STATUS UPDATE EXCEPTION ===', [
                'order_id' => $order->id,
                'current_status' => $currentStatus,
                'new_status' => $status,
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => __('admin.orders.status_update_failed') . ': ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Regenerate download tokens
     */
    public function regenerateTokens(Order $order)
    {
        if ($order->status !== 'approved') {
            return redirect()->back()
                ->with('error', __('app.admin.orders.cannot_regenerate_tokens'));
        }

        // Regenerate all download tokens
        foreach ($order->downloads as $download) {
            $download->regenerateToken();
        }

        return redirect()->back()
            ->with('success', __('app.admin.orders.tokens_regenerated'));
    }

    /**
     * Upload files for an order item
     */
    public function uploadFiles(Request $request, Order $order)
    {
        $request->validate([
            'order_item_id' => 'required|exists:order_items,id',
            'files.*' => 'required|file|max:102400', // 100MB max per file
        ]);

        $orderItem = $order->items()->findOrFail($request->get('order_item_id'));

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {
                // Store file in private storage
                $filename = time() . '_' . Str::random(10) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('order_files/' . $order->id, $filename, 'private');

                // Create download record
                OrderDownload::create([
                    'order_id' => $order->id,
                    'order_item_id' => $orderItem->id,
                    'user_id' => $order->user_id,
                    'file_name' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                    'expires_at' => $order->downloads_expires_at,
                    'is_active' => $order->downloads_enabled,
                ]);
            }
        }

        return redirect()->back()
            ->with('success', __('app.admin.orders.files_uploaded'));
    }

    /**
     * Update order item credentials
     */
    public function updateCredentials(Request $request, Order $order)
    {
        $request->validate([
            'order_item_id' => 'required|exists:order_items,id',
            'credentials' => 'required|array',
        ]);

        $orderItem = $order->items()->findOrFail($request->get('order_item_id'));

        if ($orderItem->delivery_type !== 'credentials') {
            return redirect()->back()
                ->with('error', __('app.admin.orders.not_credential_item'));
        }

        // Update delivery content with new credentials
        $orderItem->update([
            'delivery_content' => $request->get('credentials')
        ]);

        return redirect()->back()
            ->with('success', __('app.admin.orders.credentials_updated'));
    }


    /**
     * Add admin note to order
     */
    public function addNote(Request $request, Order $order)
    {
        $request->validate([
            'note_content' => 'required|string|max:1000',
        ]);

        $currentNotes = $order->admin_notes ?? '';
        $newNote = '[' . now()->format('Y-m-d H:i:s') . '] ' . auth()->user()->name . ': ' . $request->get('note_content');
        
        $updatedNotes = $currentNotes ? $currentNotes . "\n\n" . $newNote : $newNote;

        $order->update([
            'admin_notes' => $updatedNotes,
        ]);

        return response()->json([
            'success' => true,
            'message' => __('admin.orders.note_added'),
        ]);
    }

    /**
     * Create download tokens for order items
     */
    private function createDownloadTokens(Order $order): void
    {
        foreach ($order->items as $item) {
            if ($item->delivery_type === 'download' && $item->hasDownloadableContent()) {
                // Check if download tokens already exist
                $existingDownloads = $order->downloads()->where('order_item_id', $item->id)->count();
                
                if ($existingDownloads === 0) {
                    // Create placeholder download records
                    $files = $item->delivery_content['files'] ?? [];
                    
                    foreach ($files as $file) {
                        OrderDownload::create([
                            'order_id' => $order->id,
                            'order_item_id' => $item->id,
                            'user_id' => $order->user_id,
                            'file_name' => $file['name'],
                            'file_path' => '', // Will be updated when file is uploaded
                            'file_type' => 'application/octet-stream',
                            'file_size' => 0,
                            'expires_at' => $order->downloads_expires_at,
                            'is_active' => $order->downloads_enabled,
                        ]);
                    }
                }
            }
        }
    }
}
