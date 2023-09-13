<?php
function md5_plus($str) {
	$len = strlen($str);
	$result = $str;
	for ($i = 0; $i < $len; $i++) {
		$result = md5($result);
	}
	return $result;
}

if (!empty($_POST)) {
	$quote = $_POST["quote"];
	$quote_lower = strtolower($_POST["quote"]);
	$array = explode("\r\n", $quote);
	$array_lower = explode("\r\n", $quote_lower);
	$contents = file_get_contents('id_chienquoc2-2015-10-20.sql');
	$contents_lower = strtolower(file_get_contents('id_chienquoc2-2015-10-20.sql'));
	$result = "";
	for ($i = 0, $size = count($array); $i < $size; $i++) {
		$line = explode(":", $array[$i]);
		$line_lower = explode(":", $array_lower[$i]);
		if (strstr($contents, "'$line[0]', '" . md5($line[1]) . "'")) {
			$result .= "UPDATE `account` SET `password` = '" . md5_plus($line[1]) . "' WHERE `account` = '$line[0]' AND `password` = '-1';<br/>";
		}
		else if (strstr($contents_lower, "'$line[0]', '" . md5($line_lower[1]) . "'")) {
			$result .= "UPDATE `account` SET `password` = '" . md5_plus($line_lower[1]) . "' WHERE `account` = '$line[0]' AND `password` = '-1';<br/>";
		}
		else {
			$result .= "/*$line[0] mat khau " . $line[1] . " khong hop le!*/<br/>";
		}
	}
}
?>
<html> 
<head> 
<title>Test</title> 
</head> 
<body>
<?php echo $result; ?>
<form method="post" action="<?php echo $PHP_SELF; ?>"> 
<textarea rows="16" cols="80" name="quote" wrap="physical"><?php echo $quote; ?></textarea><br />
<input type="submit" value="submit" name="submit">
</form>