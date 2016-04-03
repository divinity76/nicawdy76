<?php
@session_start();
include ("config.php");
include ("functions.php");
/* Session security */
if (isset($_GET['logout']) || 
((time()-($_SESSION['last_activity']??0))>$cfg['session_timeout'] && $cfg['session_security'] > 0) || 
($_SERVER['REMOTE_ADDR'] != ($_SESSION['ip']??'0') && $cfg['session_security'] > 1)){
	$_SESSION['account']='';
	$_SESSION['access']='';
}
$_SESSION['last_activity']=time();

//login phase
if (!empty($_POST['account'])){
$account = new Account(trim($_POST['account']));
if ($account->isValid()){
if ($account->load()){
if ($account->correctPass($_POST['password'])){

$_SESSION['account']=$_POST['account'];
$_SESSION['email']=$pieces['email']??'';
$_SESSION['ip']=$_SERVER['REMOTE_ADDR'];

}else{$error = "Wrong password or account number";}
}else{$error = "Wrong password or account number";}
}else{$error = "Invalid account number";}
}
$ptitle="Account - $cfg[server_name]";
include ("header.php");
?>
<div id="content">
<div class="top">.:Account:.</div>
<div class="mid">
<?php

//IF NOT LOGED IN
if (empty($_SESSION['account'])){?>
<form action="<?=htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
<label for="account">Account Number</label><br/>
<input id="account" name="account" type="password" class="textfield" maxlength="8" size="10"/><br/>
<label for="password">Password</label><br/>
<input id="password" name="password" type="password" class="textfield" maxlength="30" size="10"/><br/>
<input type="submit" value="LogIn"/>
</form>
<a href="new.php">New Account</a><br/>
	<?php } else {

$account = new Account($_SESSION['account']);
if (!$account->load()){die('Failed loading account');}

//IF CREATING CHARACTER
if (!empty($_POST['name'])){
$_POST['name'] = trim($_POST['name']);
$_POST['name'] = ucfirst($_POST['name']);

if (!$cfg['vocation_choose']){
	$_POST['vocation'] = 0;
}

$newplayer = new Player($_POST['name']);
if (isset($cfg['temple'][$_POST['city']]['x']) && isset($cfg['health'][(int)$_POST['vocation']]) && ($_POST['sex'] ==="0" || $_POST['sex'] ==="1")){
if ($account->getCharCount() < $cfg['maxchars']){
if ($newplayer->isValidName()){
if (!$newplayer->exist()){

if ($cfg['CVSplayers']){

	if (!file_exists($cfg['dirplayer']."players.xml")){
		indexPlayers();
	}
	$lines = file_get_contents($cfg['dirplayer']."players.xml");
	$guid = 1;
	while (!(strpos($lines,'"'.$guid.'"') === false)){
		$guid = mt_rand(1,10000000); //probably the fastest way
	}
	$lines = str_ireplace('<players/>','<players></players>',$lines);
	$lines = str_ireplace("</players>","<player guid=\"$guid\" name=\"$_POST[name]\"/>\r\n</players>",$lines);
	file_put_contents($cfg['dirplayer']."players.xml",$lines);
	
}

	$account->addChar($_POST['name']);
	$account->save();

	$newplayer->make($_POST['vocation'],$_SESSION['account'],$_POST['sex'],$_POST['city']);
	$newplayer->save();
	echo "<p><b>Character created !</b></p>";

}else{$error = "This name is already taken.";}
}else{$error = "Not a valid name";}
}else{echo "<b>You can't have more than $cfg[maxchars] characters on your account</b><br/>";}
}else{$error = "Invalid parameters.";}

//IF CHANGING PASSWORD
}elseif (!empty($_POST['old'])){
if (ereg('^[A-Za-z0-9@#$%^+=]{5,60}$',$_POST['new'])){
if ($_POST['new']==$_POST['new2']){
if ($_POST['new']!==$_SESSION['account']){
if ($account->correctPass($_POST['old'])){

	$account->changePass($_POST['new']);
	$account->save();
	echo "<p><b>Password changed !</b></p>";

}else{$error = "Wrong old password.";}
}else{$error = "Your password matches account number.";}
}else{$error = "Passwords do not match";}
}else{$error = "Invalid password format.";}

//IF DELETING CHARACTER
}elseif (!empty($_REQUEST['delete'])){

//if (ereg("^[A-Za-z0-9 -]+$",$_REQUEST['delete'])){
if (preg_match('/^[a-zA-Z0-9\ \-]+$/i',$_REQUEST['delete'])){
$deadPlayer = new Player($_REQUEST['delete']);
//if (ereg("^[1-9][0-9]{1,20}$",$_REQUEST['account'])){
if(preg_match('/^[1-9][0-9]{1,20}$/',$_REQUEST['delete'])){

if ($deadPlayer->load()){
if ($deadPlayer->data['account'] == $_SESSION['account']){
if ($_REQUEST['account'] == $_SESSION['account']){

	$account->deleteChar($deadPlayer->name);
	$account->save();
	$deadPlayer->deleteChar();
	echo "<p><b>Character deleted !</b></p>";

}else{$error = "You didn't enter right account number.";}
}else{$error = "This is your character?";}
}else{$error = "It doesn't exist.";}
}else{$error = "Account is not valid.";}
}else{$error = "Name is not valid.";}

//IF UPDATING COMMENTS
}elseif (isset($_POST['comment_submit'])){

$comment = trim(htmlspecialchars($_POST['comment']));
if (strlen($comment) <= 255){ //max lenght	

	$account->addComment($comment);
	$account->save();

}else{$error = "Comment too long.";}
}

//Generating character list
$characters='
<h2>Characters</h2>
<table>';
$maxaccess = 0;

if (!empty($account->characters)){
	$i=0;
	foreach ($account->characters as $char){
	$player = new Player($char);
	if ($player->exist()){
		$characters.='<tr class="character-row"><td>'.$char.'</td><td><input type="button" value="View" onclick="self.window.location.href=\'characters.php?char='.$char.'\'"/></td><td><input type="button" value="Delete" onclick="char_delete(\''.str_replace("'","\'",$char).'\')"/></td><td>';
		$player->load();
		if ($player->data['banned'] == '1' && $cfg['unban_allow']){
			$characters.="<b style=\"color: red;\">You are still banned for ".ceil(($cfg['unban_after'] - (time() - $player->data['lastlogin']) + $cfg['rank_refresh'])/3600)." hours.</b>";
		}
		if ($player->data['access'] > $maxaccess && $_SESSION['account'] == $player->data['account']) { $maxaccess = (int)$player->data['access'];}
		$characters.="</td></tr>\n";
	}else{
		//delete record in account.xml if player.xml not found
		$account->deleteChar($char);
		$account->save();
	}
	$i++;
	}
}
$characters.='</table><br/>';
$_SESSION['access']=$maxaccess;
echo '<input type="button" value="LogOut" onclick="self.window.location.href=\'?logout\'"/><br/>';
//admin panel
if ($_SESSION['access'] >= $cfg['admin_access'] && !empty($_SESSION['account'])){
	echo '<a href="admin.php">Admin Panel</a>';
}
?>
<h2>Create New Character</h2>
<form action="<?=htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
<table>
<tr class="row"><td style="width: 150px">Enter a valid name</td><td style="width: 350px">
<input name="name" type="text"  class="textfield" maxlength="20"/>
</td></tr><tr class="row"><td>Sex</td><td>
<select name="sex" class="textfield">
<option value="0">Female</option>
<option value="1" selected="selected">Male</option>
</select>
</td></tr><tr class="row"><td><?php  if ($cfg['vocation_choose']){echo "Vocation";} ?></td><td>
<select name="vocation" class="textfield" <?php  if (!$cfg['vocation_choose']){echo 'style="display: none"';} ?> >
<option value="1" selected="selected">Sorcerer</option>
<option value="2">Druid</option>
<option value="3">Paladin</option>
<option value="4">Knight</option>
</select>
</td></tr><tr class="row"><td>
<?php
$cities=array_keys($cfg['temple']);
if (isset($cities[1])){
	echo 'Residence</td><td><select name="city" class="textfield">'."\r\n";
}else{
	echo '</td><td><select name="city" style="display: none;" class="textfield">'."\r\n";
}
$i = 0;
while (isset($cities[$i])){
echo '<option value="'.$cities[$i].'">'.ucfirst($cities[$i]).'</option>'."\r\n";
$i++;}
?>
</select>
</td></tr><tr class="row"><td style="text-align: center;" colspan="2">
<input type="submit" name="submit" value="Submit"/>
</td></tr></table>
</form><br/>
<h2>Change Password</h2>
<form action="<?=htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
<table>
<tr class="row"><td style="width: 150px">Your old password</td><td style="width: 350px">
<input name="old" type="password" class="textfield" id="old" maxlength="30"/>
</td></tr><tr class="row"><td>New password</td><td>
<input name="new" type="password"  class="textfield" id="new" maxlength="20"/>
</td></tr><tr class="row"><td>Confirm new password</td><td>
<input name="new2" type="password" class="textfield" id="new2" maxlength="30"/>
</td></tr><tr class="row"><td style="text-align: center;" colspan="2">
<input type="submit" name="submit" value="Submit"/>
</td></tr></table>
</form><br/><?php 
echo $characters;
?>
<h2>Comments</h2>
<form action="<?=htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
<textarea name="comment" cols="40" rows="10">
<?=$account->data->comment?>
</textarea><br/>
<input type="submit" name="comment_submit" value="Submit"/>
</form><br/>
<?php }?>
</div>
<div class="bot"></div>
</div>
<?php include ("footer.php");?>