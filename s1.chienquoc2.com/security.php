<?php
	$legal_ip = array(
		"118.70.109.110",
		"146.196.65.160",
	);

	if (in_array($_SERVER['REMOTE_ADDR'], $legal_ip) == false) {
		die("Restricted access\n");
	}
?>
