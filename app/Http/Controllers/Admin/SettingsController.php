<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSection;
use App\Models\Product;
use App\Services\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class SettingsController extends Controller
{
    /**
     * Display general settings page
     */
    public function index(): View
    {
        $settings = SettingsService::getAll();
        
        // Get hero sections for hero management
        $heroSections = HomepageSection::ofType('hero')->ordered()->get();
        
        return view('admin.settings.index', compact('settings', 'heroSections'));
    }

    /**
     * Update general settings
     */
    public function update(Request $request): RedirectResponse
    {
        // Base validation rules
        $rules = [
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_address_en' => 'nullable|string|max:500',
            'contact_address_ar' => 'nullable|string|max:500',
            'facebook_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'linkedin_url' => 'nullable|url|max:255',
            'whatsapp_number' => 'nullable|string|max:20',
            'payment_methods' => 'nullable|array',
            'payment_methods.*' => 'string|in:bank_transfer,crypto_usdt,crypto_btc,mtn_cash,syriatel_cash',
            'currency' => 'nullable|string|max:10',
            'tax_rate' => 'nullable|numeric|min:0|max:100',
            'shipping_fee' => 'nullable|numeric|min:0',
            'free_shipping_threshold' => 'nullable|numeric|min:0',
            // Payment details
            'bank_name' => 'nullable|string|max:255',
            'account_name' => 'nullable|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'iban' => 'nullable|string|max:255',
            'usdt_wallet' => 'nullable|string|max:255',
            'usdt_network' => 'nullable|string|in:TRC20,ERC20,BEP20',
            'btc_wallet' => 'nullable|string|max:255',
            // Mobile payment methods
            'mtn_cash_phone' => 'nullable|string|max:20|regex:/^[\+]?[0-9\s\-\(\)]+$/',
            'syriatel_cash_phone' => 'nullable|string|max:20|regex:/^[\+]?[0-9\s\-\(\)]+$/',
        ];

        // Conditional validation: require phone numbers when payment methods are enabled
        $paymentMethods = $request->input('payment_methods', []);
        if (in_array('mtn_cash', $paymentMethods)) {
            $rules['mtn_cash_phone'] = 'required|string|max:20|regex:/^[\+]?[0-9\s\-\(\)]+$/';
        }
        if (in_array('syriatel_cash', $paymentMethods)) {
            $rules['syriatel_cash_phone'] = 'required|string|max:20|regex:/^[\+]?[0-9\s\-\(\)]+$/';
        }

        $validated = $request->validate($rules, [
            'mtn_cash_phone.required' => __('admin.site_settings.mtn_cash_phone_required'),
            'mtn_cash_phone.regex' => __('admin.site_settings.phone_must_be_valid'),
            'syriatel_cash_phone.required' => __('admin.site_settings.syriatel_cash_phone_required'),
            'syriatel_cash_phone.regex' => __('admin.site_settings.phone_must_be_valid'),
        ]);

        try {
            // Update all settings
            $settingsToUpdate = $validated;
            
            SettingsService::setMultiple($settingsToUpdate);

            return redirect()
                ->route('admin.settings.index')
                ->with('success', __('admin.site_settings.updated_successfully'));

        } catch (\Exception $e) {
            \Log::error('Settings update failed: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', __('admin.site_settings.update_failed'));
        }
    }


    /**
     * Store new hero section
     */
    public function storeHero(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title_en' => 'required|string|max:255',
            'title_ar' => 'nullable|string|max:255',
            'subtitle_en' => 'nullable|string|max:500',
            'subtitle_ar' => 'nullable|string|max:500',
            'description_en' => 'nullable|string|max:1000',
            'description_ar' => 'nullable|string|max:1000',
            'button_text_en' => 'nullable|string|max:100',
            'button_text_ar' => 'nullable|string|max:100',
            'link_url' => 'nullable|url|max:500',
            'image' => 'nullable|image|max:5120', // 5MB max
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ], [
            'title_en.required' => __('admin.homepage.title_en_required'),
            'image.image' => __('admin.homepage.image_must_be_image'),
            'image.max' => __('admin.homepage.image_max_5mb'),
        ]);

        try {
            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('homepage', 'public');
            }

            // Build content array
            $content = $this->buildHeroContentArray($validated);

            HomepageSection::create([
                'section_type' => 'hero',
                'title' => $validated['title_en'] ?? null,
                'content' => $content,
                'image_path' => $imagePath,
                'link_url' => $validated['link_url'] ?? null,
                'is_active' => $request->boolean('is_active'),
                'sort_order' => $validated['sort_order'] ?? 0,
            ]);

            // Clear homepage cache
            \Cache::forget('homepage_sections');
            
            return redirect()
                ->route('admin.settings.index')
                ->with('success', __('admin.homepage.section_created'));

        } catch (\Exception $e) {
            \Log::error('Hero section creation failed: ' . $e->getMessage(), [
                'request_data' => $request->all(),
                'validated_data' => $validated ?? null,
                'is_active_value' => $request->input('is_active'),
                'has_is_active' => $request->has('is_active')
            ]);
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', __('admin.homepage.creation_failed') . ': ' . $e->getMessage());
        }
    }

    /**
     * Get hero section data for editing
     */
    public function editHero(HomepageSection $hero): JsonResponse
    {
        try {
            return response()->json([
                'success' => true,
                'hero' => [
                    'id' => $hero->id,
                    'content' => $hero->content,
                    'link_url' => $hero->link_url,
                    'sort_order' => $hero->sort_order,
                    'is_active' => $hero->is_active,
                    'image_url' => $hero->image_url
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('admin.homepage.load_failed')
            ], 500);
        }
    }

    /**
     * Update hero section
     */
    public function updateHero(Request $request, HomepageSection $hero): RedirectResponse
    {
        $validated = $request->validate([
            'title_en' => 'nullable|string|max:255',
            'title_ar' => 'nullable|string|max:255',
            'subtitle_en' => 'nullable|string|max:500',
            'subtitle_ar' => 'nullable|string|max:500',
            'description_en' => 'nullable|string|max:1000',
            'description_ar' => 'nullable|string|max:1000',
            'button_text_en' => 'nullable|string|max:100',
            'button_text_ar' => 'nullable|string|max:100',
            'link_url' => 'nullable|url|max:500',
            'image' => 'nullable|image|max:5120',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer|min:0',
        ]);

        try {
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($hero->image_path) {
                    Storage::disk('public')->delete($hero->image_path);
                }
                
                $imagePath = $request->file('image')->store('homepage', 'public');
                $hero->image_path = $imagePath;
            }

            // Build content array
            $content = $this->buildHeroContentArray($validated);

            $hero->update([
                'title' => $validated['title_en'] ?? $hero->title,
                'content' => $content,
                'link_url' => $validated['link_url'] ?? $hero->link_url,
                'is_active' => $request->boolean('is_active'),
                'sort_order' => $validated['sort_order'] ?? $hero->sort_order,
            ]);

            // Clear homepage cache
            \Cache::forget('homepage_sections');
            
            return redirect()
                ->route('admin.settings.index')
                ->with('success', __('admin.homepage.section_updated'));

        } catch (\Exception $e) {
            \Log::error('Hero section update failed: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', __('admin.homepage.update_failed'));
        }
    }

    /**
     * Toggle hero section active status
     */
    public function toggleHeroStatus(HomepageSection $hero): JsonResponse
    {
        try {
            $hero->update(['is_active' => !$hero->is_active]);
            
            // Clear homepage cache
            \Cache::forget('homepage_sections');
            
            return response()->json([
                'success' => true,
                'message' => __('admin.homepage.status_updated'),
                'is_active' => $hero->is_active
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('admin.homepage.status_update_failed')
            ], 500);
        }
    }

    /**
     * Delete hero section
     */
    public function destroyHero(HomepageSection $hero): RedirectResponse
    {
        try {
            // Delete image if exists
            if ($hero->image_path) {
                Storage::disk('public')->delete($hero->image_path);
            }

            $hero->delete();

            // Clear homepage cache
            \Cache::forget('homepage_sections');

            return redirect()
                ->route('admin.settings.index')
                ->with('success', __('admin.homepage.section_deleted'));

        } catch (\Exception $e) {
            \Log::error('Hero section deletion failed: ' . $e->getMessage());
            
            return redirect()
                ->back()
                ->with('error', __('admin.homepage.deletion_failed'));
        }
    }

    /**
     * Build content array for hero section
     */
    private function buildHeroContentArray(array $validated): array
    {
        $content = [];

        // Title
        if (isset($validated['title_en']) || isset($validated['title_ar'])) {
            $content['title'] = array_filter([
                'en' => $validated['title_en'] ?? '',
                'ar' => $validated['title_ar'] ?? ''
            ]);
        }

        // Subtitle
        if (isset($validated['subtitle_en']) || isset($validated['subtitle_ar'])) {
            $content['subtitle'] = array_filter([
                'en' => $validated['subtitle_en'] ?? '',
                'ar' => $validated['subtitle_ar'] ?? ''
            ]);
        }

        // Description
        if (isset($validated['description_en']) || isset($validated['description_ar'])) {
            $content['description'] = array_filter([
                'en' => $validated['description_en'] ?? '',
                'ar' => $validated['description_ar'] ?? ''
            ]);
        }

        // Button text
        if (isset($validated['button_text_en']) || isset($validated['button_text_ar'])) {
            $content['button_text'] = array_filter([
                'en' => $validated['button_text_en'] ?? '',
                'ar' => $validated['button_text_ar'] ?? ''
            ]);
        }

        return $content;
    }
}
