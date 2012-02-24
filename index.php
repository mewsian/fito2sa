<?php
if(array_key_exists('_fito_submit', $_POST))
{
	// we have a submission
	$in_arr = str_split($_POST['fito_string']);
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
			$outstr .= "<br>\n";
		}else{
			// its a label line or comment line
			$templine = "";
			while($in_arr[$i] != "\n" && $i < count($in_arr))
			{
				$templine .= $in_arr[$i];
				$i++;
			}
			// trailing colon means label, else comment
			$templinearr = str_split($templine);
			if($templinearr[count($templinearr)-2] == ":")
				$outstr .= "[b]".$templine."[/b]<br>\n";
			else
				$outstr .= "- [i]".$templine."[/i]<br>\n";
		}
	}
}
?>

<html>

<head>
<title>fito2sa by mewse</title>
</head>

<body bgcolor="#FFFFFF" text="#000000">
<h1>mewse's handy dandy fitocracy to SA converter</h1>
<?php
if(isset($outstr))
{
	echo "<table border=\"1\" cellpadding=\"5\"><td>\n";
	echo $outstr;
	echo "[sub]copied from fitocracy with [url=http://www.doomers.org/fito2sa/]fito2sa[/url][/sub]\n";
	echo "</td></table>\n";
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
