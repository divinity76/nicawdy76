<?php 
/*FILE INFO:
character lookup*/
include ("config.php");
include ("functions.php");
$ptitle="Characters - $cfg[server_name]";
include("header.php");
//you can add extra cities here. they will not be displayed when creating character
$cfg['temple']['edron'] = array ('x'=>1651, 'y'=>519, 'z'=>8);
?>
<div id="content">
<div class="top">.:Character LookUp:.</div>
<div class="mid">
<?php 
if (!empty($_GET['char'])){
	if (1===preg_match('/^[0-9A-Za-z]{2,30}$/i',$_GET['char'])){
		$player = new Player($_GET['char']);
		if ($player->load()){
			echo '<b>Name:</b> '.$player->data['name']."<br/>\n";
			echo '<b>Level:</b> '.$player->data['level']."<br/>\n";
			echo '<b>Magic Level:</b> '.$player->data[maglevel]."<br/>\n";
			$voc = (int) $player->data['voc'];

			if ($player->data['promoted'] == 1){
				echo '<b>Vocation:</b> '.$cfg[voc_promoted][$voc]."<br/>\n";
			}else{
				echo '<b>Vocation:</b> '.$cfg[voc_normal][$voc]."<br/>\n";
			}

			$gender = Array('Female','Male');
			$sex = (int) $player->data['sex'];
			echo "<b>Gender:</b> $gender[$sex]<br/>\n";
			$cities=array_keys($cfg['temple']);
			if (isset($cities[1])){
				while ($coords = current($cfg['temple'])){
					if ($coords['x'] == $player->data -> temple['x'] &&
						$coords['y'] == $player->data -> temple['y'] &&
						$coords['z'] == $player->data -> temple['z'] ){
							echo "<b>Residence</b>: ".ucfirst(key($cfg['temple']))."<br/>";
							break;
					}
					next($cfg['temple']);
				}
			}
			if ($player->data['access'] >= count($cfg['positions'])){
				echo "<b>Position: </b> ".end($cfg['positions'])."<br/>";
			}else{ echo "<b>Position: </b> ".$cfg['positions'][ (int) $player->data['access']]."<br/>"; }
			echo "<b>Last Login:</b> ".date("jS F Y H:i:s",(int) $player->data['lastlogin'])."<br/>\n";
			if (isset($player->data->guild)){
				echo '<b>Guild:</b> '.$player->data->guild['rank'].' of <a href="guilds.php?guild='.$player->data->guild['name'].'#'.str_replace(' ','',$player->data->guild['name']).'">'. $player->data->guild['name'].'</a><br/>'."\n";
				}
			if ($player->data['banned'] == 1){
				echo "<b style=\"color: red\">Character is banned</b><br/>";
			}
			echo "<br/>";
			//skils
			$sn = Array('Fist Fighting', 'Club Fighting', 'Sword Fighting', 'Axe Fighting', 'Distance Fighting', 'Shielding', 'Fishing');
			for ($i=0; $i < count($sn); $i++){
				echo "<b>$sn[$i]:</b> ".$player->data->skills->skill[$i]['level']."<br/>\n";
			}
			echo "<br/>\n";
			$account = new Account($player->data['account']);
			$account->load();
			if (strlen($account->data->comment)>0){
				echo "<b>Comments</b><br/><div style=\"overflow:hidden\"><pre>".$account->data->comment."</pre></div><br/>\n";
			}
			echo "<b>Deaths</b><br/>\n";

			//deaths
			$i = 0;
			while(isset($player->data -> deaths -> death[$i])){
				$time = (int) $player->data -> deaths -> death[$i]['time'];
				$name = (string) $player->data -> deaths -> death[$i]['name'];
				$level = (int) $player->data -> deaths -> death[$i]['level'];
				if (file_exists($cfg['dirplayer'].$name.".xml")){
					$name= "<a href=\"characters.php?char=$name\">$name</a>";
				}
				if ($time !== 0) {echo "<i>".date("Y.m.d H:i:s",$time)."</i> ";}
				echo "Killed at level $level by $name<br/>\n";
				$i++;
			}		
		}else{$error = "Player does not exist";}
	}else{$error = "Name not valid";}
}
?>
<form method="get" action="<?=htmlspecialchars($_SERVER['PHP_SELF'])?>"> 
<input type="text" name="char"/> 
<input type="submit" value="Search"/> 
</form>
</div>
<div class="bot"></div>
</div>
<?php include ("footer.php");?>