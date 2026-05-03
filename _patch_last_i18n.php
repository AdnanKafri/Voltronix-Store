<?php
function setdot(array &$arr, string $path, $value): void {
  $parts = explode('.', $path); $ref =& $arr;
  foreach ($parts as $i => $p) {
    if ($i === count($parts)-1) { $ref[$p] = $value; return; }
    if (!isset($ref[$p]) || !is_array($ref[$p])) $ref[$p] = [];
    $ref =& $ref[$p];
  }
}
function export_array($v,$i=0){ if(!is_array($v)) return var_export($v,true); $p=str_repeat('    ',$i); $ip=str_repeat('    ',$i+1); if($v===[]) return '[]'; $l=["["]; foreach($v as $k=>$vv){$kk=is_int($k)?$k:var_export((string)$k,true); $l[]=$ip.$kk.' => '.export_array($vv,$i+1).',';} $l[]=$p.']'; return implode("\n",$l);} 
$targets = [
 ['file'=>'lang/en/admin.php','kv'=>['nav.toggle_sidebar'=>'Toggle Sidebar','coupon.type_'=>'Type','orders.status_'=>'Status']],
 ['file'=>'lang/ar/admin.php','kv'=>['nav.toggle_sidebar'=>'????? ?????? ???????','coupon.type_'=>'?????','orders.status_'=>'??????']],
 ['file'=>'lang/en/app.php','kv'=>['products.sample_'=>'Sample']],
 ['file'=>'lang/ar/app.php','kv'=>['products.sample_'=>'?????']],
];
foreach($targets as $t){$arr=include $t['file']; foreach($t['kv'] as $k=>$v){setdot($arr,$k,$v);} file_put_contents($t['file'],"<?php\n\nreturn ".export_array($arr).";\n"); echo "Patched {$t['file']}\n";}
