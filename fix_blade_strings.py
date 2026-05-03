import os

files_to_fix = {
    'resources/views/admin/products/show.blade.php': [
        ("{{ $product->getTranslation('name', 'ar') ?: 'غير متوفر' }}", "{{ $product->getTranslation('name', 'ar') ?: __('admin.products.not_available') }}"),
        ("{{ $product->getTranslation('description', 'ar') ?: 'لا يوجد وصف' }}", "{{ $product->getTranslation('description', 'ar') ?: __('admin.products.no_description') }}"),
        ("لا توجد ميزات عربية", "{{ __('admin.products.no_arabic_features') }}"),
    ],
    'resources/views/admin/products/create.blade.php': [
        ('<span>العربية</span>', '<span>{{ __(\'app.common.arabic\') }}</span>'),
        ('<span>English</span>', '<span>{{ __(\'app.common.english\') }}</span>'),
        ('أدخل اسم المنتج بالعربية', '{{ __(\'admin.products.enter_name_ar\') }}'),
        ('أدخل وصف المنتج التفصيلي بالعربية', '{{ __(\'admin.products.enter_desc_ar\') }}'),
        ('أدخل ميزة المنتج بالعربية', '{{ __(\'admin.products.enter_feature_ar\') }}'),
        ('إضافة ميزة', '{{ __(\'admin.products.add_feature\') }}'),
        ("isArabic ? 'أدخل ميزة المنتج بالعربية' : 'Enter product feature in English'", "isArabic ? '{{ __('admin.products.enter_feature_ar') }}' : '{{ __('admin.products.enter_feature_en') }}'"),
        ('>English Features<', '>{{ __(\'admin.products.english_features\') }}<'),
        ('>Arabic Features<', '>{{ __(\'admin.products.arabic_features\') }}<'),
        ('Create a new digital product with advanced media options', '{{ __(\'admin.products.create_desc\') }}'),
        ('Please wait while we process your request', '{{ __(\'admin.products.wait_process\') }}'),
        ('Discount Price', '{{ __(\'admin.products.discount_price\') }}'),
        ('Sort Order', '{{ __(\'admin.products.sort_order\') }}'),
        ('Product Thumbnail <span class="text-danger">*</span>', '{{ __(\'admin.products.thumbnail\') }} <span class="text-danger">*</span>'),
        ('Click to upload thumbnail', '{{ __(\'admin.products.click_upload\') }}'),
        ('Select Media Types to Add', '{{ __(\'admin.products.select_media\') }}'),
        ('Image Gallery', '{{ __(\'admin.products.image_gallery\') }}'),
        ('Upload Gallery Images', '{{ __(\'admin.products.upload_gallery\') }}'),
        ('Before Image', '{{ __(\'admin.products.before_image\') }}'),
        ('Upload Before Image', '{{ __(\'admin.products.upload_before\') }}'),
        ('After Image', '{{ __(\'admin.products.after_image\') }}'),
        ('Upload After Image', '{{ __(\'admin.products.upload_after\') }}'),
        ('Video Upload', '{{ __(\'admin.products.video_upload\') }}'),
        ('Upload Video File', '{{ __(\'admin.products.upload_video\') }}'),
        ('Upload Poster', '{{ __(\'admin.products.upload_poster\') }}'),
        ('Video Title', '{{ __(\'admin.products.video_title\') }}'),
        ('Video Description', '{{ __(\'admin.products.video_desc\') }}'),
        ('YouTube Video', '{{ __(\'admin.products.youtube_video\') }}'),
        ('YouTube URL', '{{ __(\'admin.products.youtube_url\') }}')
    ],
    'resources/views/admin/products/edit.blade.php': [
        ('<span>العربية</span>', '<span>{{ __(\'app.common.arabic\') }}</span>'),
        ('<span>English</span>', '<span>{{ __(\'app.common.english\') }}</span>'),
        ('أدخل ميزة المنتج بالعربية', '{{ __(\'admin.products.enter_feature_ar\') }}'),
        ('إضافة ميزة', '{{ __(\'admin.products.add_feature\') }}'),
        ("isArabic ? 'أدخل ميزة المنتج بالعربية' : 'Enter product feature in English'", "isArabic ? '{{ __('admin.products.enter_feature_ar') }}' : '{{ __('admin.products.enter_feature_en') }}'"),
        ('>English Features<', '>{{ __(\'admin.products.english_features\') }}<'),
        ('>Arabic Features<', '>{{ __(\'admin.products.arabic_features\') }}<'),
    ],
    'resources/views/admin/categories/create.blade.php': [
        ('العربية', '{{ __(\'app.common.arabic\') }}'),
        ('English', '{{ __(\'app.common.english\') }}'),
    ],
    'resources/views/admin/categories/edit.blade.php': [
        ('العربية', '{{ __(\'app.common.arabic\') }}'),
        ('English', '{{ __(\'app.common.english\') }}'),
    ],
    'resources/views/admin/categories/index.blade.php': [
        ("{{ $category->getTranslation('name', 'ar') ?? 'غير متوفر' }}", "{{ $category->getTranslation('name', 'ar') ?? __('admin.products.not_available') }}"),
    ],
    'resources/views/admin/partials/topbar.blade.php': [
        ('العربية', '{{ __(\'app.common.arabic\') }}'),
        ('English', '{{ __(\'app.common.english\') }}')
    ]
}

for filepath, replacements in files_to_fix.items():
    full_path = os.path.join(os.getcwd(), filepath)
    if os.path.exists(full_path):
        with open(full_path, 'r', encoding='utf-8') as f:
            content = f.read()
        
        for old, new in replacements:
            content = content.replace(old, new)
            
        with open(full_path, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f'Processed {filepath}')
