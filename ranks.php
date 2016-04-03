<?php
/*Rank displaying by rizz and wrzasq*/
include 'config.php';
include 'functions.php';
$ptitle="Highscores - $cfg[server_name]";
include 'header.php';
	// runs the script in background
  // we cannt run it directly as it takes lots of time to execute it
	// by wrzasq 
	if( (!file_exists('statistics.php') or (time()-filemtime('statistics.php')) > $cfg['rank_refresh'])	){ 
	$socket = fsockopen($cfg['server_ip'], $_SERVER['SERVER_PORT'], $errorCode, $errorString, 1);
	if ($socket === false){
		$error = 'Connection to server failed. Please check server_ip setting.';
		include('footer.php');
		die();
	}
	$url = 'GET '.str_replace(' ','%20',dirname(htmlspecialchars($_SERVER['PHP_SELF'])).'/update.php').' '.$_SERVER['SERVER_PROTOCOL']."\r\n";
    fwrite($socket, $url);
    fwrite($socket, 'Host: '.$_SERVER['HTTP_HOST']."\r\n");
    fwrite($socket, 'User-Agent: Highscores Cron Runtime by Wrzasq'."\r\n");
    fwrite($socket, 'Content-Length: 0'."\r\n");
    fwrite($socket, "\r\n");
	fclose($socket);
	}
include 'statistics.php';
$total = $statistics['census']['male'] + $statistics['census']['female'];
if ($total == 0 || empty($total)){
	@include 'statistics.bak';
	$total = $statistics['census']['male'] + $statistics['census']['female'];
}
if ($total == 0 || empty($total)){
	$error = 'No players found. Highscores are being updated.';
	include('footer.php');
	die();
}
if(isset($_GET['lvl'])){$set='level';$sets = 'lvl';$census = false;}
elseif(isset($_GET['maglvl'])){$set='magic';$sets = 'maglvl';$census = false;}
elseif(isset($_GET['fist'])){$set='fist';$sets = $set;$census = false;}
elseif(isset($_GET['club'])){$set='club';$sets = $set;$census = false;}
elseif(isset($_GET['sword'])){$set='sword';$sets = $set;$census = false;}
elseif(isset($_GET['axe'])){$set='axe';$sets = $set;$census = false;}
elseif(isset($_GET['distance'])){$set='distance';$sets = $set;$census = false;}
elseif(isset($_GET['shielding'])){$set='shield';$sets = 'shielding';$census = false;}
elseif(isset($_GET['fish'])){$set='fishing';$sets = 'fish';$census = false;}
else{$census = true;$set = 'Census';}?>
<div id="content">
<div class="top">.:Highscores:.</div>
<div class="mid">
<table id="choose-skill">
	<tr class='p0'> 
		<td> 
			Choose skill
		</td> 
	</tr>
	<tr class='p1'> 
		<td>
			<ul>
				<li> <a href='?lvl'>Level </a> </li>
				<li> <a href='?maglvl'>Magic level </a> </li>
				<li> <a href='?fist'>Fist fighting </a> </li>
				<li> <a href='?club'>Club fighting </a> </li>
				<li> <a href='?sword'>Sword fighting </a> </li>
				<li> <a href='?axe'>Axe fighting </a> </li>
				<li> <a href='?distance'>Distance fighting </a> </li>
				<li> <a href='?shielding'>Shielding </a> </li>
				<li> <a href='?fish'>Fishing </a> </li>
			</ul>
		</td>
	</tr>
</table>
<?if(!$census){
/* page system*/
$page_config = $cfg['number_per_page'];
$pages = intval(( count($statistics[$set]) - 1) / $page_config);
$site = $_GET['site'] < 0 ? 0 : ($_GET['site'] > $pages ? $pages : $_GET['site']);
for($i = $site * $page_config; $i < ($site + 1) * $page_config; $i++)
{
  if( !empty($statistics[$set][$i]) )
  {$scores[$i + 1] = $statistics[$set][$i];}
}
/* end page system*/
$it = $_GET['site'];
?>
<div id="statictics"><h1>Statistics: <?=$set; ?></h1>
<?
if($it >= 1){
$prev = $it - 1;
$next = $it+1;
echo '<a href="?'.$sets.'&amp;site='.$prev.'">&lt;</a>&nbsp;';
for ($i = 0; $i <= $pages; $i++){
	echo '<a href="?'.$sets.'&amp;site='.$i.'">'.$i.'</a>&nbsp;';
}
	  if($it != $pages){
	  echo '<a href="?'.$sets.'&amp;site='.$next.'">&gt;</a>';
}
}
if(!$it){
for ($i = 0; $i <= $pages; $i++){
	echo '<a href="?'.$sets.'&amp;site='.$i.'">'.$i.'</a>&nbsp;';
}
$next = 1;
echo '<a href="?'.$sets.'&amp;site='.$next.'">&gt;</a>';
}
?>
<table>
<?
echo '<tr class="p0"><td class="p0"><b>Rank</b></td><td class="p0"><b>Name</b></td><td class="p0"><b>Level</b></td></tr>'; }

if(isset($_GET[$sets])):
foreach($scores as $position => $score):
?>
    <tr class="<?= $score['class']; ?>">
	<td><?= $position; ?></td>
    <td><a href="characters.php?char=<?= $score['name']; ?>"><?= $score['name']; ?></a></td>
    <td><?= $score['skill']; ?></td>
	</tr>
<?php endforeach; ?><?php endif; ?>
	<? if (!$census){echo "</table></div>";} ?>
	<? if($census): ?>
	<div  id="census">
	<table>
	<tr><td><b><u><i> Gender </i></u></b></td><td> </td><td> </td></tr>
	<tr>
	<td>Male: </td><td><?= percent($statistics['census']['male'], $total); ?>% </td>
	<td>(<?= $statistics['census']['male']; ?>)
	</td>
	</tr>
	<tr>
	<td>Female: </td><td><?= percent($statistics['census']['female'], $total); ?>% </td>
	<td>(<?= $statistics['census']['female']; ?>)
	</td>
	</tr>
	</table>
	
    <table>
	<tr><td><b><u><i> Vocations </i></u></b></td><td> </td><td> </td></tr>
<?
//edit by nicaw, can handle many vocations now
//damn, this whole script sucks
foreach(array_keys($cfg['voc_normal']) as $id){?>
	<tr>
	<td><?=$cfg['voc_normal'][$id]?> </td><td><?= percent($statistics['census'][$id], $total); ?>% </td>
	<td>(<?= $statistics['census'][$id]; ?>)
	</td>
	</tr>
<?}?>
</table>
</div>
	<? endif; ?>
	<?php

function percent($first, $second)
{
 $it = $first/$second*100;
 
 return round( $it, 2 );
}

echo'<br/><br/>Last refresh:<br/>'.date("jS F Y H:i:s",filemtime('statistics.php'));
?>
</div>
<div class="bot"></div>
</div>
<?include ("footer.php");?>