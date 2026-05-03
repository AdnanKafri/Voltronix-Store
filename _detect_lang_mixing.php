<?php
$files=['app','admin','orders','products'];
foreach($files as $f){
 $en=include __DIR__."/lang/en/$f.php";
 $ar=include __DIR__."/lang/ar/$f.php";
 $flat=function($a,$p='') use (&$flat){$o=[]; foreach($a as $k=>$v){$d=$p===''?$k:"$p.$k"; if(is_array($v)) $o+=$flat($v,$d); else $o[$d]=$v;} return $o;};
 $ef=$flat($en); $af=$flat($ar);
 echo "[$f]\n";
 foreach($ef as $k=>$v){ if(is_string($v)&&preg_match('/[\x{0600}-\x{06FF}]/u',$v)) echo "EN_ARABIC $k => $v\n"; }
 foreach($af as $k=>$v){ if(is_string($v)&&preg_match('/[A-Za-z]/',$v) && !preg_match('/(USD|EUR|BTC|USDT|MTN|Syriatel|Voltronix|PDF|OTP|IP|API|URL|SVG|PNG|JPG|WebP|ICO)/i',$v)) echo "AR_ENGLISH $k => $v\n"; }
}
