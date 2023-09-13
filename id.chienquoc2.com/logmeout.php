<?php
	session_start();
	session_destroy();
	die(header('location: http://'.$_SERVER["SERVER_NAME"]));
?>