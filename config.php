<?php 
/*
Copyright (C) 2006  Nicaw

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

http://www.gnu.org/copyleft/gpl.html
*/
//error_reporting(E_ALL);
init();
//---------------------------- Data Dir ----------------------------------------

/*Set location of data directory
IMPORTANT! Use / to separate folders, put / in the end     */
$cfg['dirdata'] = 'C:/xampp/htdocs/divinity76/data/';

//----------------------------- Website Configuration --------------------------

//look in skins folder to see available skins
$cfg['skin'] = 'dark';

//name displayed in window title
$cfg['server_name'] = 'Shitbiscuit';

/*Encrypt passwords. (true / false)
Works together with md5passwords = "yes" in server config.lua
Strongly recomended, but off by default. After changing this, existing accounts will become inaccessible!*/
$cfg['md5passwords'] = True; 

/*(true / false)
Using captcha will prevent automated sripts from flooding server with accounts. 
Note that GD library must be enabled in php.ini for this to work.
extension_loaded('gd') means "auto"*/
$cfg['use_captha'] = extension_loaded('gd'); 

/*(advanced users only) Asks user's email when registering and attempts to send greeting.
SMTP can be configured function mailex() in functions.php.
Email content can be found in new.php. You must have DNS MX record if sending from local SMTP.
Correct reverse DNS is also recomended.*/
$cfg['ask_email'] = false; 

/* Session security. Set medium if you get kicked out after loging in account.
0 - low		(no fancy stuff)
1 - medium	(users are loged out after being idle for specified time interval)
2 - high	(IPs are checked to prevent session hijacking)*/
$cfg['session_security'] = 2;

//seconds in which user is loged out. default is 10 minutes: 10*60
$cfg['session_timeout'] = 10*60; 

//allowed characters per account
$cfg['maxchars'] = 10; 

/* enable unban feature. some servers ban permanetly for pking. if this enabled players will be unbaned when generating highscores (true / false)*/
$cfg['unban_allow'] = false;

/* time in seconds after which player can be unbaned
  (2 days)     d*hh*mm*ss;  kapish? */
$cfg['unban_after'] = 2*24*60*60;

//Delete old characters?
$cfg['delete_allow'] = false;

//delete players older than (seconds)
$cfg['delete_player'] = 2*30*24*60*60; //(2 months)

//only delete players with level lower than
$cfg['delete_level'] = 20;

/* Predefined account number. 
0 - none (leave blank field), 
1 - offer (put account number, but user can change it), 
2 - force (dont allow user to select his own account number)*/
$cfg['account_number'] = 0;

//Vocation names
$cfg['voc_normal'] = array('None','Sorcerer','Druid','Paladin','Knight','Master Sorcerer','Elder Druid','Royal Paladin','Elite Knight');

//Promoted names
$cfg['voc_promoted'] = array('None','Master Sorcerer','Elder Druid','Royal Paladin','Elite Knight');

//Access level names
$cfg['positions'] = array("Player", "Tutor", "Counselor", "Gamemaster", "God");

//Access level to use admin panel
$cfg['admin_access'] = 10;

//Admin Panel - attributes to load from player file (root tag only)
$cfg['admin_attrs'] = array('name', 'account', 'sex', 'exp', 'voc', 'level', 'access', 'cap', 'maglevel', 'lastlogin', 'premticks', 'promoted', 'banned');

//generate CVS players.xml file (true/false) This is mostly needed for 7.7 servers.
$cfg['CVSplayers'] = False;

//For online status. Do not change, unless you running on diferent port.
$cfg['server_ip'] = "localhost";
$cfg['server_port'] = 7171;

//----------------------------- Highscore Configuration ------------------------

 //seconds in which highscores will be reloaded. default is 3 hours: 3*60*60
$cfg['rank_refresh'] = 3*60*60;

// this access level and above not included in ranks
$cfg['gm_access'] = 2;

//number of ranks to cache
$cfg['highscoreshow'] = 200;

//how many displayer per page
$cfg['number_per_page'] = 30;

//----------------------------- All vocations ----------------------------------
$cfg['lvl'] = 8; 	// initial level (can be float like: 8.3 or 14.7)

//whether to allow users to choose vocations. Set to false if you have Rook system
$cfg['vocation_choose'] = True; 

//Players Temple(s)

$cfg['temple']['Divinity']['x'] = 171;
$cfg['temple']['Divinity']['y'] = 55;
$cfg['temple']['Divinity']['z'] = 7;




// you might want to add GM chars here
$cfg['vip_file'] = '<?xml version="1.0"?>
<vips><vip id="1" name="GM Nicaw"/></vips>';

