<? 
/*FILE INFO:
included at the footer of every page*/
if (!empty($error)){?>
<div id="error" onclick="setStyle(this,'display','none')">
<span><?=$error?></span><br/>
</div><?}?>
</div>
<?
//Get current time as we did at start
    $mtime = microtime();
    $mtime = explode(" ",$mtime);
    $mtime = $mtime[1] + $mtime[0];
//Store end time in a variable
    $tend = $mtime;
//Calculate the difference
    $totaltime = ($tend - $tstart);
//Output result
    printf ("<!--Page was generated in %f seconds !-->\n", $totaltime); 
?>
<a style="display: none" href="http://nicaw.net/">Nicaw Home</a>
</body>
</html>