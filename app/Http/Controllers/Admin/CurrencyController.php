<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Services\CurrencyUpdateService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $currencies = Currency::orderBy('is_default', 'desc')
                             ->orderBy('is_active', 'desc')
                             ->orderBy('code')
                             ->get();
        
        $stats = [
            'total' => Currency::count(),
            'active' => Currency::where('is_active', true)->count(),
            'default' => Currency::where('is_default', true)->first()?->code ?? 'None'
        ];

        return view('admin.currencies.index', compact('currencies', 'stats'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.currencies.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'code' => 'required|string|size:3|unique:currencies,code',
            'symbol' => 'required|string|max:10',
            'exchange_rate' => 'required|numeric|min:0.00000001|max:999999999',
            'is_active' => 'boolean',
            'is_default' => 'boolean'
        ]);

        // Create currency
        $currency = Currency::create([
            'name' => [
                'en' => $validated['name_en'],
                'ar' => $validated['name_ar']
            ],
            'code' => strtoupper($validated['code']),
            'symbol' => $validated['symbol'],
            'exchange_rate' => $validated['exchange_rate'],
            'is_active' => $validated['is_active'] ?? true,
            'is_default' => false // Will be set via setAsDefault if needed
        ]);

        // Set as default if requested
        if ($validated['is_default'] ?? false) {
            $currency->setAsDefault();
        }

        return redirect()
            ->route('admin.currencies.index')
            ->with('success', __('admin.currency.created_successfully'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Currency $currency): View
    {
        return view('admin.currencies.show', compact('currency'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Currency $currency): View
    {
        return view('admin.currencies.edit', compact('currency'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Currency $currency): RedirectResponse
    {
        $validated = $request->validate([
            'name_en' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'code' => ['required', 'string', 'size:3', Rule::unique('currencies')->ignore($currency->id)],
            'symbol' => 'required|string|max:10',
            'exchange_rate' => 'required|numeric|min:0.00000001|max:999999999',
            'is_active' => 'boolean',
            'is_default' => 'boolean'
        ]);

        // Update currency
        $currency->update([
            'name' => [
                'en' => $validated['name_en'],
                'ar' => $validated['name_ar']
            ],
            'code' => strtoupper($validated['code']),
            'symbol' => $validated['symbol'],
            'exchange_rate' => $validated['exchange_rate'],
            'is_active' => $validated['is_active'] ?? $currency->is_active
        ]);

        // Set as default if requested
        if ($validated['is_default'] ?? false) {
            $currency->setAsDefault();
        }

        return redirect()
            ->route('admin.currencies.index')
            ->with('success', __('admin.currency.updated_successfully'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Currency $currency): JsonResponse
    {
        // Prevent deletion of default currency
        if ($currency->is_default) {
            return response()->json([
                'success' => false,
                'message' => __('admin.currency.cannot_delete_default')
            ], 400);
        }

        // Check if currency is being used in orders (if orders table exists)
        // This would need to be implemented when orders are updated to store currency info

        $currency->delete();

        return response()->json([
            'success' => true,
            'message' => __('admin.currency.deleted_successfully')
        ]);
    }

    /**
     * Toggle currency status
     */
    public function toggleStatus(Currency $currency): JsonResponse
    {
        // Prevent deactivating default currency
        if ($currency->is_default && $currency->is_active) {
            return response()->json([
                'success' => false,
                'message' => __('admin.currency.cannot_deactivate_default')
            ], 400);
        }

        $currency->update(['is_active' => !$currency->is_active]);

        $message = $currency->is_active 
            ? __('admin.currency.activated_successfully')
            : __('admin.currency.deactivated_successfully');

        return response()->json([
            'success' => true,
            'message' => $message,
            'is_active' => $currency->is_active
        ]);
    }

    /**
     * Set currency as default
     */
    public function setDefault(Currency $currency): JsonResponse
    {
        $currency->setAsDefault();

        return response()->json([
            'success' => true,
            'message' => __('admin.currency.set_default_successfully')
        ]);
    }

    /**
     * Update exchange rate
     */
    public function updateRate(Request $request, Currency $currency): JsonResponse
    {
        $validated = $request->validate([
            'exchange_rate' => 'required|numeric|min:0.00000001|max:999999999'
        ]);

        $currency->update(['exchange_rate' => $validated['exchange_rate']]);

        return response()->json([
            'success' => true,
            'message' => __('admin.currency.rate_updated_successfully'),
            'formatted_rate' => $currency->formatted_rate
        ]);
    }

    /**
     * Update all currency rates from API
     * 
     * @param CurrencyUpdateService $updateService
     * @return JsonResponse
     */
    public function updateAllRates(CurrencyUpdateService $updateService): JsonResponse
    {
        try {
            Log::info('Manual currency update triggered by admin', [
                'admin_id' => auth('admin')->id(),
                'timestamp' => now()
            ]);

            // Force update by clearing cache
            $updateService->clearUpdateCache();
            
            // Perform update
            $result = $updateService->updateAllRates();

            if ($result['success']) {
                Log::info('Manual currency update completed successfully', [
                    'updated_count' => $result['updated'],
                    'admin_id' => auth('admin')->id()
                ]);

                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'updated' => $result['updated'],
                    'skipped' => $result['skipped'] ?? [],
                    'errors' => $result['errors'] ?? [],
                    'last_updated' => now()->format('Y-m-d H:i:s')
                ]);
            } else {
                Log::error('Manual currency update failed', [
                    'message' => $result['message'],
                    'errors' => $result['errors'] ?? [],
                    'admin_id' => auth('admin')->id()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => $result['message'],
                    'errors' => $result['errors'] ?? []
                ], 500);
            }

        } catch (\Exception $e) {
            Log::error('Manual currency update exception', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'admin_id' => auth('admin')->id()
            ]);

            return response()->json([
                'success' => false,
                'message' => __('admin.currency.update_failed') . ': ' . $e->getMessage(),
                'errors' => [$e->getMessage()]
            ], 500);
        }
    }
}
