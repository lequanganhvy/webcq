<?php
header("Content-Type: text/html; charset=utf-8");

require_once("auth.php");

if ($_GET['mod'] == 'phpinfo') {
	die(phpinfo());
}
?>
OK
