<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$translator = $app->make('translator');

$content = shell_exec('rg -n "__\\(|@lang\\(|trans\\(" app resources\\views');
preg_match_all('/(?:__|@lang|trans)\(\s*["\']([A-Za-z0-9_\.-]+)["\']/', $content, $m);
$keys = array_values(array_unique($m[1]));
sort($keys);
foreach ($keys as $k){
  if (!str_contains($k,'.')) continue;
  if (str_ends_with($k,'.')) continue;
  if (str_contains($k,'._')) continue;
  if (preg_match('/\.$/', $k)) continue;
  if (preg_match('/\.$/', $k)) continue;
  if (preg_match('/\.[0-9]+$/', $k)) continue;
  if (!preg_match('/^(app|admin|orders|products|emails)\./',$k)) continue;
  $en = $translator->hasForLocale($k,'en');
  $ar = $translator->hasForLocale($k,'ar');
  if(!$en || !$ar){
    echo "$k\ten:".($en?'ok':'missing')."\tar:".($ar?'ok':'missing')."\n";
  }
}
