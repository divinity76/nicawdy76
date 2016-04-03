//default adsense values
google_ad_client = "pub-8407977271374637";
google_ad_width = 125;
google_ad_height = 125;
google_ad_format = "125x125_as";
google_ad_type = "text_image";
google_ad_channel ="9679671194";
google_color_border = "CCCCCC";
google_color_bg = "CCCCCC";
google_color_link = "000000";
google_color_text = "333333";
google_color_url = "666666";

function setStyle(obj,style,value){
		getRef(obj).style[style]= value;
}
	
function getRef(obj){
		return (typeof obj == "string") ?
			 document.getElementById(obj) : obj;
}
function char_delete(name){
	if (account = prompt("Enter your account number to confirm deletion.",""))
	{
		self.window.location.href='account.php?delete='+name+'&account='+account;
	}
}

function onLevelChange(){
	lvl = document.form.level.value;
	document.form.exp.value = Math.round(50*(lvl-1)*(lvl*lvl-5*lvl+12)/3);
}

function guildClick(node){
	if (node.nextSibling.nextSibling.style['display'] == 'none')
	{
		setStyle(node.nextSibling.nextSibling, 'display', 'block');
	}else{
		setStyle(node.nextSibling.nextSibling, 'display', 'none');
	}

}