//Depending on server, you might need to change depot configuration
$cfg['depots'] = '
<depots>
<depot depotid="1"><item id="2590"><inside><item id="2594"/></inside></item></depot>
<depot depotid="2"><item id="2590"><inside><item id="2594"/></inside></item></depot>
<depot depotid="3"><item id="2590"><inside><item id="2594"/></inside></item></depot>
<depot depotid="4"><item id="2590"><inside><item id="2594"/></inside></item></depot>
<depot depotid="5"><item id="2590"><inside><item id="2594"/></inside></item></depot>
<depot depotid="6"><item id="2590"><inside><item id="2594"/></inside></item></depot>
<depot depotid="7"><item id="2590"><inside><item id="2594"/></inside></item></depot>
</depots>
';

// Leave this stuff as it is :P
if (!defined('MALE')) define('MALE', 1);
if (!defined('FEMALE')) define('FEMALE', 0);
if (!defined('NOVOC')) define('NOVOC', 0);
if (!defined('SORCERER')) define('SORCERER', 1);
if (!defined('DRUID')) define('DRUID', 2);
if (!defined('PALADIN')) define('PALADIN', 3);
if (!defined('KNIGHT')) define('KNIGHT', 4);

//-------------------------------- Sorcerer ------------------------------------
// Looks
$cfg['look'][SORCERER][MALE] = '130';
$cfg['look'][SORCERER][FEMALE] = '138';

// HP, mana, magic level
$cfg['health'][SORCERER] = '185';
$cfg['mana'][SORCERER] = '40';
$cfg['mlvl'][SORCERER] = '0';
$cfg['cap'][SORCERER] = '470';

// Skills:               fist,	club,	sword,	axe,	dist,	shld,	fish
$cfg['skill'][SORCERER] = array(10,	15,		15,		15,		15,		15,		15);

// Eq:               helm, amul, bp,  armor, rght, left, legs, boot, ring, ammo
$cfg['equip'][SORCERER] = array(2480, 2172, 2000, 2464, 0, 2530, 2468, 2643, 0, 0);

// Backpack:
$cfg['bp'][SORCERER] = '<item id="2554"/><item id="2120"/><item id="2398"/><item id="2412"/><item id="2388"/><item id="2190"/>';

//--------------------------------- Druid --------------------------------------
// Looks
$cfg['look'][DRUID][MALE] = '130';
$cfg['look'][DRUID][FEMALE] = '138';

// HP, mana, magic level
$cfg['health'][DRUID] = '185';
$cfg['mana'][DRUID] = '40';
$cfg['mlvl'][DRUID] = '0';
$cfg['cap'][DRUID] = '470';

// Skills:               fist,	club,	sword,	axe,	dist,	shld,	fish
$cfg['skill'][DRUID] = array(10,	15,		15,		15,		15,		15,		15);

// Eq:               helm, amul, bp,  armor, rght, left, legs, boot, ring, ammo
$cfg['equip'][DRUID] = array(2480, 2172, 2000, 2464, 0, 2530, 2468, 2643, 0, 0);

// Backpack:
$cfg['bp'][DRUID] = '<item id="2554"/><item id="2120"/><item id="2398"/><item id="2412"/><item id="2388"/><item id="2182"/>';

//-------------------------------- Paladin -------------------------------------
// Looks
$cfg['look'][PALADIN][MALE] = '129';
$cfg['look'][PALADIN][FEMALE] = '137';

// HP, mana, magic level
$cfg['health'][PALADIN] = '185';
$cfg['mana'][PALADIN] = '40';
$cfg['mlvl'][PALADIN] = '0';
$cfg['cap'][PALADIN] = '470';

// Skills:               fist,	club,	sword,	axe,	dist,	shld,	fish
$cfg['skill'][PALADIN] = array(10,	15,		15,		15,		15,		15,		15);

// Eq:               helm, amul, bp,  armor, rght, left, legs, boot, ring, ammo
$cfg['equip'][PALADIN] = array(2480, 2172, 2000, 2464, 0, 2530, 2468, 2643, 0, 0);

// Backpack:
$cfg['bp'][PALADIN] = '<item id="2554"/><item id="2120"/><item id="2398"/><item id="2412"/><item id="2388"/><item id="2389" count="3"/>';

//--------------------------------- Knight -------------------------------------
// Looks
$cfg['look'][KNIGHT][MALE] = '131';
$cfg['look'][KNIGHT][FEMALE] = '139';

// HP, mana, magic level
$cfg['health'][KNIGHT] = '185';
$cfg['mana'][KNIGHT] = '40';
$cfg['mlvl'][KNIGHT] = '0';
$cfg['cap'][KNIGHT] = '470';

// Skills:               fist,	club,	sword,	axe,	dist,	shld,	fish
$cfg['skill'][KNIGHT] = array(10,	20,		20,		20,		15,		20,		15);

