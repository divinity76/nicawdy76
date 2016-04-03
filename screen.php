<?php
/*FILE INFO:
Style manipulations*/
header("Content-type: text/css");
include ("config.php");
function detect()
    {
    $browser = array ("IE","OPERA","MOZILLA","NETSCAPE","FIREFOX","SAFARI");
    $os = array ("WIN","MAC");
    $info['browser'] = "OTHER";
    $info['os'] = "OTHER";
    foreach ($browser as $parent)
        {
        $s = strpos(strtoupper($_SERVER['HTTP_USER_AGENT']), $parent);
        $f = $s + strlen($parent);
        $version = substr($_SERVER['HTTP_USER_AGENT'], $f, 5);
        $version = preg_replace('/[^0-9,.]/','',$version);
        if ($s)
            {
            $info['browser'] = $parent;
            $info['version'] = $version;
            }
        }
    foreach ($os as $val)
        {
        if (eregi($val,strtoupper($_SERVER['HTTP_USER_AGENT']))) $info['os'] = $val;
        }
    return $info;
    } 

$d = detect();
$b = $d['browser'];
$v = $d['version'];
$o = $d['os'];

// real CSS starts here. im making it a variable so i can mess with it later :)
$CSS=file_get_contents("skins/".$cfg['skin'].".css");

//replace /*$cfg['skin']dir*/ with images directory
$CSS = str_ireplace('/*$skindir*/','skins/'.$cfg['skin'].'/',$CSS);

//lets fix IE png background problems now. a bit complexed, but works great :P
$pattern="/background-image:\s+url\(['\"]?([^()'\"]*?\.png[^()'\"]*?)['\"]?\)\s*;/";
preg_match_all($pattern,$CSS,$out,PREG_PATTERN_ORDER);
$i=0;
$IECSS=$CSS;
while (isset($out[0][$i])){
	$fix= "filter: progid:DXImageTransform.Microsoft.AlphaImageLoader(src='".$out[1][$i]."', sizingMethod='image');";
	$IECSS=str_replace($out[0][$i],$fix,$IECSS);
	$i++;
}
//hacks for IE
//uncoment /*IE margin-right: -15px */
$pattern="/\/\*IE(.+?)\*\//";
preg_match_all($pattern, $IECSS, $out, PREG_PATTERN_ORDER);
$IECSS=str_replace($out[0], $out[1], $IECSS);

//output CSS according to browser type
if ($b=="IE" && ($v=="5.5" || $v=="6")){echo $IECSS;}
else {echo $CSS;}

?>
