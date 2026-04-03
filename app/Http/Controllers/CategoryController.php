<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * Display a listing of all categories
     */
    public function index(): View
    {
        $categories = Category::active()
            ->ordered()
            ->withCount('activeProducts')
            ->get();

        return view('categories.index', compact('categories'));
    }

    /**
     * Display the specified category and its products
     */
    public function show(Category $category, Request $request): View
    {
        // Check if category is active
        if (!$category->is_active) {
            abort(404);
        }

        $query = $category->activeProducts();

        // Search within category
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->whereRaw("JSON_EXTRACT(name, '$.en') LIKE ?", ["%{$search}%"])
                  ->orWhereRaw("JSON_EXTRACT(name, '$.ar') LIKE ?", ["%{$search}%"])
                  ->orWhereRaw("JSON_EXTRACT(description, '$.en') LIKE ?", ["%{$search}%"])
                  ->orWhereRaw("JSON_EXTRACT(description, '$.ar') LIKE ?", ["%{$search}%"]);
            });
        }

        // Sort products
        $sortBy = $request->get('sort', 'default');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'name':
                $query->orderByRaw("JSON_EXTRACT(name, '$.en') ASC");
                break;
            default:
                $query->ordered();
        }

        $products = $query->paginate(12)->withQueryString();

        return view('categories.show', compact('category', 'products', 'sortBy'));
    }
}
