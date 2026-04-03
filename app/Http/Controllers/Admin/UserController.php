<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class UserController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Status filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Role filter
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        // Date range filter
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Sort
        $sortField = $request->get('sort', 'created_at');
        $sortDirection = $request->get('direction', 'desc');
        
        $query->orderBy($sortField, $sortDirection);

        $users = $query->withCount('orders')->paginate(15);

        // Statistics for the header cards
        $stats = [
            'total' => User::count(),
            'active' => User::active()->count(),
            'inactive' => User::inactive()->count(),
            'suspended' => User::suspended()->count(),
            'new_this_month' => User::whereMonth('created_at', now()->month)
                                  ->whereYear('created_at', now()->year)
                                  ->count(),
        ];

        return view('admin.users.index', compact('users', 'stats'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,user,moderator',
            'status' => 'required|in:active,inactive,suspended,pending',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'status' => $request->status,
            'email_verified_at' => $request->has('email_verified') ? now() : null,
        ]);

        return redirect()->route('admin.users.index')
                        ->with('success', __('admin.users.created_successfully'));
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $user->load(['orders' => function($query) {
            $query->latest()->take(10);
        }]);

        // User statistics
        $stats = [
            'total_orders' => $user->orders()->count(),
            'completed_orders' => $user->orders()->where('status', 'completed')->count(),
            'total_spent' => $user->orders()->where('status', 'completed')->sum('total_amount'),
            'average_order' => $user->orders()->where('status', 'completed')->avg('total_amount') ?? 0,
            'last_order' => $user->orders()->latest()->first(),
            'join_date' => $user->created_at,
            'last_login' => $user->last_login_at,
        ];

        // Monthly order statistics for chart
        $monthlyOrders = $user->orders()
            ->selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $chartData[] = $monthlyOrders[$i] ?? 0;
        }

        return view('admin.users.show', compact('user', 'stats', 'chartData'));
    }

    /**
     * Check if email is available.
     */
    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $exists = User::where('email', $request->email)->exists();

        return response()->json([
            'available' => !$exists
        ]);
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:admin,user,moderator',
            'status' => 'required|in:active,inactive,suspended,pending',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'status' => $request->status,
        ];

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if ($request->has('email_verified')) {
            $data['email_verified_at'] = $request->email_verified ? now() : null;
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
                        ->with('success', __('admin.users.updated_successfully'));
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                           ->with('error', __('admin.users.cannot_delete_self'));
        }

        // Prevent deleting admin users or users with orders
        if ($user->isAdmin() || $user->orders()->exists()) {
            return redirect()->route('admin.users.index')
                           ->with('error', __('admin.users.cannot_delete'));
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                        ->with('success', __('admin.users.deleted_successfully'));
    }

    /**
     * Display user statistics dashboard.
     */
    public function statistics()
    {
        // Basic statistics
        $stats = [
            'total_users' => User::count(),
            'active_users' => User::active()->count(),
            'inactive_users' => User::inactive()->count(),
            'suspended_users' => User::suspended()->count(),
            'verified_users' => User::whereNotNull('email_verified_at')->count(),
            'unverified_users' => User::whereNull('email_verified_at')->count(),
        ];

        // Monthly registration data for chart
        $monthlyRegistrations = User::selectRaw('MONTH(created_at) as month, COUNT(*) as count')
            ->whereYear('created_at', now()->year)
            ->groupBy('month')
            ->pluck('count', 'month')
            ->toArray();

        // Fill missing months with 0
        $registrationData = [];
        for ($i = 1; $i <= 12; $i++) {
            $registrationData[] = $monthlyRegistrations[$i] ?? 0;
        }

        // Recent users
        $recentUsers = User::latest()->take(10)->get();

        // Top users by orders
        $topUsers = User::withCount('orders')
            ->having('orders_count', '>', 0)
            ->orderBy('orders_count', 'desc')
            ->take(10)
            ->get();

        // User growth data (last 30 days)
        $growthData = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $count = User::whereDate('created_at', $date)->count();
            $growthData[] = [
                'date' => $date->format('M d'),
                'count' => $count
            ];
        }

        return view('admin.users.statistics', compact(
            'stats', 
            'registrationData', 
            'recentUsers', 
            'topUsers', 
            'growthData'
        ));
    }

    /**
     * Toggle user status.
     */
    public function toggleStatus(User $user)
    {
        $newStatus = $user->isActive() ? 'inactive' : 'active';
        $user->update(['status' => $newStatus]);

        return response()->json([
            'success' => true,
            'message' => __('admin.users.status_updated'),
            'status' => $newStatus
        ]);
    }

    /**
     * Suspend user.
     */
    public function suspend(User $user)
    {
        // Prevent self-suspension
        if ($user->id === auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => __('admin.users.cannot_suspend_self')
            ]);
        }

        if ($user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => __('admin.users.cannot_suspend_admin')
            ]);
        }

        $user->update(['status' => 'suspended']);

        return response()->json([
            'success' => true,
            'message' => __('admin.users.suspended_successfully')
        ]);
    }

    /**
     * Activate user.
     */
    public function activate(User $user)
    {
        $user->update(['status' => 'active']);

        return response()->json([
            'success' => true,
            'message' => __('admin.users.activated_successfully')
        ]);
    }

    /**
     * Bulk actions for users.
     */
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:activate,suspend,delete',
            'users' => 'required|array',
            'users.*' => 'exists:users,id'
        ]);

        $users = User::whereIn('id', $request->users)->get();
        $count = 0;

        foreach ($users as $user) {
            // Skip self-actions
            if ($user->id === auth()->id() && in_array($request->action, ['suspend', 'delete'])) {
                continue;
            }

            // Skip admin users for certain actions
            if ($user->isAdmin() && in_array($request->action, ['suspend', 'delete'])) {
                continue;
            }

            switch ($request->action) {
                case 'activate':
                    $user->update(['status' => 'active']);
                    $count++;
                    break;
                case 'suspend':
                    $user->update(['status' => 'suspended']);
                    $count++;
                    break;
                case 'delete':
                    if (!$user->orders()->exists()) {
                        $user->delete();
                        $count++;
                    }
                    break;
            }
        }

        $message = match($request->action) {
            'activate' => __('admin.users.bulk_activated', ['count' => $count]),
            'suspend' => __('admin.users.bulk_suspended', ['count' => $count]),
            'delete' => __('admin.users.bulk_deleted', ['count' => $count]),
        };

        return redirect()->route('admin.users.index')->with('success', $message);
    }
}
