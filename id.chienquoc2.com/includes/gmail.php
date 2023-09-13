<?php
class Gmail {
	function login($php_userid, $php_password) {
		$clientPost = array(  
			"accountType"   => "HOSTED_OR_GOOGLE",
			"Email"         => $php_userid,
			"Passwd"        => $php_password,
			"service"       => "cp",
		);
		$clientUrl  = "https://www.google.com/accounts/ClientLogin";
		$curl = curl_init($clientUrl);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $clientPost);
		curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$response = curl_exec($curl);
		preg_match("/Auth=([a-z0-9_\-]+)/i", $response, $matches);
		$auth = $matches[1];
		if ($auth) {
			return true;
		} else {
			return false;
		}
	}
}
?>