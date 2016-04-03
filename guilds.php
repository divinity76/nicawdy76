<?
/*FILE INFO:
guild displaying*/
include ("config.php");
include ("functions.php");
$ptitle="Guilds - $cfg[server_name]";
include ("header.php");
?>
<div id="content">
<div class="top">.:Guilds:.</div>
<div class="mid">
<?
$guildsXML = simplexml_load_file($cfg['dirdata']."guilds.xml");
foreach ($guildsXML->guild as $guild){
	if (count($guild) > 3){
		if ($_GET['guild'] == $guild['name']){
			$style = 'block';
		}else{
			$style = 'none';
		}
		echo '<div id="'.htmlspecialchars(str_replace(' ','',$guild['name'])).'"><b style="cursor: pointer" onclick="guildClick(this)">'.$guild['name'].'</b>'."\n<fieldset style=\"font-size: 90%; padding: 10px; display:$style;\"><table>\n";
		echo '<tr><td style="width: 300px"><b>Name</b></td><td style="width: 200px"><b>Rank</b></td></tr>'."\n";
		$i = 4;
		while ($i > 1){
			foreach ($guild->member as $member){
				if ($member['status'] == $i){
					if (strlen($member['nick'])>2)
						{$name= $member['name'].' <i>('.$member['nick'].')</i>';}
						else{$name = $member['name'];}
					echo '<tr><td><a href="characters.php?char='.$member['name'].'">'.$name.'</a></td><td>'.$member['rank'].'</td></tr>'."\n";
				}
			}
			$i--;
		}
		echo "</table></fieldset></div>\n";
	}
}
?>
</div>
<div class="bot"></div>
</div>
<?include ("footer.php");?>