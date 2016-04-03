<?php
/*FILE INFO:
loading home.txt file*/
include ("config.php");
include ("functions.php");
$ptitle="Home - $cfg[server_name]";
include ("header.php");
?>
<div id="content">
<div class="top">.:Home:.</div>
<div class="mid">
<pre>
<?php

echo htmlentities(file_get_contents("home.txt"));
?>
</pre>
</div>
<div class="bot"></div>
</div>
<?phpinclude ("footer.php");?>