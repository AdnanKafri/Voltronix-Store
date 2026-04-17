<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderDelivery;
use App\Models\DeliveryLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class DeliveryAutomationService
{
    /**
     * Process automatic deliveries for an approved order
     */
    public function processOrderDeliveries(Order $order): array
    {
        \Log::info('=== DELIVERY AUTOMATION SERVICE START ===', [
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'order_status' => $order->status
        ]);
        
        $results = [
            'processed' => 0,
            'created' => 0,
            'skipped' => 0,
            'failed' => 0,
            'errors' => []
        ];

        // Load order items with products
        $order->load(['items.product']);
        
        \Log::info('Order items loaded', [
            'order_id' => $order->id,
            'items_count' => $order->items->count()
        ]);

        foreach ($order->items as $item) {
            $results['processed']++;
            
            try {
                $result = $this->processOrderItem($order, $item);
                $results[$result]++;
                
            } catch (\Throwable $e) {
                $results['failed']++;
                $results['errors'][] = [
                    'item_id' => $item->id,
                    'product_name' => $item->getTranslation(),
                    'error' => $e->getMessage()
                ];
                
                // Log individual item failure
                $this->logAutomationEvent($order, $item, 'auto_failed', [
                    'error' => $e->getMessage(),
                    'product_id' => $item->product_id
                ]);
            }
        }

        // Log overall automation summary
        $this->logAutomationSummary($order, $results);

        return $results;
    }

    /**
     * Process individual order item for automation
     */
    private function processOrderItem(Order $order, $item): string
    {
        \Log::info('Processing order item for automation', [
            'order_id' => $order->id,
            'item_id' => $item->id,
            'product_id' => $item->product_id,
            'has_existing_delivery' => !is_null($item->delivery)
        ]);
        
        // Skip if item already has delivery
        if ($item->delivery) {
            \Log::info('Skipping item - already has delivery', ['item_id' => $item->id]);
            return 'skipped';
        }

        $product = $item->product;
        
        if (!$product) {
            \Log::warning('Skipping item - product not found', ['item_id' => $item->id, 'product_id' => $item->product_id]);
            return 'skipped';
        }
        
        \Log::info('Product automation check', [
            'product_id' => $product->id,
            'auto_delivery_enabled' => $product->auto_delivery_enabled,
            'delivery_type' => $product->delivery_type,
            'can_auto_deliver' => $product->canAutoDeliver(),
            'has_delivery_file' => $product->hasDeliveryFile()
        ]);
        
        // Skip if product not configured for auto-delivery
        if (!$product->canAutoDeliver()) {
            \Log::info('Skipping item - product not configured for auto-delivery', [
                'item_id' => $item->id,
                'product_id' => $product->id,
                'can_auto_deliver' => $product->canAutoDeliver(),
                'delivery_type' => $product->delivery_type
            ]);
            return 'skipped';
        }

        // Skip if delivery type is not supported for automation
        if (!in_array($product->delivery_type, ['file', 'credentials', 'license'])) {
            \Log::info('Skipping item - delivery type not supported for automation', [
                'item_id' => $item->id,
                'product_id' => $product->id,
                'delivery_type' => $product->delivery_type
            ]);
            return 'skipped';
        }

        // Validate delivery requirements based on type
        if ($product->delivery_type === 'file' && !$product->hasDeliveryFile()) {
            \Log::error('Delivery file not found for product', [
                'product_id' => $product->id,
                'delivery_file_path' => $product->delivery_file_path
            ]);
            throw new \Exception("Delivery file not found for product: {$product->getTranslation('name')}");
        }

        \Log::info('Creating automatic delivery', [
            'order_id' => $order->id,
            'item_id' => $item->id,
            'product_id' => $product->id
        ]);

        // Create delivery based on type
        if ($product->delivery_type === 'file') {
            $this->createFileDelivery($order, $item, $product);
        } elseif ($product->delivery_type === 'credentials') {
            $this->createCredentialsDelivery($order, $item, $product);
        } elseif ($product->delivery_type === 'license') {
            $this->createLicenseDelivery($order, $item, $product);
        }
        
        return 'created';
    }

    /**
     * Create automatic file delivery
     */
    private function createFileDelivery(Order $order, $item, $product): OrderDelivery
    {
        $fileInfo = $product->getDeliveryFileInfo();
        $config = $product->getDeliveryConfig();

        // Create delivery record in transaction
        $delivery = DB::transaction(function () use ($order, $item, $product, $fileInfo, $config) {
            // Create delivery record using same structure as manual creation
            return OrderDelivery::create([
                'order_id' => $order->id,
                'order_item_id' => $item->id,
                'user_id' => $order->user_id,
                'type' => OrderDelivery::TYPE_FILE,
                'title' => $product->getTranslation('name') . ' - Auto Delivery',
                'description' => 'Automatically delivered upon order approval',
                
                // File information from product
                'file_path' => $product->delivery_file_path,
                'file_name' => $fileInfo['name'],
                'file_type' => $fileInfo['mime_type'],
                'file_size' => $fileInfo['size'],
                
                // Configuration from product defaults
                'expires_at' => $this->resolveExpirationDate($config),
                'max_downloads' => $config['max_downloads'],
                'max_views' => $config['max_views'],
                'require_otp' => $config['require_otp'] ?? false,
                'allowed_ips' => $config['allowed_ips'],
                
                // Automation tracking
                'created_automatically' => true,
                'automation_source' => 'order_approval',
                'created_by' => null, // System-created, not admin-created
                'admin_notes' => 'Automatically created upon order approval'
            ]);
        });

        \Log::info('Delivery record created successfully', [
            'delivery_id' => $delivery->id,
            'order_id' => $order->id,
            'item_id' => $item->id,
            'product_id' => $product->id
        ]);

        // Log creation AFTER transaction commits and delivery ID is available
        try {
            $delivery->recordAccess('auto_created', request()->ip() ?? '127.0.0.1', 'DeliveryAutomationService', [
                'order_number' => $order->order_number,
                'product_id' => $product->id,
                'product_name' => $product->getTranslation('name'),
                'automation_trigger' => 'order_approval'
            ]);
        } catch (\Throwable $e) {
            // Log the error but don't fail the delivery creation
            \Log::warning('Failed to log delivery creation', [
                'delivery_id' => $delivery->id,
                'error' => $e->getMessage()
            ]);
        }

        return $delivery;
    }

    /**
     * Create automatic credentials delivery
     */
    private function createCredentialsDelivery(Order $order, $item, $product): OrderDelivery
    {
        $config = $product->getDeliveryConfig();
        
        // Prepare simple credentials from product configuration
        $credentials = [
            'username' => $config['default_username'] ?? '',
            'password' => $config['default_password'] ?? '',
            'notes' => $config['credential_notes'] ?? ''
        ];

        // Create delivery record in transaction
        $delivery = DB::transaction(function () use ($order, $item, $product, $config, $credentials) {
            return OrderDelivery::create([
                'order_id' => $order->id,
                'order_item_id' => $item->id,
                'user_id' => $order->user_id,
                'type' => OrderDelivery::TYPE_CREDENTIALS,
                'title' => $product->getTranslation('name') . ' - Credentials',
                'description' => 'Automatically delivered credentials upon order approval',
                
                // Credentials information
                'encrypted_credentials' => json_encode($credentials),
                'credentials_type' => 'login_credentials',
                
                // Configuration from product defaults
                'expires_at' => $this->resolveExpirationDate($config),
                'max_views' => $config['max_views'] ?? null,
                
                // Automation tracking
                'created_automatically' => true,
                'automation_source' => 'order_approval',
                'created_by' => null,
                'admin_notes' => 'Automatically created upon order approval'
            ]);
        });

        \Log::info('Credentials delivery created successfully', [
            'delivery_id' => $delivery->id,
            'order_id' => $order->id,
            'item_id' => $item->id,
            'product_id' => $product->id
        ]);

        // Log creation
        try {
            $delivery->recordAccess('auto_created', request()->ip() ?? '127.0.0.1', 'DeliveryAutomationService', [
                'order_number' => $order->order_number,
                'product_id' => $product->id,
                'product_name' => $product->getTranslation('name'),
                'automation_trigger' => 'order_approval',
                'delivery_type' => 'credentials'
            ]);
        } catch (\Throwable $e) {
            \Log::warning('Failed to log credentials delivery creation', [
                'delivery_id' => $delivery->id,
                'error' => $e->getMessage()
            ]);
        }

        return $delivery;
    }

    /**
     * Create automatic license delivery
     */
    private function createLicenseDelivery(Order $order, $item, $product): OrderDelivery
    {
        $config = $product->getDeliveryConfig();
        
        // Use the license key from product configuration
        $licenseKey = $config['default_license_key'] ?? 'VTX-' . strtoupper(\Str::random(4)) . '-' . strtoupper(\Str::random(4)) . '-' . strtoupper(\Str::random(4));

        // Create delivery record in transaction
        $delivery = DB::transaction(function () use ($order, $item, $product, $config, $licenseKey) {
            return OrderDelivery::create([
                'order_id' => $order->id,
                'order_item_id' => $item->id,
                'user_id' => $order->user_id,
                'type' => OrderDelivery::TYPE_LICENSE,
                'title' => $product->getTranslation('name') . ' - License',
                'description' => 'Automatically delivered license key upon order approval',
                
                // License information
                'license_key' => $licenseKey,
                'credentials_type' => 'license_key',
                
                // Configuration from product defaults
                'expires_at' => $this->resolveExpirationDate($config),
                
                // Automation tracking
                'created_automatically' => true,
                'automation_source' => 'order_approval',
                'created_by' => null,
                'admin_notes' => 'Automatically created upon order approval'
            ]);
        });

        \Log::info('License delivery created successfully', [
            'delivery_id' => $delivery->id,
            'order_id' => $order->id,
            'item_id' => $item->id,
            'product_id' => $product->id,
            'license_key_length' => strlen($licenseKey)
        ]);

        // Log creation
        try {
            $delivery->recordAccess('auto_created', request()->ip() ?? '127.0.0.1', 'DeliveryAutomationService', [
                'order_number' => $order->order_number,
                'product_id' => $product->id,
                'product_name' => $product->getTranslation('name'),
                'automation_trigger' => 'order_approval',
                'delivery_type' => 'license'
            ]);
        } catch (\Throwable $e) {
            \Log::warning('Failed to log license delivery creation', [
                'delivery_id' => $delivery->id,
                'error' => $e->getMessage()
            ]);
        }

        return $delivery;
    }


    /**
     * Log automation event using existing DeliveryLog system
     */
    private function logAutomationEvent(Order $order, $item, string $action, array $details = []): void
    {
        try {
            DeliveryLog::create([
                'delivery_id' => data_get($item, 'delivery.id'),
                'user_id' => $order->user_id,
                'action' => $action,
                'status' => $action === 'auto_failed' ? 'failed' : 'success',
                'details' => json_encode(array_merge($details, [
                    'order_number' => $order->order_number,
                    'item_id' => data_get($item, 'id'),
                    'automation_source' => 'order_approval'
                ])),
                'ip_address' => request()->ip() ?? '127.0.0.1',
                'user_agent' => 'DeliveryAutomationService',
                'session_id' => 'automation'
            ]);
        } catch (\Throwable $e) {
            \Log::warning('Failed to log automation event', [
                'order_id' => $order->id,
                'action' => $action,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Log automation summary
     */
    private function logAutomationSummary(Order $order, array $results): void
    {
        $this->logAutomationEvent($order, null, 'automation_summary', [
            'results' => $results,
            'success_rate' => $results['processed'] > 0 ? 
                round(($results['created'] / $results['processed']) * 100, 2) : 0
        ]);
    }

    private function resolveExpirationDate(array $config): ?\Illuminate\Support\Carbon
    {
        $expirationDays = $this->normalizeNullableInteger($config['expiration_days'] ?? null);

        return $expirationDays ? now()->addDays($expirationDays) : null;
    }

    private function normalizeNullableInteger(mixed $value): ?int
    {
        if ($value === null || $value === '') {
            return null;
        }

        return is_numeric($value) ? (int) $value : null;
    }
}
