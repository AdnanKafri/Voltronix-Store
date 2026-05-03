<?php
$root = __DIR__;
$targets = ["$root/resources/views", "$root/app"];
$keys = [];
$pattern = '/__(\s*[\(]?[\"\']([A-Za-z0-9_\.-]+)[\"\'])|@lang\(\s*[\"\']([A-Za-z0-9_\.-]+)[\"\']|trans\(\s*[\"\']([A-Za-z0-9_\.-]+)[\"\']/';
$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root, FilesystemIterator::SKIP_DOTS));
foreach ($rii as $file) {
    $path = $file->getPathname();
    if (!str_ends_with($path, '.php') && !str_ends_with($path, '.blade.php')) continue;
    if (!(str_contains($path, DIRECTORY_SEPARATOR.'resources'.DIRECTORY_SEPARATOR.'views'.DIRECTORY_SEPARATOR) || str_contains($path, DIRECTORY_SEPARATOR.'app'.DIRECTORY_SEPARATOR))) continue;
    $content = file_get_contents($path);
    if (preg_match_all('/__\(\s*[\"\']([A-Za-z0-9_\.-]+)[\"\']/', $content, $m1)) {
        foreach ($m1[1] as $k) $keys[$k]=true;
    }
    if (preg_match_all('/@lang\(\s*[\"\']([A-Za-z0-9_\.-]+)[\"\']/', $content, $m2)) {
        foreach ($m2[1] as $k) $keys[$k]=true;
    }
    if (preg_match_all('/trans\(\s*[\"\']([A-Za-z0-9_\.-]+)[\"\']/', $content, $m3)) {
        foreach ($m3[1] as $k) $keys[$k]=true;
    }
}
ksort($keys);

require $root.'/vendor/autoload.php';
$app = require_once $root.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$translator = $app->make('translator');
$missing = [];
foreach (array_keys($keys) as $key) {
    if (!str_contains($key, '.')) continue;
    $okEn = $translator->hasForLocale($key, 'en');
    $okAr = $translator->hasForLocale($key, 'ar');
    if (!$okEn || !$okAr) {
        $missing[] = [$key, $okEn ? 'ok' : 'missing', $okAr ? 'ok' : 'missing'];
    }
}

foreach ($missing as [$k,$e,$a]) {
    echo "$k\ten:$e\tar:$a\n";
}

echo "TOTAL_KEYS=".count($keys)."\n";
echo "MISSING=".count($missing)."\n";
