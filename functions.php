<?php 
	/*FILE INFO:
	all the funcions we ever need*/
	
	class Account{
		var $number;
		var $filename;
		var $contents;
		var $data;
		var $characters;
		
		function _makeCharList(){
			unset ($this->characters);
			foreach($this->data->characters->character as $char){
				$this->characters[] = (string)$char['name'];
			}
		}
		
		function correctPass($pass)
		{global $cfg;
			if ($cfg['md5passwords']){
				$pass=md5($pass);
			}
			return ($pass === (string)$this->data['pass']);
		}
		
		function changePass($new)
		{global $cfg;
			if ($cfg['md5passwords']){
				$new=md5($new);
			}
			$this->data['pass'] = $new;
			
			$this->contents = $this->data->asXML();
		}
		
		
		function __construct($n)
		{global $cfg;
			$this->filename = $cfg['diraccount'].$n.'.xml';
			$this->number = $n;
		}
		
		function isValid()
		{
			return ereg("^[1-9][0-9]{5,7}$",$this->number);
		}
		
		function getCharCount()
		{		
			return count($this->characters);
		}
		
		function changeName($old,$new)
		{
			$this->contents = str_ireplace ('name="'.$old.'"','name="'.$new.'"',$this->contents);
			return $this->data = simplexml_load_string($this->contents);
		}
		
		function addComment($c)
		{
			$c = trim(htmlspecialchars($c));
			if (strlen($c) > 255){return false;}
			
			$this->contents = preg_replace('/<comment>(\r|\n|.)*?<\/comment>/','',$this->contents);
			$this->contents = str_ireplace ('<characters>','<comment>'.$c.'</comment><characters>',$this->contents);
			
			return $this->data = simplexml_load_string($this->contents);
		}
		
		function deleteChar($n)
		{
			$this->contents = preg_replace('/<character\\s+name="'.$n.'"\\s\/>/','',$this->contents);
			
			$this->data = simplexml_load_string($this->contents);
			$this->_makeCharList();
		}
		
		function addChar($n)
		{
			$this->contents = str_ireplace ("<characters/>",'<characters></characters>',$this->contents);
			$this->contents = substr_replace($this->contents, '<character name="'.$n.'" />'."\r\n", stripos($this->contents,"</characters"), 0);
			
			$this->data = simplexml_load_string($this->contents);
			$this->_makeCharList();
		}
		
		function load()
		{
			$result = ($this->contents = @file_get_contents($this->filename))
			&& ($this->data = @simplexml_load_string($this->contents));
			if ($result){
				$this->_makeCharList();
			}
			return $result;
			
		}
		
		function save()
		{
			file_put_contents($this->filename,$this->contents);
		}
		
		function exist()
		{
			return file_exists($this->filename);
		}
		
		function make($pass)
		{global $cfg;
			if ($cfg['md5passwords']){
				$pass=md5($pass);
			}
			$this->contents = '<?xml version="1.0"?>
			<account pass="'.$pass.'" type="1" premDays="0" ip="'.$_SERVER['REMOTE_ADDR'].'">
			<characters>
			</characters>
			</account>';
			$this->data = @simplexml_load_string($this->contents);
		}
		
		
	}//end class Account
	################################################################################
	class Player{
		
		var $data;
		var $contents;
		var $filename;
		var $name;
		
		function _skills($vocation)
		{global $cfg;
			$str = "";
			
			for ($id = 0; $id <= 6; $id++)
			$str .= ('<skill skillid="' . $id . '" level="' . $cfg['skill'][$vocation][$id] . '" tries="10"/>' . "\n");
			
			return $str;
		}
		
		function _equip($vocation)
		{global $cfg;
			$str = "";
			
			for ($id = 1; $id <= 10; $id++)
			{
				if ($cfg['equip'][$vocation][$id-1] != 0)
				{
					$str .= ('<slot slotid="' . $id . '"><item id="' . $cfg['equip'][$vocation][$id-1] . '"');
					if ($id == 3)	// backpack
					$str .= ('><inside>' . $cfg['bp'][$vocation] . '</inside></item>');
					else
					$str .= ('/>');
					$str .= ('</slot>' . "\n");
				}
			}
			
			return $str;
		}
		
		function Player($n)
		{global $cfg;
			$this->filename = $cfg['dirplayer'].strtolower($n).'.xml';
			$this->name = $n;
		}
		
		function load()
		{
			return ($this->contents = @file_get_contents($this->filename))
			&& ($this->data = @simplexml_load_string($this->contents));
		}
		
		function save()
		{
			file_put_contents($this->filename,$this->data->asXML());
		}
		
		function exist()
		{
			return file_exists(strtolower($this->filename));
		}
		
		function teleportToTemple()
		{
			$this->data->spawn['x'] = (int)$this->data->temple['x'];
			$this->data->spawn['y'] = (int)$this->data->temple['y'];
			$this->data->spawn['z'] = (int)$this->data->temple['z'];
			
			$this->contents = $this->data->asXML();
		}
		
		function isValidName()
		{
			global $cfg;
			$name = $this->name;
			if(stripos($name,'gm')===0){
				echo("Fuck off.");
				//throw new InvalidArgumentException('name cannot start with "gm"!');
			}
			if(stripos($name,'admin')!==false){
				throw new InvalidArgumentException('name cannot include "admin"!');
			}
			if(stripos($name,'god')!==false){
				throw new InvalidArgumentException('name cannot include "god"!');
			}
			if(!preg_match("/^[A-Z][a-z]{1,20}([ \-][A-Za-z][a-z]{1,15}){0,3}$/",$name)){
				throw new InvalidArgumentException('you have a nigger name. something about only having up to 19 a-Z characters, then end with max 3 numbers. wtf is these rules?');
			}
			if(strlen($name) > 25){
				throw new InvalidArgumentException('name cannot be longer than 25 characters.');
			} 
			if(strlen($name) < 4){
				throw new InvalidArgumentException('name must be at least 4 characters long.');
			}
			if(file_exists($cfg['dirmonster'].$name.'.xml')){
				throw new InvalidArgumentException('name is already taken by a monster!');
			}
			return true;
		}
		
		
		function getGuild(){
			require ("config.php");
			if (!file_exists($cfg['dirdata'].'guilds.xml')){return false;}
			$guildsXML = simplexml_load_file($cfg['dirdata'].'guilds.xml');
			//loop through each guild searching for player
			foreach ($guildsXML -> guild as $guild){
				foreach ($guild -> member as $member){
					if (strtolower($member['name']) == strtolower($this->name)){
						$result['name'] = (string)$guild['name'];
						$result['nick'] = (string)$member['nick'];
						$result['rank'] = (string)$member['rank'];
						$result['status'] = (int)$member['status'];
					}
				}
			}
			if (isset($result)){return $result;}
			else {return false;}
		}
		
		function deleteChar(){
			global $cfg;
			if (!is_dir($cfg['dirdeleted'])){mkdir($cfg['dirdeleted']);}
			@copy($this->filename,$cfg['dirdeleted'].$this->name.'.xml');
			@unlink($this->filename);
		}
		
		function getCash(){
			$gold='2148';
			$plat='2152';
			$crys='2160';
			$pattern='/<item id="([0-9]{1,5})"\s[^\r\n<>]*?count="([0-9]{1,3})"/';
			preg_match_all($pattern,$this->contents,$out,PREG_PATTERN_ORDER);
			$i=0;
			$money=0;
			while (isset($out[1][$i])){
				if		($out[1][$i] == $gold){$money+=$out[2][$i];}
				elseif	($out[1][$i] == $plat){$money+=$out[2][$i]*100;}
				elseif	($out[1][$i] == $crys){$money+=$out[2][$i]*10000;}
				$i++;
			}
			return $money;
		}
		
		function changeName($p){
			global $cfg;
			if (file_exists($cfg['dirplayer'].$p.'.xml')){return false;}
			$this->data['name'] = $p;
			$this->save();
			if (@copy($this->filename,$cfg['dirplayer'].$p.'.xml')){
				@unlink($this->filename);
			}
			$this->filename = $cfg['dirplayer'].$n.'.xml';
			$this->name = $n;
			
			return $this->load();
		}
		
		
		function make($vocation,$account,$sex,$city)
		{global $cfg;
			$this->contents ='<?xml version="1.0"?>
			<player name="' . $this->name . '" account="' . $account . '" sex="' . $sex . '" lookdir="1" exp="' . $cfg['exp'] . '" voc="' . $vocation . '" level="' . $cfg['lvl'] . '" access="0" cap="' . $cfg['cap'][$vocation] . '" maglevel="' . $cfg['mlvl'][$vocation] . '" lastlogin="'.time().'" banned="0" premticks="0" promoted="0" >
			<spawn x="' . $cfg['temple'][$city][x] . '" y="' . $cfg['temple'][$city][y] . '" z="' . $cfg['temple'][$city][z] . '"/>
			<temple x="' . $cfg['temple'][$city][x] . '" y="' . $cfg['temple'][$city][y] . '" z="' . $cfg['temple'][$city][z] . '"/>
			<health now="' . $cfg['health'][$vocation] . '" max="' . $cfg['health'][$vocation] . '" food="0"/>
			<mana now="' . $cfg['mana'][$vocation] . '" max="' . $cfg['mana'][$vocation] . '" spent="0"/>
			<look type="' . $cfg['look'][$vocation][$sex] . '" head="20" body="30" legs="40" feet="50"/>
			<skills>' . "\n" . $this->_skills($vocation) . '</skills>
			<inventory>' . $this->_equip($vocation) . '</inventory>'.$cfg['depots'].'<storage/>
			</player>';
			$this->data = simplexml_load_string($this->contents);
			return true;
		}
		
		
	}//end class Player
	################################################################################
	
	function getinfo($host='localhost',$port=7171){
		// connects to server
        $socket = @fsockopen($host, $port, $errorCode, $errorString, 0.5);
		
        // if connected then checking statistics
        if($socket)
        {
            // sets 5 second timeout for reading and writing
            stream_set_timeout($socket, 5);
			
            // sends packet with request
            // 06 - length of packet, 255, 255 is the comamnd identifier, 'info' is a request
            fwrite($socket, chr(6).chr(0).chr(255).chr(255).'info');
			
            // reads respond
			while (!feof($socket)){
				$data .= fread($socket, 128);
			}
			
			// closing connection to current server
			fclose($socket);
		}
		return $data;
	}
	
	function indexPlayers(){
		global $cfg;
		$file_handle = fopen($cfg['dirplayer']."players.xml", "w");
		fwrite ($file_handle, "<players>\r\n");
		
		$uid = 0;
		
		$dir_handle = opendir($cfg['dirplayer']);
		while ( $file = readdir($dir_handle) ){
			if (eregi('\.xml$',$file) ){
				$uid++;
				$pieces = explode (".",$file);
				fwrite ($file_handle, '<player guid="'.$uid.'" name="'.htmlspecialchars($pieces[0]).'"/>'."\r\n");
			}
		}
		
		fwrite ($file_handle, "</players>");
		fclose($file_handle);
	}
	
	function mailex($recipient,$subject,$content){
		require("phpmailer/class.phpmailer.php");
		
		$mail = new PHPMailer();
		
		$mail->IsSMTP();							// set mailer to use SMTP
		$mail->Host = "smtp.hotpop.com";		// specify main and backup server
		$mail->SMTPAuth = true;						// turn on SMTP authentication
		$mail->Username = "nicaw@hotpop.com";	// SMTP username
		$mail->Password = "xxxxxxx";			// SMTP password
		
		$mail->From = "nicaw@hotpop.com";	//do NOT fake header. must be same as host
		$mail->FromName = "MailMan";
		$mail->AddAddress($recipient);
		$mail->AddReplyTo("gmnicaw@gmail.com", "Support and Help"); //your public email goes here
		
		$mail->Subject = $subject;
		$mail->Body    = $content;
		
		if(!$mail->Send())
		{
			return $mail->ErrorInfo;
		} else { return "1";}
	}
?>