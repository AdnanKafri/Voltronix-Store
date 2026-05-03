<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductMedia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Product::with('category');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->whereRaw("JSON_EXTRACT(name, '$.en') LIKE ?", ["%{$search}%"])
                  ->orWhereRaw("JSON_EXTRACT(name, '$.ar') LIKE ?", ["%{$search}%"]);
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->get('category'));
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->get('status'));
        }

        $products = $query->ordered()->paginate(15);
        $categories = Category::active()->ordered()->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $categories = Category::active()->ordered()->get();
        return view('admin.products.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        try {
            \Log::info('Product creation started', [
                'user_id' => auth()->id(), 
                'auto_delivery_enabled' => $request->boolean('auto_delivery_enabled'),
                'delivery_type' => $request->input('delivery_type'),
                'request_data' => $request->except(['thumbnail', 'delivery_file'])
            ]);
            
            $validated = $request->validated();
            \Log::info('Validation passed', [
                'validated_fields' => array_keys($validated),
                'delivery_type' => $validated['delivery_type'] ?? 'not_set',
                'auto_delivery_enabled' => $validated['auto_delivery_enabled'] ?? false
            ]);

            // Handle thumbnail upload
            $thumbnailPath = null;
            if ($request->hasFile('thumbnail')) {
                $thumbnailPath = $request->file('thumbnail')->store('products/thumbnails', 'public');
            }

            // Prepare features array with debug logging
            \Log::info('Features Debug - Raw Request Data', [
                'features_en_raw' => $request->input('features_en'),
                'features_ar_raw' => $request->input('features_ar'),
                'validated_features_en' => $validated['features_en'] ?? 'NOT_SET',
                'validated_features_ar' => $validated['features_ar'] ?? 'NOT_SET'
            ]);
            
            $features = [];
            if (!empty($validated['features_en'])) {
                $features['en'] = array_filter($validated['features_en']);
            }
            if (!empty($validated['features_ar'])) {
                $features['ar'] = array_filter($validated['features_ar']);
            }
            
            \Log::info('Features Debug - Processed Array', [
                'features_final' => $features,
                'features_json' => json_encode($features)
            ]);

            // Handle media data based on type
            $mediaData = $this->processMediaData($request, $validated);

            // Handle delivery file upload
            $deliveryFilePath = null;
            $deliveryFileName = null;
            if ($request->boolean('auto_delivery_enabled') && $request->hasFile('delivery_file')) {
                $file = $request->file('delivery_file');
                $deliveryFileName = $file->getClientOriginalName();
                $deliveryFilePath = 'products/deliveries/' . \Str::random(32) . '_' . $deliveryFileName;
                
                // Store in private disk
                $file->storeAs('', $deliveryFilePath, 'private');
            }

            // Create product with translations
            $product = Product::create([
                'category_id' => $validated['category_id'],
                'name' => [
                    'en' => $validated['name_en'],
                    'ar' => $validated['name_ar']
                ],
                'description' => [
                    'en' => $validated['description_en'] ?? '',
                    'ar' => $validated['description_ar'] ?? ''
                ],
                'price' => $validated['price'],
                'discount_price' => $validated['discount_price'] ?? null,
                'status' => $validated['status'],
                'is_featured' => $request->boolean('is_featured'),
                'is_new' => $request->boolean('is_new'),
                'thumbnail' => $thumbnailPath,
                'media_type' => $validated['media_type'] ?? 'simple',
                'media_data' => $mediaData,
                'features' => $features ?: null,
                'sort_order' => $validated['sort_order'] ?? 0,
                // Delivery automation fields
                'auto_delivery_enabled' => $request->boolean('auto_delivery_enabled'),
                'delivery_type' => $request->boolean('auto_delivery_enabled') ? 'file' : null,
                'delivery_file_path' => $deliveryFilePath,
                'delivery_file_name' => $deliveryFileName,
                'default_expiration_days' => $validated['default_expiration_days'] ?? null,
                'default_max_downloads' => $validated['default_max_downloads'] ?? null,
                'default_max_views' => $validated['default_max_views'] ?? null,
                // Delivery configuration
                'delivery_config' => $request->boolean('auto_delivery_enabled') ? [
                    'default_username' => $validated['default_username'] ?? null,
                    'default_password' => $validated['default_password'] ?? null,
                    'credential_notes' => $validated['credential_notes'] ?? null,
                    'default_license_key' => $validated['default_license_key'] ?? null,
                    'license_instructions' => $validated['license_instructions'] ?? null,
                    'expiration_days' => $validated['default_expiration_days'] ?? null,
                    'max_downloads' => $validated['default_max_downloads'] ?? null,
                    'max_views' => $validated['default_max_views'] ?? null,
                ] : null
            ]);

            // Process and save media
            $this->processAndSaveMedia($request, $product);

            \Log::info('Product created successfully', ['product_id' => $product->id, 'product_name' => $product->name]);

            // Handle AJAX requests
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => __('admin.product.created_successfully'),
                    'product' => $product,
                    'redirect' => route('admin.products.index')
                ]);
            }

            return redirect()
                ->route('admin.products.index')
                ->with('success', __('admin.product.created_successfully'));

        } catch (\Exception $e) {
            \Log::error('Product creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
                'request_data' => $request->except(['thumbnail', 'delivery_file'])
            ]);
            
            // Handle AJAX requests
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => __('admin.product.creation_failed') . ': ' . $e->getMessage(),
                    'error' => $e->getMessage()
                ], 500);
            }
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', __('admin.product.creation_failed') . ': ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): View
    {
        $product->load(['category', 'media']);
        return view('admin.products.show', compact('product'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product): View
    {
        $categories = Category::active()->ordered()->get();
        $product->load('media');
        return view('admin.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product): RedirectResponse
    {
        $validated = $request->validated();

        // Handle thumbnail upload
        $thumbnailPath = $product->thumbnail;
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($product->thumbnail) {
                Storage::disk('public')->delete($product->thumbnail);
            }
            $thumbnailPath = $request->file('thumbnail')->store('products/thumbnails', 'public');
        }

        // Prepare features array
        $features = [];
        if (!empty($validated['features_en'])) {
            $features['en'] = array_filter($validated['features_en']);
        }
        if (!empty($validated['features_ar'])) {
            $features['ar'] = array_filter($validated['features_ar']);
        }

        // Handle media data based on type
        $mediaData = $this->processMediaData($request, $validated, $product);

        // Handle delivery file upload
        $deliveryFilePath = $product->delivery_file_path;
        $deliveryFileName = $product->delivery_file_name;
        if ($request->boolean('auto_delivery_enabled') && $request->hasFile('delivery_file')) {
            // Delete old delivery file
            if ($product->delivery_file_path) {
                Storage::disk('private')->delete($product->delivery_file_path);
            }
            
            $file = $request->file('delivery_file');
            $deliveryFileName = $file->getClientOriginalName();
            $deliveryFilePath = 'products/deliveries/' . \Str::random(32) . '_' . $deliveryFileName;
            
            // Store in private disk
            $file->storeAs('', $deliveryFilePath, 'private');
        }

        // Update product with translations
        $product->update([
            'category_id' => $validated['category_id'],
            'name' => [
                'en' => $validated['name_en'],
                'ar' => $validated['name_ar']
            ],
            'description' => [
                'en' => $validated['description_en'] ?? '',
                'ar' => $validated['description_ar'] ?? ''
            ],
            'slug' => $validated['slug'],
            'price' => $validated['price'],
            'discount_price' => $validated['discount_price'] ?? null,
            'status' => $validated['status'],
            'is_featured' => $validated['is_featured'] ?? false,
            'is_new' => $validated['is_new'] ?? false,
            'thumbnail' => $thumbnailPath,
            'media_type' => $validated['media_type'] ?? 'simple',
            'media_data' => $mediaData,
            'features' => $features ?: null,
            'download_link' => $validated['download_link'],
            'sort_order' => $validated['sort_order'] ?? 0,
            // Delivery automation fields
            'auto_delivery_enabled' => $request->boolean('auto_delivery_enabled'),
            'delivery_type' => $request->boolean('auto_delivery_enabled') ? 'file' : null,
            'delivery_file_path' => $deliveryFilePath,
            'delivery_file_name' => $deliveryFileName,
            'default_expiration_days' => $validated['default_expiration_days'] ?? null,
            'default_max_downloads' => $validated['default_max_downloads'] ?? null,
            'default_max_views' => $validated['default_max_views'] ?? null
        ]);

        return redirect()
            ->route('admin.products.index')
            ->with('success', __('admin.product.updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        // Delete thumbnail
        if ($product->thumbnail) {
            Storage::disk('public')->delete($product->thumbnail);
        }

        $product->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', __('admin.product.deleted_successfully'));
    }

    /**
     * Toggle product status
     */
    public function toggleStatus(Product $product): RedirectResponse
    {
        $newStatus = $product->status === 'available' ? 'unavailable' : 'available';
        $product->update(['status' => $newStatus]);

        return redirect()
            ->route('admin.products.index')
            ->with('success', __("admin.product.status_updated_successfully"));
    }

    /**
     * Bulk actions for products
     */
    public function bulkAction(Request $request): RedirectResponse
    {
        $request->validate([
            'action' => 'required|in:delete,activate,deactivate',
            'products' => 'required|array|min:1',
            'products.*' => 'exists:products,id'
        ]);

        $productIds = $request->get('products');
        $action = $request->get('action');

        switch ($action) {
            case 'delete':
                $products = Product::whereIn('id', $productIds)->get();
                foreach ($products as $product) {
                    if ($product->thumbnail) {
                        Storage::disk('public')->delete($product->thumbnail);
                    }
                }
                Product::whereIn('id', $productIds)->delete();
                $message = __('admin.product.bulk_deleted_successfully');
                break;

            case 'activate':
                Product::whereIn('id', $productIds)->update(['status' => 'available']);
                $message = __('admin.product.bulk_activated_successfully');
                break;

            case 'deactivate':
                Product::whereIn('id', $productIds)->update(['status' => 'unavailable']);
                $message = __('admin.product.bulk_deactivated_successfully');
                break;
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', $message);
    }

    /**
     * Process and save media for product
     */
    private function processAndSaveMedia(Request $request, Product $product): void
    {
        $sortOrder = 1;

        // Process Gallery Images
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $index => $file) {
                $path = $file->store('products/gallery', 'public');
                
                $product->media()->create([
                    'type' => ProductMedia::TYPE_IMAGE,
                    'path' => $path,
                    'title' => $request->input("gallery_titles.{$index}") ?? '',
                    'description' => $request->input("gallery_descriptions.{$index}") ?? '',
                    'sort_order' => $sortOrder++,
                    'is_featured' => $index === 0 // First image is featured
                ]);
            }
        }

        // Process Before/After Images
        if ($request->hasFile('before_image')) {
            $path = $request->file('before_image')->store('products/before_after', 'public');
            
            $product->media()->create([
                'type' => ProductMedia::TYPE_BEFORE,
                'path' => $path,
                'title' => 'Before Image',
                'sort_order' => $sortOrder++
            ]);
        }

        if ($request->hasFile('after_image')) {
            $path = $request->file('after_image')->store('products/before_after', 'public');
            
            $product->media()->create([
                'type' => ProductMedia::TYPE_AFTER,
                'path' => $path,
                'title' => 'After Image',
                'sort_order' => $sortOrder++
            ]);
        }

        // Process Video File
        if ($request->hasFile('video_file')) {
            $path = $request->file('video_file')->store('products/videos', 'public');
            
            $metadata = [];
            if ($request->hasFile('video_poster')) {
                $posterPath = $request->file('video_poster')->store('products/posters', 'public');
                $metadata['poster'] = $posterPath;
            }
            
            $product->media()->create([
                'type' => ProductMedia::TYPE_VIDEO,
                'path' => $path,
                'title' => $request->input('video_title') ?? 'Product Video',
                'description' => $request->input('video_description') ?? '',
                'metadata' => $metadata,
                'sort_order' => $sortOrder++
            ]);
        }

        // Process YouTube/External Video
        if ($request->filled('youtube_url')) {
            $product->media()->create([
                'type' => ProductMedia::TYPE_YOUTUBE,
                'url' => $request->input('youtube_url'),
                'title' => $request->input('youtube_title') ?? 'Product Video',
                'description' => $request->input('youtube_description') ?? '',
                'sort_order' => $sortOrder++
            ]);
        }
    }

    /**
     * AJAX validation endpoint for real-time field validation
     */
    public function validateField(Request $request): JsonResponse
    {
        $field = $request->input('field');
        $value = $request->input('value');
        $productId = $request->input('product_id');

        $rules = [];
        $messages = [];

        switch ($field) {
            case 'name_en':
                $rules['value'] = 'required|string|max:255';
                $messages['value.required'] = __('admin.product.name_en') . ' ' . __('admin.validation.required');
                $messages['value.max'] = __('admin.product.name_en') . ' ' . __('admin.validation.max', ['max' => 255]);
                break;
                
            case 'name_ar':
                $rules['value'] = 'required|string|max:255';
                $messages['value.required'] = __('admin.product.name_ar') . ' ' . __('admin.validation.required');
                $messages['value.max'] = __('admin.product.name_ar') . ' ' . __('admin.validation.max', ['max' => 255]);
                break;
                
            case 'price':
                $rules['value'] = 'required|numeric|min:0|max:99999.99';
                $messages['value.required'] = __('admin.product.price') . ' ' . __('admin.validation.required');
                $messages['value.numeric'] = __('admin.product.price') . ' ' . __('admin.validation.numeric');
                $messages['value.min'] = __('admin.product.price') . ' ' . __('admin.validation.min', ['min' => 0]);
                $messages['value.max'] = __('admin.product.price') . ' ' . __('admin.validation.max', ['max' => 99999.99]);
                break;
                
            case 'slug':
                $rules['value'] = 'nullable|string|max:255|regex:/^[a-z0-9-]+$/|unique:products,slug' . ($productId ? ",{$productId}" : '');
                $messages['value.max'] = __('admin.product.slug') . ' ' . __('admin.validation.max', ['max' => 255]);
                $messages['value.regex'] = __('admin.product.slug') . ' ' . __('admin.validation.slug_format');
                $messages['value.unique'] = __('admin.product.slug') . ' ' . __('admin.validation.unique');
                break;
                
            default:
                return response()->json([
                    'valid' => false,
                    'message' => __('admin.validation.invalid_field')
                ], 400);
        }

        $validator = \Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'valid' => false,
                'message' => $validator->errors()->first('value')
            ]);
        }

        return response()->json([
            'valid' => true,
            'message' => __('admin.validation.field_valid')
        ]);
    }

    /**
     * Get media item details
     */
    public function getMedia(ProductMedia $media)
    {
        try {
            return response()->json([
                'success' => true,
                'media' => [
                    'id' => $media->id,
                    'type' => $media->type,
                    'title' => $media->title,
                    'description' => $media->description,
                    'path' => $media->path,
                    'url' => $media->url,
                    'media_url' => $media->media_url,
                    'is_featured' => $media->is_featured,
                    'sort_order' => $media->sort_order
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load media details.'
            ], 500);
        }
    }

    /**
     * Store new media item
     */
    public function storeMedia(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'type' => 'required|in:image,video,before,after,youtube',
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'media_file' => 'nullable|file|max:10240', // 10MB max
            'url' => 'nullable|url'
        ]);

        try {
            $mediaData = [
                'product_id' => $request->product_id,
                'type' => $request->type,
                'title' => $request->title,
                'description' => $request->description,
                'sort_order' => ProductMedia::where('product_id', $request->product_id)->count() + 1
            ];

            // Handle file upload
            if ($request->hasFile('media_file')) {
                $file = $request->file('media_file');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('products/media', $filename, 'public');
                $mediaData['path'] = $path;
            }

            // Handle URL (for YouTube)
            if ($request->url) {
                $mediaData['url'] = $request->url;
            }

            $media = ProductMedia::create($mediaData);

            return response()->json([
                'success' => true,
                'message' => 'Media added successfully!',
                'media' => $media
            ]);

        } catch (\Exception $e) {
            \Log::error('Media creation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to add media. Please try again.'
            ], 500);
        }
    }

    /**
     * Update media item
     */
    public function updateMedia(Request $request, ProductMedia $media)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'media_file' => 'nullable|file|max:10240', // 10MB max
            'url' => 'nullable|url'
        ]);

        try {
            $updateData = [
                'title' => $request->title,
                'description' => $request->description
            ];

            // Handle file replacement
            if ($request->hasFile('media_file')) {
                // Delete old file
                if ($media->path) {
                    Storage::disk('public')->delete($media->path);
                }
                
                // Store new file
                $file = $request->file('media_file');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('products/media', $filename, 'public');
                $updateData['path'] = $path;
            }

            // Handle URL update (for YouTube)
            if ($request->has('url')) {
                $updateData['url'] = $request->url;
            }

            $media->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Media updated successfully!',
                'media' => $media->fresh()
            ]);

        } catch (\Exception $e) {
            \Log::error('Media update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update media. Please try again.'
            ], 500);
        }
    }

    /**
     * Delete media item
     */
    public function deleteMedia(ProductMedia $media)
    {
        try {
            // Delete file if exists
            if ($media->path) {
                Storage::disk('public')->delete($media->path);
            }

            $media->delete();

            return response()->json([
                'success' => true,
                'message' => 'Media deleted successfully!'
            ]);

        } catch (\Exception $e) {
            \Log::error('Media deletion failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete media. Please try again.'
            ], 500);
        }
    }

    /**
     * Process media data based on media type
     */
    private function processMediaData($request, $validated, $product = null)
    {
        $mediaData = [];
        $mediaType = $validated['media_type'] ?? 'simple';

        switch ($mediaType) {
            case 'gallery':
                if ($request->has('gallery_urls')) {
                    $mediaData['gallery_urls'] = array_filter($validated['gallery_urls'] ?? []);
                }
                break;

            case 'video':
                if ($request->has('video_url')) {
                    $mediaData['video_url'] = $validated['video_url'];
                }
                if ($request->has('video_poster_url')) {
                    $mediaData['video_poster_url'] = $validated['video_poster_url'];
                }
                break;

            case 'before_after':
                if ($request->has('before_image_url')) {
                    $mediaData['before_image_url'] = $validated['before_image_url'];
                }
                if ($request->has('after_image_url')) {
                    $mediaData['after_image_url'] = $validated['after_image_url'];
                }
                break;

            case 'simple':
            default:
                // For simple products, no additional media data needed
                break;
        }

        return $mediaData;
    }
}
