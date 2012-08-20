<?php
if(array_key_exists('_fito_submit', $_POST))
{
	// we have a submission
	$in_arr = str_split($_POST['fito_string']);
	$firstline = true;
	for($i=0; $i<count($in_arr); $i++)
	{
		// ignore spaces at the beginning of a line
		while($in_arr[$i] == " " && $i < count($in_arr)) $i++;

		if($in_arr[$i] >= '0' && $in_arr[$i] <= '9')
		{
			// its a reps line
			$outstr .= "- ";
			while($in_arr[$i] != "\n" && $i < count($in_arr))
			{
				if($in_arr[$i] == '(')
				{
					while($in_arr[$i] != ')' && $i < count($in_arr)) $i++;
					$i++;
				}
				if($i >= count($in_arr))
					break;
				$outstr .= $in_arr[$i];
				$i++;
			}
			$outstr = rtrim($outstr);
			$outstr .= "<br>\n";
			$firstline = false;
		}else{
			// its a label line or comment line
			$templine = "";
			while($in_arr[$i] != "\n" && $i < count($in_arr))
			{
				$templine .= $in_arr[$i];
				$i++;
			}
			$templine = trim($templine);
			// is it a "Tracked" line? also make sure they
			// copied their username so that "Tracked" isn't
			// at position zero
			if(strpos($templine,"Tracked")!==FALSE &&
			   strpos($templine,"Tracked")!==0)
			{
				$username = strtok($templine," ");
				$username = "[url=http://www.fitocracy.com/profile/".$username."]".$username."[/url] ";
				$templine = substr_replace($templine, $username, 0, strpos($templine,"Tracked"));
				$outstr .= $templine."<br>\n";
				$firstline = $false;
			} elseif(substr($templine,-1) == ":") {
				// trailing colon means workout name
				//if(!$firstline)
				//	$outstr .= "<br>\n";
				$outstr .= "[b]".$templine."[/b]<br>\n";
				$firstline = $false;
			} elseif($templine == "") {
				// empty line
				$outstr .= "<br>\n";
				$firstline = $false;
			} else {
				if(substr($templine,0,7)=="http://")
					$templine = "[url=".$templine."]".$templine."[/url]";
				$outstr .= "- [i]".$templine."[/i]<br>\n";
				$firstline = $false;
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
