<?php 
/*FILE INFO:
included at every page header*/
//Get current time
    $mtime = microtime();
//Split seconds and microseconds
    $mtime = explode(" ",$mtime);
//Create one value for start time
    $mtime = $mtime[1] + $mtime[0];
//Write start time into a variable
    $tstart = $mtime; 
	?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php  echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="Keywords" content="opentibia, nicaw, aac, otserv" />
<meta name="Author" content="nicaw" />
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<title><?=$ptitle?></title>
<link rel="stylesheet" href="screen.php" type="text/css" media="screen" />
<link rel="stylesheet" href="print.css" type="text/css" media="print" />
<script language="javascript" type="text/javascript" src="js.js"></script>
<script language="javascript" type="text/javascript" src="skins/<?=$cfg['skin']?>.js"></script>
<link rel="shortcut icon" href="favicon.ico" />
</head>
<body>
<div id="container">
<div id="header"></div>
<div id="panel">
<div id="navigation">
<?php 
if (file_exists('navigation.xml')){
	$XML = simplexml_load_file('navigation.xml');
	if ($XML === false) die ('Malformed XML');
}else{die('Unable to load navigation.xml');}
foreach ($XML->category as $cat){
	echo '<div class="top">'.$cat['name'].'</div><ul>'."\n";
	foreach ($cat->item as $item)
		echo '<li><a href="'.$item['href'].'">'.$item.'</a></li>'."\n";
	echo '</ul><div class="bot"></div>'."\n";
}
?>
</div>
<div id="status">
<div class="top">.:Status:.</div>
<div class="mid">
<?php 
$info = getinfo($cfg['server_ip'],$cfg['server_port']);
if (!empty($info)) {
$infoXML = simplexml_load_string($info);

	$up = (int)$infoXML->serverinfo['uptime'];
	$online = (int)$infoXML->players['online'];
	$max = (int)$infoXML->players['max'];

	$h = floor($up/3600);
	$up = $up - $h*3600;
	$m = floor($up/60);
	$up = $up - $m*60;
	if ($h < 10) {$h = "0".$h;}
	if ($m < 10) {$m = "0".$m;}
	echo "<span class=\"online\">Online</span><br/>\n";
	echo "<span class=\"players\">Players: <b>$online/$max</b></span><br/>\n";
	echo "<span class=\"uptime\">Uptime: <b>$h:$m</b></span><br/>\n";
} else {
	echo "<span class=\"offline\">Offline</span>\n";
}
?>
</div>
<div class="bot"></div>
</div>
<div id="friends">
<div class="top">.:Sponsor:.</div>
<div class="mid">
<div id="keywords" style="display:none">
Funny Free Online Games MMORPG Tibia Nicaw AAC Lineage Opentibia Otserv
</div>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</div>
<div class="bot"></div>
</div>
</div>