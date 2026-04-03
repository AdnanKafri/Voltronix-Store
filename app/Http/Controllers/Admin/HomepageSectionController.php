<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HomepageSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class HomepageSectionController extends Controller
{
    /**
     * Display a listing of homepage sections
     */
    public function index()
    {
        $sections = HomepageSection::orderBy('sort_order')
            ->orderBy('created_at')
            ->paginate(15);

        return view('admin.homepage-sections.index', compact('sections'));
    }

    /**
     * Show the form for creating a new section
     */
    public function create()
    {
        $sectionTypes = [
            HomepageSection::TYPE_HERO => __('admin.homepage.hero_section'),
            HomepageSection::TYPE_BANNER => __('admin.homepage.banner_section'),
            HomepageSection::TYPE_FEATURED_PRODUCTS => __('admin.homepage.featured_products'),
            HomepageSection::TYPE_TESTIMONIAL => __('admin.homepage.testimonial_section'),
            HomepageSection::TYPE_STATS => __('admin.homepage.stats_section'),
            HomepageSection::TYPE_NEWSLETTER => __('admin.homepage.newsletter_section'),
        ];

        return view('admin.homepage-sections.create', compact('sectionTypes'));
    }

    /**
     * Store a newly created section
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'section_type' => 'required|string|max:50',
            'title' => 'nullable|string|max:255',
            'content' => 'required|array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'link_url' => 'nullable|url|max:500',
            'link_text' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'settings' => 'nullable|array'
        ]);

        try {
            // Handle image upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('homepage-sections', 'public');
                $validated['image_path'] = $imagePath;
            }

            // Create the section
            $section = HomepageSection::create($validated);

            // Clear homepage cache
            $this->clearHomepageCache();

            return redirect()->route('admin.homepage-sections.index')
                ->with('success', __('admin.homepage.section_created_successfully'));

        } catch (\Exception $e) {
            \Log::error('Homepage section creation failed: ' . $e->getMessage());
            
            return redirect()->back()->withInput()
                ->with('error', __('admin.homepage.section_creation_failed'));
        }
    }

    /**
     * Display the specified section
     */
    public function show(HomepageSection $homepageSection)
    {
        return view('admin.homepage-sections.show', compact('homepageSection'));
    }

    /**
     * Show the form for editing the specified section
     */
    public function edit(HomepageSection $homepageSection)
    {
        $sectionTypes = [
            HomepageSection::TYPE_HERO => __('admin.homepage.hero_section'),
            HomepageSection::TYPE_BANNER => __('admin.homepage.banner_section'),
            HomepageSection::TYPE_FEATURED_PRODUCTS => __('admin.homepage.featured_products'),
            HomepageSection::TYPE_TESTIMONIAL => __('admin.homepage.testimonial_section'),
            HomepageSection::TYPE_STATS => __('admin.homepage.stats_section'),
            HomepageSection::TYPE_NEWSLETTER => __('admin.homepage.newsletter_section'),
        ];

        return view('admin.homepage-sections.edit', compact('homepageSection', 'sectionTypes'));
    }

    /**
     * Update the specified section
     */
    public function update(Request $request, HomepageSection $homepageSection)
    {
        $validated = $request->validate([
            'section_type' => 'required|string|max:50',
            'title' => 'nullable|string|max:255',
            'content' => 'required|array',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'link_url' => 'nullable|url|max:500',
            'link_text' => 'nullable|string|max:100',
            'is_active' => 'boolean',
            'sort_order' => 'integer|min:0',
            'settings' => 'nullable|array'
        ]);

        try {
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($homepageSection->image_path) {
                    Storage::disk('public')->delete($homepageSection->image_path);
                }
                
                $imagePath = $request->file('image')->store('homepage-sections', 'public');
                $validated['image_path'] = $imagePath;
            }

            // Update the section
            $homepageSection->update($validated);

            // Clear homepage cache
            $this->clearHomepageCache();

            return redirect()->route('admin.homepage-sections.index')
                ->with('success', __('admin.homepage.section_updated_successfully'));

        } catch (\Exception $e) {
            \Log::error('Homepage section update failed: ' . $e->getMessage());
            
            return redirect()->back()->withInput()
                ->with('error', __('admin.homepage.section_update_failed'));
        }
    }

    /**
     * Remove the specified section
     */
    public function destroy(HomepageSection $homepageSection)
    {
        try {
            // Delete associated image
            if ($homepageSection->image_path) {
                Storage::disk('public')->delete($homepageSection->image_path);
            }

            $homepageSection->delete();

            // Clear homepage cache
            $this->clearHomepageCache();

            return redirect()->route('admin.homepage-sections.index')
                ->with('success', __('admin.homepage.section_deleted_successfully'));

        } catch (\Exception $e) {
            \Log::error('Homepage section deletion failed: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', __('admin.homepage.section_deletion_failed'));
        }
    }

    /**
     * Toggle section active status
     */
    public function toggleStatus(HomepageSection $homepageSection)
    {
        try {
            $homepageSection->update([
                'is_active' => !$homepageSection->is_active
            ]);

            // Clear homepage cache
            $this->clearHomepageCache();

            $status = $homepageSection->is_active ? __('admin.common.activated') : __('admin.common.deactivated');
            
            return response()->json([
                'success' => true,
                'message' => __('admin.homepage.section_status_updated', ['status' => $status]),
                'is_active' => $homepageSection->is_active
            ]);

        } catch (\Exception $e) {
            \Log::error('Homepage section status toggle failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => __('admin.homepage.status_update_failed')
            ], 500);
        }
    }

    /**
     * Update section sort order
     */
    public function updateOrder(Request $request)
    {
        $validated = $request->validate([
            'sections' => 'required|array',
            'sections.*.id' => 'required|exists:homepage_sections,id',
            'sections.*.sort_order' => 'required|integer|min:0'
        ]);

        try {
            foreach ($validated['sections'] as $sectionData) {
                HomepageSection::where('id', $sectionData['id'])
                    ->update(['sort_order' => $sectionData['sort_order']]);
            }

            // Clear homepage cache
            $this->clearHomepageCache();

            return response()->json([
                'success' => true,
                'message' => __('admin.homepage.order_updated_successfully')
            ]);

        } catch (\Exception $e) {
            \Log::error('Homepage section order update failed: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => __('admin.homepage.order_update_failed')
            ], 500);
        }
    }

    /**
     * Clear homepage related caches
     */
    private function clearHomepageCache()
    {
        Cache::forget('homepage_sections');
        Cache::forget('homepage_categories');
        Cache::forget('homepage_latest_products');
        Cache::forget('homepage_featured_products');
        Cache::forget('homepage_popular_products');
        Cache::forget('homepage_testimonials');
        Cache::forget('homepage_stats');
    }
}
