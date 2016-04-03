<?php 
/*FILE INFO:
new account creation*/
session_start();
include ("config.php");
include ("functions.php");
$ptitle="Account Registration - $cfg[server_name]";
include ("header.php");
?>
<div id="content">
<div class="top">.:Account Registration:.</div>
<div class="mid">
<?php 
if (!empty($_POST['account'])){
$_POST['email']=trim($_POST['email']);
$_POST['account']=trim($_POST['account']);

if ((strtolower($_POST['captcha']) == $_SESSION['RandomText']) || !$cfg['use_captha']){
if (eregi("^[A-Z0-9._%-]+@[A-Z0-9._%-]+\.[A-Z]{2,4}$",$_POST['email']) || empty($_POST['email'])){
$account = new Account($_POST['account']);
if (!$account->exist() && $account->isValid()){
if ($cfg['account_number'] !== 2 or $_SESSION['acc'] == $account->number){
if (ereg('^[A-Za-z0-9@#$%^+=]{5,60}$',$_POST['password']) || !$cfg['md5passwords']){
if ($_POST['password'] == $_POST['confirm']){
if ($_POST['password'] != $account->number){
if (!empty($_SESSION['RandomText']) || !$cfg['use_captha']){

$body = "
Thank you for registering at http://$_SERVER[SERVER_NAME]/

Here's your login information:
Account number: $_POST[account]
Password: $_POST[password]

Note that you must use IP Changer to login to 7.6 server.

Yours sincerely,
GM Nicaw";

echo "<!--";
if (!empty($_POST['email'])){echo mailex($_POST['email'], "OTS Login Details", $body);}
echo "-->\n";

$account->make($_POST['password']);
$account->save();

if (is_dir($cfg['dirvip'])){
	$file = fopen($cfg['dirvip'].$account->number.'.xml', 'w');
	fwrite($file,$cfg['vip_file']);
	fclose($file);
}

echo "<p>You have succesfuly created a new account. You can now <a href=\"account.php\">create characters here</a>.<br/>";
$_POST['password']=''; $_POST['confirm']=''; $_POST['account']=''; $_POST['email']='';

}else{ $error = "Your browser might be rejecting cookies";}
}else{ $error = "Choose a safe password, please."; $_POST['password']=''; $_POST['confirm']='';}
}else{ $error = "Passwords do not match"; $_POST['password']=''; $_POST['confirm']='';}
}else{ $error = "Invalid password"; $_POST['password']=''; $_POST['confirm']='';}
}else{ $error = "WTF? You can't choose account number.";}
}else{ $error = "Invalid account number"; $_POST['account']='';}
}else{ $error = "If you don't want to enter your email just leave it empty"; $_POST['email']='';}
}else{ $error = "Image verification failed";}
}
$_SESSION['RandomText'] = '';

if (!empty($_POST['account']) && $cfg['account_number'] != 2){
	$_SESSION['acc'] = htmlspecialchars($_POST['account']);
}elseif ($cfg['account_number'] >= 1){
	$_SESSION['acc']="-1";
	$tmp = new Account($_SESSION['acc']);
	while ( !$tmp->isValid() || $tmp->exist() ){
		$_SESSION['acc'] = rand(100000,999999);
		$tmp = new Account($_SESSION['acc']);
	}
}else{$_SESSION['acc']='';}
?>
<div id="account-image"></div>
<form action="<?=htmlspecialchars($_SERVER['PHP_SELF'])?>" method="post">
<label for="account">Account Number</label><br/>
<input name="account" id="account" type="<?php 
if ($cfg['account_number'] == 2){echo "hidden";} else {echo "text";}
?>" class="textfield" size="30" maxlength="10" value="<?=$_SESSION['acc']?>"/>
<?php 
if ($cfg['account_number'] == 2){echo $_SESSION['acc'];}
?><br/>
<label for="account">Password</label><br/>
<input name="password" id="password" type="password" class="textfield" size="30" maxlength="60" value="<?=htmlspecialchars(($_POST['password']??''))?>"/><br/>
<label for="confirm">Confirm Password</label><br/>
<input name="confirm" id="confirm" type="password" class="textfield" size="30" maxlength="60" value="<?=htmlspecialchars(($_POST['confirm']??''));?>"/><br/>
<?php  if ($cfg['ask_email']){?>
<label for="email">Email (optional)</label><br/>
<input name="email" id="email" class="textfield" size="30" maxlength="60" value="<?=htmlspecialchars($_POST['email'])?>"/><br/>
<?php }?>
<?php  if ($cfg['use_captha']){?>
<img id="captcha-image" src="doimg.php?<?=time()?>" alt="IMAGE NOT DISPLAYED. CHECK BROWSER SETTINGS !"/><br/>
<label for="captcha">Enter text seen above</label><br/>
<input name="captcha" id="captcha" type="text" class="textfield" size="30" maxlength="10"/><br/>
<?php }?>
<input type="submit" id="submit" value="Submit"/>
</form>
</div>
<div class="bot"></div>
</div>
<?php include ("footer.php");?>