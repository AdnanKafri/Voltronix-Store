<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Category::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->whereRaw("JSON_EXTRACT(name, '$.en') LIKE ?", ["%{$search}%"])
                  ->orWhereRaw("JSON_EXTRACT(name, '$.ar') LIKE ?", ["%{$search}%"]);
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('is_active', $request->get('status') === 'active');
        }

        $categories = $query->ordered()->paginate(15);

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // Handle thumbnail upload
        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $request->file('thumbnail')->store('categories', 'public');
        }

        // Create category with translations
        $category = Category::create([
            'name' => [
                'en' => $validated['name_en'],
                'ar' => $validated['name_ar']
            ],
            'description' => [
                'en' => $validated['description_en'] ?? '',
                'ar' => $validated['description_ar'] ?? ''
            ],
            'slug' => $validated['slug'],
            'thumbnail' => $thumbnailPath,
            'is_active' => $validated['is_active'] ?? true,
            'sort_order' => $validated['sort_order'] ?? 0
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', __('admin.category.created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category): View
    {
        $category->load(['products' => function ($query) {
            $query->ordered()->take(10);
        }]);

        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category): View
    {
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $validated = $request->validated();

        // Handle thumbnail upload
        $thumbnailPath = $category->thumbnail;
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($category->thumbnail) {
                Storage::disk('public')->delete($category->thumbnail);
            }
            $thumbnailPath = $request->file('thumbnail')->store('categories', 'public');
        }

        // Update category with translations
        $category->update([
            'name' => [
                'en' => $validated['name_en'],
                'ar' => $validated['name_ar']
            ],
            'description' => [
                'en' => $validated['description_en'] ?? '',
                'ar' => $validated['description_ar'] ?? ''
            ],
            'slug' => $validated['slug'],
            'thumbnail' => $thumbnailPath,
            'is_active' => $validated['is_active'] ?? true,
            'sort_order' => $validated['sort_order'] ?? 0
        ]);

        return redirect()
            ->route('admin.categories.index')
            ->with('success', __('admin.category.updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category): JsonResponse|RedirectResponse
    {
        // Check if category has products
        if ($category->products()->count() > 0) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('admin.category.cannot_delete_with_products')
                ], 422);
            }
            
            return redirect()
                ->route('admin.categories.index')
                ->with('error', __('admin.category.cannot_delete_with_products'));
        }

        // Delete thumbnail
        if ($category->thumbnail) {
            Storage::disk('public')->delete($category->thumbnail);
        }

        $category->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('admin.category.deleted_successfully')
            ]);
        }

        return redirect()
            ->route('admin.categories.index')
            ->with('success', __('admin.category.deleted_successfully'));
    }

    /**
     * Toggle category status
     */
    public function toggleStatus(Category $category): JsonResponse|RedirectResponse
    {
        $category->update(['is_active' => !$category->is_active]);

        $status = $category->is_active ? 'activated' : 'deactivated';
        $message = __("admin.category.{$status}_successfully");
        
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'status' => $category->is_active ? 'active' : 'inactive'
            ]);
        }
        
        return redirect()
            ->route('admin.categories.index')
            ->with('success', $message);
    }
}
