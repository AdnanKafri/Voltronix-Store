<?php
function flatten(array $arr, string $prefix=''): array {
    $out = [];
    foreach ($arr as $k => $v) {
        $key = $prefix === '' ? (string)$k : $prefix.'.'.$k;
        if (is_array($v)) {
            $out += flatten($v, $key);
        } else {
            $out[$key] = $v;
        }
    }
    return $out;
}
function setdot(array &$arr, string $path, $value): void {
    $parts = explode('.', $path);
    $ref =& $arr;
    foreach ($parts as $p) {
        if (!isset($ref[$p]) || !is_array($ref[$p])) {
            $ref[$p] = [];
        }
        $ref =& $ref[$p];
    }
    $ref = $value;
}
function export_array($value, int $indent = 0): string {
    if (!is_array($value)) {
        return var_export($value, true);
    }
    $pad = str_repeat('    ', $indent);
    $innerPad = str_repeat('    ', $indent + 1);
    if ($value === []) return '[]';
    $lines = ["["];
    foreach ($value as $k => $v) {
        $key = is_int($k) ? $k : var_export((string)$k, true);
        $lines[] = $innerPad . $key . ' => ' . export_array($v, $indent + 1) . ',';
    }
    $lines[] = $pad . ']';
    return implode("\n", $lines);
}
function default_en(string $dot): string {
    $leaf = preg_replace('/.*\./', '', $dot);
    $leaf = str_replace(['_', '-'], ' ', $leaf);
    return ucwords(trim($leaf));
}
$files = ['app.php','admin.php','orders.php','products.php','auth.php','pagination.php','validation.php','passwords.php','emails.php'];
foreach ($files as $file) {
    $enPath = __DIR__ . '/lang/en/' . $file;
    $arPath = __DIR__ . '/lang/ar/' . $file;
    if (!file_exists($enPath) || !file_exists($arPath)) continue;
    $en = include $enPath;
    $ar = include $arPath;
    if (!is_array($en) || !is_array($ar)) continue;

    $enFlat = flatten($en);
    $arFlat = flatten($ar);
    $allKeys = array_unique(array_merge(array_keys($enFlat), array_keys($arFlat)));

    foreach ($allKeys as $k) {
        if (!array_key_exists($k, $enFlat)) {
            setdot($en, $k, is_string($arFlat[$k] ?? null) ? $arFlat[$k] : default_en($k));
        }
        if (!array_key_exists($k, $arFlat)) {
            setdot($ar, $k, is_string($enFlat[$k] ?? null) ? $enFlat[$k] : default_en($k));
        }
    }

    file_put_contents($enPath, "<?php\n\nreturn " . export_array($en) . ";\n");
    file_put_contents($arPath, "<?php\n\nreturn " . export_array($ar) . ";\n");
    echo "Synced {$file}\n";
}
