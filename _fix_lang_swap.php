<?php
function flatten(array $arr, string $prefix=''): array { $out=[]; foreach($arr as $k=>$v){$key=$prefix===''?$k:"$prefix.$k"; if(is_array($v)) $out+=flatten($v,$key); else $out[$key]=$v;} return $out; }
function setdot(array &$arr,string $path,$value): void { $parts=explode('.',$path); $ref=&$arr; foreach($parts as $i=>$p){ if($i===count($parts)-1){$ref[$p]=$value; return;} if(!isset($ref[$p])||!is_array($ref[$p])) $ref[$p]=[]; $ref=&$ref[$p]; }}
function hasArabic(string $s): bool { return preg_match('/[\x{0600}-\x{06FF}]/u',$s)===1; }
function hasLatin(string $s): bool { return preg_match('/[A-Za-z]/',$s)===1; }
function exportArr($v,$i=0){ if(!is_array($v)) return var_export($v,true); $p=str_repeat('    ',$i); $ip=str_repeat('    ',$i+1); if($v===[]) return '[]'; $l=["["]; foreach($v as $k=>$vv){$kk=is_int($k)?$k:var_export((string)$k,true); $l[]=$ip.$kk.' => '.exportArr($vv,$i+1).',';} $l[]=$p.']'; return implode("\n",$l); }
$files=['app.php','admin.php','orders.php','products.php'];
foreach($files as $f){
  $enp=__DIR__."/lang/en/$f"; $arp=__DIR__."/lang/ar/$f";
  $en=include $enp; $ar=include $arp;
  $ef=flatten($en); $af=flatten($ar);
  $swaps=0;
  foreach($ef as $k=>$ev){
    if(!isset($af[$k]) || !is_string($ev) || !is_string($af[$k])) continue;
    $av=$af[$k];
    if(hasArabic($ev) && hasLatin($av) && !hasArabic($av)){
      setdot($en,$k,$av); setdot($ar,$k,$ev); $swaps++;
    }
  }
  file_put_contents($enp,"<?php\n\nreturn ".exportArr($en).";\n");
  file_put_contents($arp,"<?php\n\nreturn ".exportArr($ar).";\n");
  echo "$f swaps=$swaps\n";
}
