<?php
	include('includes/cipher.class.php');

	$Cipher = new Cipher('chienquoc2.com');
	$array = @json_decode($Cipher->decrypt(file_get_contents("userdb.dat")), true);
	ksort($array);
	die(json_encode($array));
?>