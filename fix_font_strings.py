import os

files_to_fix = [
    'resources/views/delivery/credentials.blade.php',
    'resources/views/checkout/success.blade.php',
    'resources/views/home.blade.php',
    'resources/views/layouts/app.blade.php'
]

for filepath in files_to_fix:
    full_path = os.path.join(os.getcwd(), filepath)
    if os.path.exists(full_path):
        with open(full_path, 'r', encoding='utf-8') as f:
            content = f.read()
        
        content = content.replace("'Tajawal', 'Noto Sans Arabic', sans-serif", "var(--font-ar-heading)")
            
        with open(full_path, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f'Processed {filepath}')
