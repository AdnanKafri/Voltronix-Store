<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductReview;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Show admin dashboard
     */
    public function index()
    {
        // Get statistics
        $stats = [
            'total_products' => Product::count(),
            'active_products' => Product::where('status', 'available')->count(),
            'featured_products' => Product::where('is_featured', true)->count(),
            'total_orders' => Order::count(),
            'pending_orders' => Order::where('status', 'pending')->count(),
            'approved_orders' => Order::where('status', 'approved')->count(),
            'total_revenue' => Order::where('status', 'approved')->sum('total_amount'),
            'monthly_revenue' => Order::where('status', 'approved')
                ->whereMonth('created_at', now()->month)
                ->sum('total_amount'),
            'total_reviews' => ProductReview::count(),
            'pending_reviews' => ProductReview::where('status', 'pending')->count(),
            'approved_reviews' => ProductReview::where('status', 'approved')->count(),
            'total_users' => User::count(),
        ];

        // Recent orders
        $recentOrders = Order::with(['user'])
            ->latest()
            ->take(5)
            ->get();

        // Recent reviews
        $recentReviews = ProductReview::with(['user', 'product'])
            ->latest()
            ->take(5)
            ->get();

        // Monthly sales chart data
        $monthlySales = Order::where('status', 'approved')
            ->selectRaw('MONTH(created_at) as month, SUM(total_amount) as total')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->orderBy('month')
            ->get()
            ->pluck('total', 'month')
            ->toArray();

        // Fill missing months with 0
        $salesData = [];
        for ($i = 1; $i <= 12; $i++) {
            $salesData[] = $monthlySales[$i] ?? 0;
        }

        // Order status distribution
        $orderStatusData = Order::selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status')
            ->toArray();

        return view('admin.dashboard', compact(
            'stats',
            'recentOrders',
            'recentReviews',
            'salesData',
            'orderStatusData'
        ));
    }
}
