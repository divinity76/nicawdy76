<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
 <meta http-equiv="content-type" content="text/html;charset=utf-8" />
 <meta http-equiv="imagetoolbar" content="no" />
 <title>MD5 generator</title>
</head>
<body>
<form action="#" method="POST"><p>
<textarea name="string" rows="5" cols="30"><?=$_POST['string']?></textarea>
</p><p>
<input type="submit" name="submit"/>
</p>
</form>
<?
if (!empty($_POST['string'])) {
	echo "<p>\n".md5($_POST['string'])."\n</p>\n";}
?>
</body>
</html>