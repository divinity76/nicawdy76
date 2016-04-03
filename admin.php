<?
/*FILE INFO:
admin panel*/

session_start();
include ("config.php");
include ("functions.php");
print_r($_SESSION);
if (isset($_GET['logout']) || (time()-$_SESSION['last_activity'])>$cfg['session_timeout'] ){
	$_SESSION['account']='';
	$_SESSION['access']='';
}
if ($_SESSION['access'] < $cfg['admin_access'] || empty($_SESSION['account'])){die("Access denied.");}
$_SESSION['time']=time();
$ptitle="Admin Panel - $cfg[server_name]";
include ("header.php");
?>
<div id="content">
<div class="top">.:Admin Panel:.</div>
<div class="mid">
<input type="button" value="Back" onclick="self.window.location.href='account.php'"/><br/><br/>
<form method="post" action="<?=htmlspecialchars($_SERVER['PHP_SELF'])?>"> 
<input type="text" name="char"/> 
<input type="submit" value="Search"/> 
</form>
Notice:<br/>
~Character must be offline to edit his settings.<br/>
~There's built in exp calculator. You just need to change level.<br/>
~If autounban is on, you can increase bantime by decreasing lastlogin.<br/>
<?

if (!empty($_POST['char'])){
			$_SESSION['edit-char'] = $_POST['char'];
}
$player = new Player($_SESSION['edit-char']);
if ($player->load()){

$account = new Account($player->data['account']);
if (!$account->load()){die ('Failed loading account');}

if (!empty($_POST['submit'])){

//Log events
$log = fopen("logs/adminlog.txt","a");
fwrite($log,date("jS F Y H:i:s",time()).' '.$_SERVER['REMOTE_ADDR'].' '.$_SESSION['account'].' '.$_SESSION['edit-char']."\n");
foreach($cfg['admin_attrs'] as $key){
		if ((string) $player->data[$key] != (string)$_POST[$key]){
			$before[$key] = (string) $player->data[$key];
			$after[$key] = (string)$_POST[$key];
		}
}
fwrite($log,print_r($before,true));
fwrite($log,print_r($after,true));
fclose($log);

	if ($_POST['name'] != $player->data['name']){
		$tmp = new Player($_POST['name']);
		if (!$tmp->exist()){
			$_SESSION['edit-char'] = $_POST['name'];
			$account->changeName($player->data['name'],$_POST['name']);
			$account->save();
			$player->changeName($_POST['name']);
			}else{
				$error="Character Exists";
				$_POST['name'] = $player->data['name'];
				}
	}

	foreach($cfg['admin_attrs'] as $key){
		$player->data[$key] = (string)$_POST[$key];	}

	if ($_POST['pass'] != $account->data['pass']){
		if ($cfg['md5passwords']){
		$account->data['pass']=md5($_POST['pass']);
		}else{
			$account->data['pass']=$_POST['pass'];
		}
		$account->contents = $account->data->asXML();
		$account->save();
	}
$player->save();
}elseif (!empty($_POST['delete'])){
	$account->deleteChar($player->name);
	$account->save();
	$player->deleteChar();
	$_SESSION['edit-char'] = '';
}elseif (!empty($_POST['teleport'])){
	$player->data->spawn['x'] = (int)$player->data->temple['x'];
	$player->data->spawn['y'] = (int)$player->data->temple['y'];
	$player->data->spawn['z'] = (int)$player->data->temple['z'];
	$player->save();
}

if (empty($_POST['delete'])){

echo '<form action="'.htmlspecialchars($_SERVER['PHP_SELF']).'" method="post" name="form"><input type="submit" name="submit" value="Update Information"/><table>'."\n";
foreach ($cfg['admin_attrs'] as $key){
	echo '<tr><td>'.$key.'</td><td><input name="'.$key.'" value="'.$player->data[$key].'" type="text" class="textfield"';
	if ($key == 'level') echo ' onchange="onLevelChange()" ';
	echo '/></td></tr>'."\n";
}

echo '<tr><td>password</td><td><input name="pass" value="'.$account->data['pass'].'" type="text"  class="textfield" /></td></tr>'."\n";
echo '</table>';
echo '<input type="submit" name="delete" value="Delete Character" onclick="return confirm(\'Are you sure?\')"/>';
echo '<input type="submit" name="teleport" value="Teleport to Temple"/></form>';
}else{
	//Log events
	$log = fopen("logs/adminlog.txt","a");
	fwrite($log,date("jS F Y H:i:s",time()).' '.$_SERVER['REMOTE_ADDR'].' '.$_SESSION['account'].' '.$_SESSION['edit-char']." deleted\n");
	fclose($log);
}}else{$_SESSION['edit-char'] = '';
	if (!empty($_POST['char'])){$error = "Character not found.";}}?>
</div>
<div class="bot"></div>
</div>
<?include ("footer.php");?>