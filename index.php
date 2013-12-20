<?php

function is_reps_line($in_str)
{
    $tokens = explode(" ",trim($in_str));
    if(intval($tokens[sizeof($tokens)-1]) > 0)
        return true;
    else
        return false;
}

if(array_key_exists('_fito_submit', $_POST))
{
	// we have a submission
	$lines = explode("\n",$_POST['fito_string']);
    $outstr = "";

	for($i=0; $i<sizeof($lines); $i++)
    {
        // check for "tracked a workout" line
        $tracked = strpos($lines[$i],"tracked a workout");
        if($tracked !== false)
        {
            $username = substr($lines[$i],0,$tracked);
            $outstr .= "[url=http://www.fitocracy.com/profile/".$username."]".$username."[/url] ";
            $outstr .= "tracked a workout<br>\n";
            continue;
        }

        $lines[$i] = trim($lines[$i]);

        $tokens = explode(" ",$lines[$i]);
        if(is_reps_line($lines[$i]))
        {
            // reps line probably
            $outstr .= "- ";
            for($j=0; $j<sizeof($tokens)-1; $j++)
                $outstr.=$tokens[$j]." ";
            $outstr .= "<br>\n";
        }else{
            // is it empty?
            if($lines[$i] == "")
                continue;

            // look ahead to next line to see if it's a comment or workout name
            if(is_reps_line($lines[$i+1]))
            {
                // workout name probably
                $outstr .= "[b]".trim($lines[$i])."[/b]<br>\n";
            }else{
                // probably a comment line
                $outstr .= "- [i]".trim($lines[$i])."[/i]<br>\n";
            }
        }
    }
}
?>

<html>

<head>
<title>fito2sa by mewse</title>
</head>

<body bgcolor="#FFFFFF" text="#000000">

<script type="text/javascript" src="ZeroClipboard.js"></script>

<!-- Piwik -->
<script type="text/javascript">
  var _paq = _paq || [];
  _paq.push(["trackPageView"]);
  _paq.push(["enableLinkTracking"]);

  (function() {
    var u=(("https:" == document.location.protocol) ? "https" : "http") + "://doomers.org/piwik/piwik/";
    _paq.push(["setTrackerUrl", u+"piwik.php"]);
    _paq.push(["setSiteId", "2"]);
    var d=document, g=d.createElement("script"), s=d.getElementsByTagName("script")[0]; g.type="text/javascript";
    g.defer=true; g.async=true; g.src=u+"piwik.js"; s.parentNode.insertBefore(g,s);
  })();
</script>
<noscript>
<img src="http://doomers.org/piwik/piwik/piwik.php?idsite=2&amp;rec=1" style="border:0" alt="" />
</noscript>
<!-- End Piwik Code -->

<h1>mewse's handy dandy fitocracy to SA converter</h1>
<?php
if(isset($outstr))
{

	echo "<table border=\"1\" cellpadding=\"5\"><td>\n";
	$outstr .= "[sub][url=http://www.doomers.org/fito2sa/]fito2sa[/url][/sub]<br>\n";
	echo $outstr;
	echo "</td></table>\n";

	$jsoutstr = str_replace("<br>\n","\\n",addslashes($outstr));
?>
	<div id="d_clip_button" style="border:1px solid black; width:200px; padding:5px;"><center>Copy To Clipboard</center></div>
        
	<script language="JavaScript">
		var clip = new ZeroClipboard.Client();
		var jsstr = '<?php echo $jsoutstr; ?>';
		clip.setText( jsstr );
		clip.glue( 'd_clip_button' );
	</script>
<?php
}else{
?>
<p>Copy and paste your fitocracy crap below and it will
strip the points and do some formatting for you</p>
<?php
}
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
<textarea name="fito_string" cols="80" rows="25"></textarea>
<br>
<input type="submit" value="Submit">
<input type="hidden" name="_fito_submit" value="1">
</form>
</body>
<p><font size="-1"><a href="fito2sa.phps">source code here</a></font></p>
</html>