// Eq:               helm, amul, bp,  armor, rght, left, legs, boot, ring, ammo
$cfg['equip'][KNIGHT] = array(2480, 2172, 2000, 2464, 0, 2530, 2468, 2643, 0, 0);

// Backpack:
$cfg['bp'][KNIGHT] = '<item id="2554"/><item id="2120"/><item id="2398"/><item id="2412"/><item id="2388"/>';

//-------------------------------- No Vocation ---------------------------------
// Looks
$cfg['look'][NOVOC][MALE] = '130';
$cfg['look'][NOVOC][FEMALE] = '138';

// HP, mana, magic level
$cfg['health'][NOVOC] = '150';
$cfg['mana'][NOVOC] = '0';
$cfg['mlvl'][NOVOC] = '0';
$cfg['cap'][NOVOC] = '400';

// Skills:               fist,	club,	sword,	axe,	dist,	shld,	fish
$cfg['skill'][NOVOC] = array(1,	1,		1,		1,		1,		1,		1);

// Eq:               helm, amul, bp,  armor, rght, left, legs, boot, ring, ammo
$cfg['equip'][NOVOC] = array(0, 0, 3939, 2650, 2382, 0, 0, 0, 0, 2050);

// Backpack:
$cfg['bp'][NOVOC] = '<item id="2674"/>';

//-------------------------- NOOB SENSATIVE -----------------------------------

//relative paths hidden here from newbs. If you are one: DO NOT EDIT
$cfg['diraccount'] = $cfg['dirdata'].'accounts/';
$cfg['dirplayer'] = $cfg['dirdata'].'players/';
$cfg['dirmonster'] = $cfg['dirdata'].'monster/';
$cfg['dirvip'] = $cfg['dirdata'].'vip/';
$cfg['dirhouse'] = $cfg['dirdata'].'houses/';
$cfg['dirdeleted'] = $cfg['dirdata'].'deleted/';

//-------------------------- END OF CONFIGURATION -----------------------------

//checking if directories defined previously really exist
if (!(is_dir($cfg['dirplayer']) && is_dir($cfg['diraccount']))){
	$error = "Warning!<br/>\n Please set correct data directory in config.php";
}

//checking if IP not banned
if (file_exists('banned.txt')){
$banned_ips = file ('banned.txt');
foreach ($banned_ips as $ip){
	if ($ip == $_SERVER['REMOTE_ADDR']){
		die("Sorry, your IP is banned from the website."); 
		//ha ha ha. die die die. I love this function :D you're dead bye :P
	}
}
}

//Calculating correct exp for level
$cfg['exp'] = round(50*($cfg['lvl']-1)*($cfg['lvl']*$cfg['lvl']-5*$cfg['lvl']+12)/3);
$cfg['lvl'] = floor($cfg['lvl']);

//disable magic_quotes_gpc. ty wrzasq
if( get_magic_quotes_gpc() )
{
  $_POST = array_map('stripslashes', $_POST);
  $_GET = array_map('stripslashes', $_GET);
  $_COOKIE = array_map('stripslashes', $_COOKIE);
  $_REQUEST = array_map('stripslashes', $_REQUEST);
}

//Check for correct PHP version
if (!version_compare(phpversion(), "5.1.4", ">=") )
	$error = "You need PHP 5.1.4 or later to run this AAC. Try the latest XAMPP.";

//Check if extensions loaded
if (!extension_loaded('simplexml'))
	$error = "SimpleXML is not enabled in php.ini";







function init()
{
    static $firstrun=true;
    if($firstrun!==true){
    	return;
    }
    $firstrun=false;
    error_reporting(E_ALL);
    set_error_handler("exception_error_handler");
    //	ini_set("log_errors",true);
    //	ini_set("display_errors",true);
    //	ini_set("log_errors_max_len",0);
    //	ini_set("error_prepend_string",'<error>');
    //	ini_set("error_append_string",'</error>'.PHP_EOL);
    //	ini_set("error_log",__DIR__.'/error_log.php');
    assert_options(ASSERT_ACTIVE, 1);
    assert_options(ASSERT_WARNING, 0);
    assert_options(ASSERT_QUIET_EVAL, 1);
    assert_options(ASSERT_CALLBACK, 'assert_handler');
}
function exception_error_handler($errno, $errstr, $errfile, $errline)
{
    if (!(error_reporting() & $errno)) {
        // This error code is not included in error_reporting
        return;
    }
    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
}
function assert_handler($file, $line, $code, $desc = null)
{
    $errstr='Assertion failed at '.$file.':'.$line.' '.$desc.' code: '.$code;
    throw new ErrorException($errstr,0,1,$file,$line);
}
