<?php
class YahooMail {
	var $cookieFile = "yahoo_cookie.tmp";

	function __construct() {
		$this->cookieFile = dirname(__FILE__) . "/yahoo_cookie.tmp";
	}

	function login($userid, $password) {
		$fp = fopen($this->cookieFile, "wb");
		fclose($fp);
		$reffer = "http://mail.yahoo.com/";
		$loginURL = "http://login.yahoo.com/config/login?logout=1&.done=http://mail.yahoo.com/&.src=ym&.lg=us&.intl=us";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $loginURL);
		curl_setopt($ch, CURLOPT_USERAGENT, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookieFile);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookieFile);
		$result = curl_exec($ch);
		curl_close($ch);
		$loginURL = "http://mail.yahoo.com";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $loginURL);
		curl_setopt($ch, CURLOPT_USERAGENT, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookieFile);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookieFile);
		$loginpage_html = curl_exec($ch);
		curl_close($ch);
		preg_match_all("/name=\".u\" value=\"(.*?)\"/", $loginpage_html, $arr_hash_u);
		preg_match_all("/name=\".challenge\" value=\"(.*?)\"/", $loginpage_html, $arr_hash_challenge);
		$hash_u = $arr_hash_u[1][0];
		$hash_challenge = $arr_hash_challenge[1][0];
		$loginURL = "https://login.yahoo.com/config/login?";
		$postFields = ".tries=1&.src=ym&.md5=&.hash=&.js=&.last=&promo=&.intl=us&.bypass=&.partner=&.u=$hash_u&.v=0&.challenge=$hash_challenge&.yplus=&.emailCode=&pkg=&stepid=&.ev=&hasMsgr=0&.chkP=Y&.done=http%3A%2F%2Fmail.yahoo.com&login=$userid&passwd=$password";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $loginURL);
		curl_setopt($ch, CURLOPT_USERAGENT, false);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_REFERER, $reffer);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookieFile);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookieFile);
		$result = curl_exec($ch);
		curl_close($ch);
		preg_match_all("/replace\(\"(.*?)\"/", $result, $arr_url);
		$welcomeURL = $arr_url[1][0];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $welcomeURL);
		curl_setopt($ch, CURLOPT_USERAGENT, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_REFERER, $reffer);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookieFile);
		curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookieFile);
		$result = curl_exec($ch);
		curl_close($ch);
		unlink($this->cookieFile);
		if ($result) {
			return true;
		} else {
			return false;
		}
	}
}
?>