<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Validation\Rule;

class CouponController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $query = Coupon::query()->with('targetUser');

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->where('code', 'LIKE', "%{$search}%")
                  ->orWhereRaw("JSON_EXTRACT(name, '$.en') LIKE ?", ["%{$search}%"])
                  ->orWhereRaw("JSON_EXTRACT(name, '$.ar') LIKE ?", ["%{$search}%"]);
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $status = $request->get('status');
            if ($status === 'active') {
                $query->active();
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            } elseif ($status === 'expired') {
                $query->where('expiry_date', '<', now());
            }
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->get('type'));
        }

        $coupons = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.coupons.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $users = User::select('id', 'name', 'email')->get();
        return view('admin.coupons.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'code' => 'required|string|max:50|unique:coupons,code',
                'name_en' => 'required|string|max:255',
                'name_ar' => 'required|string|max:255',
                'description_en' => 'nullable|string|max:1000',
                'description_ar' => 'nullable|string|max:1000',
                'type' => 'required|in:percentage,fixed',
                'value' => 'required|numeric|min:0|max:999999.99',
                'min_order_value' => 'nullable|numeric|min:0|max:999999.99',
                'max_discount' => 'nullable|numeric|min:0|max:999999.99',
                'usage_limit' => 'nullable|integer|min:1|max:999999',
                'per_user_limit' => 'required|integer|min:1|max:999',
                'start_date' => 'nullable|date|after_or_equal:today',
                'expiry_date' => 'nullable|date|after:start_date',
                'target_user_id' => 'nullable|exists:users,id',
                'first_time_only' => 'nullable|boolean',
                'is_active' => 'nullable|boolean'
            ], [
                'code.unique' => __('admin.coupon.code_already_exists'),
                'first_time_only.boolean' => __('admin.coupon.boolean_error'),
                'is_active.boolean' => __('admin.coupon.boolean_error'),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors for AJAX requests
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('admin.validation_failed'),
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e; // Re-throw for regular form submissions
        }

        try {
            // Use database transaction to ensure atomicity
            $coupon = \DB::transaction(function () use ($validated, $request) {
                return Coupon::create([
                    'code' => strtoupper($validated['code']),
                    'name' => [
                        'en' => $validated['name_en'],
                        'ar' => $validated['name_ar']
                    ],
                    'description' => [
                        'en' => $validated['description_en'] ?? '',
                        'ar' => $validated['description_ar'] ?? ''
                    ],
                    'type' => $validated['type'],
                    'value' => $validated['value'],
                    'min_order_value' => $validated['min_order_value'],
                    'max_discount' => $validated['max_discount'],
                    'usage_limit' => $validated['usage_limit'],
                    'per_user_limit' => $validated['per_user_limit'],
                    'start_date' => $validated['start_date'],
                    'expiry_date' => $validated['expiry_date'],
                    'target_user_id' => $validated['target_user_id'],
                    'first_time_only' => $request->boolean('first_time_only'),
                    'is_active' => $request->boolean('is_active')
                ]);
            });

            // Handle AJAX requests
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('admin.coupon.created_successfully'),
                    'redirect' => route('admin.coupons.index')
                ]);
            }

            return redirect()
                ->route('admin.coupons.index')
                ->with('success', __('admin.coupon.created_successfully'));

        } catch (\Exception $e) {
            \Log::error('Coupon creation failed: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'validated_data' => $validated ?? null,
                'is_active_value' => $request->input('is_active'),
                'has_is_active' => $request->has('is_active'),
                'exception_trace' => $e->getTraceAsString()
            ]);
            
            $errorMessage = __('admin.coupon.creation_failed') . ': ' . $e->getMessage();
            
            // Handle AJAX requests
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'errors' => ['general' => [$errorMessage]]
                ], 422);
            }
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $errorMessage);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Coupon $coupon): View
    {
        $coupon->load(['targetUser', 'orders' => function ($query) {
            $query->latest()->take(10);
        }]);

        return view('admin.coupons.show', compact('coupon'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Coupon $coupon): View
    {
        $users = User::select('id', 'name', 'email')->get();
        return view('admin.coupons.edit', compact('coupon', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Coupon $coupon)
    {
        try {
            $validated = $request->validate([
                'code' => ['required', 'string', 'max:50', Rule::unique('coupons')->ignore($coupon->id)],
                'name_en' => 'required|string|max:255',
                'name_ar' => 'required|string|max:255',
                'description_en' => 'nullable|string|max:1000',
                'description_ar' => 'nullable|string|max:1000',
                'type' => 'required|in:percentage,fixed',
                'value' => 'required|numeric|min:0|max:999999.99',
                'min_order_value' => 'nullable|numeric|min:0|max:999999.99',
                'max_discount' => 'nullable|numeric|min:0|max:999999.99',
                'usage_limit' => 'nullable|integer|min:1|max:999999',
                'per_user_limit' => 'required|integer|min:1|max:999',
                'start_date' => 'nullable|date',
                'expiry_date' => 'nullable|date|after:start_date',
                'target_user_id' => 'nullable|exists:users,id',
                'first_time_only' => 'nullable|boolean',
                'is_active' => 'nullable|boolean'
            ], [
                'code.unique' => __('admin.coupon.code_already_exists'),
                'first_time_only.boolean' => __('admin.coupon.boolean_error'),
                'is_active.boolean' => __('admin.coupon.boolean_error'),
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors for AJAX requests
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('admin.validation_failed'),
                    'errors' => $e->errors()
                ], 422);
            }
            throw $e; // Re-throw for regular form submissions
        }

        try {
            // Use database transaction to ensure atomicity
            \DB::transaction(function () use ($coupon, $validated, $request) {
                $coupon->update([
                'code' => strtoupper($validated['code']),
                'name' => [
                    'en' => $validated['name_en'],
                    'ar' => $validated['name_ar']
                ],
                'description' => [
                    'en' => $validated['description_en'] ?? '',
                    'ar' => $validated['description_ar'] ?? ''
                ],
                'type' => $validated['type'],
                'value' => $validated['value'],
                'min_order_value' => $validated['min_order_value'],
                'max_discount' => $validated['max_discount'],
                'usage_limit' => $validated['usage_limit'],
                'per_user_limit' => $validated['per_user_limit'],
                'start_date' => $validated['start_date'],
                'expiry_date' => $validated['expiry_date'],
                'target_user_id' => $validated['target_user_id'],
                'first_time_only' => $request->boolean('first_time_only'),
                'is_active' => $request->boolean('is_active')
                ]);
            });

            // Handle AJAX requests
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('admin.coupon.updated_successfully'),
                    'redirect' => route('admin.coupons.index')
                ]);
            }

            return redirect()
                ->route('admin.coupons.index')
                ->with('success', __('admin.coupon.updated_successfully'));

        } catch (\Exception $e) {
            \Log::error('Coupon update failed: ' . $e->getMessage(), [
                'coupon_id' => $coupon->id,
                'request_data' => $request->all(),
                'validated_data' => $validated ?? null,
                'is_active_value' => $request->input('is_active'),
                'has_is_active' => $request->has('is_active'),
                'exception_trace' => $e->getTraceAsString()
            ]);
            
            $errorMessage = __('admin.coupon.update_failed') . ': ' . $e->getMessage();
            
            // Handle AJAX requests
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'errors' => ['general' => [$errorMessage]]
                ], 422);
            }
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $errorMessage);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Coupon $coupon): JsonResponse|RedirectResponse
    {
        // Check if coupon has been used
        if ($coupon->used_count > 0) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('admin.coupon.cannot_delete_used')
                ], 422);
            }
            
            return redirect()
                ->route('admin.coupons.index')
                ->with('error', __('admin.coupon.cannot_delete_used'));
        }

        $coupon->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => __('admin.coupon.deleted_successfully')
            ]);
        }

        return redirect()
            ->route('admin.coupons.index')
            ->with('success', __('admin.coupon.deleted_successfully'));
    }

    /**
     * Toggle coupon status
     */
    public function toggleStatus(Coupon $coupon): JsonResponse|RedirectResponse
    {
        $coupon->update(['is_active' => !$coupon->is_active]);

        $status = $coupon->is_active ? 'activated' : 'deactivated';
        $message = __("admin.coupon.{$status}_successfully");
        
        if (request()->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $message,
                'status' => $coupon->is_active ? 'active' : 'inactive'
            ]);
        }
        
        return redirect()
            ->route('admin.coupons.index')
            ->with('success', $message);
    }

    /**
     * Generate a unique coupon code
     */
    public function generateCode(): JsonResponse
    {
        $code = Coupon::generateUniqueCode();
        
        return response()->json([
            'success' => true,
            'code' => $code
        ]);
    }

    /**
     * Validate coupon code for checkout
     */
    public function validateCoupon(Request $request): JsonResponse
    {
        $request->validate([
            'code' => 'required|string',
            'order_total' => 'required|numeric|min:0'
        ]);

        $coupon = Coupon::byCode($request->code)->first();

        if (!$coupon) {
            return response()->json([
                'valid' => false,
                'message' => __('admin.coupon.not_found')
            ]);
        }

        $userId = auth()->id();
        $validation = $coupon->isValid($userId, $request->order_total);

        if (!$validation['valid']) {
            return response()->json($validation);
        }

        $discount = $coupon->calculateDiscount($request->order_total);
        $newTotal = $request->order_total - $discount;

        return response()->json([
            'valid' => true,
            'message' => __('admin.coupon.applied_successfully'),
            'coupon' => [
                'id' => $coupon->id,
                'code' => $coupon->code,
                'name' => $coupon->getTranslation('name'),
                'type' => $coupon->type,
                'value' => $coupon->formatted_value
            ],
            'discount' => number_format($discount, 2),
            'formatted_discount' => '$' . number_format($discount, 2),
            'new_total' => number_format($newTotal, 2),
            'formatted_new_total' => '$' . number_format($newTotal, 2)
        ]);
    }
}
