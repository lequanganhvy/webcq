<?php
	require_once("./lib/soap/nusoap.php");
	require_once("security.php");

	define('SAVE_EXTENSION', '.dat');
	define('DATA_PATH', 'E:/Server 1/current');
	define('USER_AUTHENTIC_PATH', 'test_db/user_authentic.dat');
	define('USER_PAY_PATH', 'test_db/user_pay.dat');

	function new_user($domain_name, $ws_username, $ws_password, $id, $passwd) {
		include("config_auth_ws.php");
		if (check_secure($domain_name, $ws_username, $ws_password) == -1) {
			return -1;
		}
		$id = strtolower(str_replace('"', '', $id));
		if (strlen($id) < 3 || strlen($id) > 20) {
			return -1;
		}
		if (!isset($passwd)) {
			return -1;
		}
		if (user_exist($auth_ws["domain_name"], $auth_ws["ws_username"], $auth_ws["ws_password"], $id) == 1) {
			return -1;
		}
		$array = explode("\r\n", file_get_contents(sprintf("%s/%s", DATA_PATH, USER_AUTHENTIC_PATH)));
		$result = "";
		for ($i = 0, $size = sizeof($array); $i < $size; $i++) {
			if ($array[$i][0] == '') continue;
			if (substr($array[$i], 0, 15) == 'UserDatabase ([') {
				$result .= sprintf("%s\"%s\":([\"passwd\":\"%s\",]),%s\r\n", substr($array[$i], 0, -2), $id, md5_plus($passwd), substr($array[1], -2, strlen($array[1])));
			} else {
				$result .= "$array[$i]\r\n";
			}
		}
		$fh = fopen(sprintf("%s/%s", DATA_PATH, USER_AUTHENTIC_PATH), 'w');
		fwrite($fh, $result);
		fclose($fh);
		return 1;
	}

	function user_exist($domain_name, $ws_username, $ws_password, $id) {
		if (check_secure($domain_name, $ws_username, $ws_password) == -1) {
			return -1;
		}
		$id = strtolower(str_replace('"', '', $id));
		if (get_save(sprintf("%s/%s", DATA_PATH, USER_AUTHENTIC_PATH), 'UserDatabase', $id)) {
			return 1;
		}
		return -1;
	}

	function get_passwd($domain_name, $ws_username, $ws_password, $id) {
		if (check_secure($domain_name, $ws_username, $ws_password) == -1) {
			return -1;
		}
		$id = strtolower(str_replace('"', '', $id));
		$array = get_user_save_2($id);
		return $array['passwd'];
	}

	function set_passwd($domain_name, $ws_username, $ws_password, $id, $passwd) {
		if (check_secure($domain_name, $ws_username, $ws_password) == -1) {
			return -1;
		}
		$id = strtolower(str_replace('"', '', $id));
		$logcontent = @file_get_contents("logs/log_change_password.txt");
		$logcontent .= date("Y-m-d H:i:s") . " Account: " . $id . ". Password: " . $passwd . "\n";
		@file_put_contents("logs/log_change_password.txt", $logcontent);
		return set_user_save_2($id, 'passwd', md5_plus($passwd));
	}

	function get_yuanbao($domain_name, $ws_username, $ws_password, $id) {
		if (check_secure($domain_name, $ws_username, $ws_password) == -1) {
			return -1;
		}
		$id = strtolower(str_replace('"', '', $id));
		return intval(str_between(file_get_contents(sprintf("%s/test_db/user_pay/%s.dat", DATA_PATH, $id)), "YuanBao ", "\r\n"));
	}
/*
	function set_yuanbao($domain_name, $ws_username, $ws_password, $id, $yuanbao) {
		include("config_auth_ws.php");
		if (check_secure($domain_name, $ws_username, $ws_password) == -1) {
			return -1;
		}
		$id = strtolower(str_replace('"', '', $id));
		if (user_exist($auth_ws["domain_name"], $auth_ws["ws_username"], $auth_ws["ws_password"], $id) == -1) {
			return -1;
		}
		return set_save(sprintf("%s/%s", DATA_PATH, USER_PAY_PATH), 'SaveDatabase', $id, $yuanbao);
	}
*/
	function set_yuanbao($domain_name, $ws_username, $ws_password, $id, $yuanbao) {
		include("config_auth_ws.php");
		if (check_secure($domain_name, $ws_username, $ws_password) == -1) {
			return -1;
		}
		$id = strtolower(str_replace('"', '', $id));
		if (user_exist($auth_ws["domain_name"], $auth_ws["ws_username"], $auth_ws["ws_password"], $id) == -1) {
			return -1;
		}
		$content = @file_get_contents(sprintf("%s/test_db/user_pay/%s.dat", DATA_PATH, $id));
		if (strstr($content, "YuanBao ")) {
			if (file_put_contents(sprintf("%s/test_db/user_pay/%s.dat", DATA_PATH, $id), str_replace(sprintf("YuanBao %s\r\n", get_yuanbao($auth_ws["domain_name"], $auth_ws["ws_username"], $auth_ws["ws_password"], $id)), sprintf("YuanBao %s\r\n", $yuanbao), $content)) != false) {
				return 1;
			}
		} else {
			if (file_put_contents(sprintf("%s/test_db/user_pay/%s.dat", DATA_PATH, $id), sprintf("YuanBao %s\r\n", $yuanbao), FILE_APPEND) != false) {
				return 1;
			}
		}
		return 0;
	}

	function add_yuanbao($domain_name, $ws_username, $ws_password, $id, $yuanbao) {
		include("config_auth_ws.php");
		if (check_secure($domain_name, $ws_username, $ws_password) == -1) {
			return -1;
		}
		$id = strtolower(str_replace('"', '', $id));
		return set_yuanbao($auth_ws["domain_name"], $auth_ws["ws_username"], $auth_ws["ws_password"], $id, get_yuanbao($auth_ws["domain_name"], $auth_ws["ws_username"], $auth_ws["ws_password"], $id) + $yuanbao);
	}

	function get_premium_point($domain_name, $ws_username, $ws_password, $id) {
		if (check_secure($domain_name, $ws_username, $ws_password) == -1) {
			return -1;
		}
		$id = strtolower(str_replace('"', '', $id));
		return intval(get_save(sprintf("%s/test_db/user_premium.dat", DATA_PATH), 'SaveDatabase', $id));
	}

	function set_premium_point($domain_name, $ws_username, $ws_password, $id, $point) {
		include("config_auth_ws.php");
		if (check_secure($domain_name, $ws_username, $ws_password) == -1) {
			return -1;
		}
		$id = strtolower(str_replace('"', '', $id));
		if (user_exist($auth_ws["domain_name"], $auth_ws["ws_username"], $auth_ws["ws_password"], $id) == -1) {
			return -1;
		}
		return set_save(sprintf("%s/test_db/user_premium.dat", DATA_PATH), 'SaveDatabase', $id, $point);
	}

	function add_premium_point($domain_name, $ws_username, $ws_password, $id, $point) {
		include("config_auth_ws.php");
		if (check_secure($domain_name, $ws_username, $ws_password) == -1) {
			return -1;
		}
		$id = strtolower(str_replace('"', '', $id));
		return set_premium_point($auth_ws["domain_name"], $auth_ws["ws_username"], $auth_ws["ws_password"], $id, get_premium_point($auth_ws["domain_name"], $auth_ws["ws_username"], $auth_ws["ws_password"], $id) + $point);
	}

	function get_event1_point($domain_name, $ws_username, $ws_password, $id) {
		if (check_secure($domain_name, $ws_username, $ws_password) == -1) {
			return -1;
		}
		$id = strtolower(str_replace('"', '', $id));
		return intval(get_save(sprintf("%s/test_db/user_event1.dat", DATA_PATH), 'SaveDatabase', $id));
	}

	function set_event1_point($domain_name, $ws_username, $ws_password, $id, $point) {
		include("config_auth_ws.php");
		if (check_secure($domain_name, $ws_username, $ws_password) == -1) {
			return -1;
		}
		$id = strtolower(str_replace('"', '', $id));
		if (user_exist($auth_ws["domain_name"], $auth_ws["ws_username"], $auth_ws["ws_password"], $id) == -1) {
			return -1;
		}
		return set_save(sprintf("%s/test_db/user_event1.dat", DATA_PATH), 'SaveDatabase', $id, $point);
	}

	function add_event1_point($domain_name, $ws_username, $ws_password, $id, $point) {
		include("config_auth_ws.php");
		if (check_secure($domain_name, $ws_username, $ws_password) == -1) {
			return -1;
		}
		$id = strtolower(str_replace('"', '', $id));
		return set_event1_point($auth_ws["domain_name"], $auth_ws["ws_username"], $auth_ws["ws_password"], $id, get_event1_point($auth_ws["domain_name"], $auth_ws["ws_username"], $auth_ws["ws_password"], $id) + $point);
	}

	function get_save_dbase($db, $mapping) {
		$array = explode("\r\n", file_get_contents($db));
		for ($i = 0, $size = sizeof($array); $i < $size; $i++) {
			if (substr($array[$i], 0, strlen($mapping) + 3) == "$mapping ([") {
				$temp = substr($array[$i], strlen($mapping) + 3, -2);
				break;
			}
		}
		return $temp;
	}

	function get_save($db, $mapping, $key) {
		$db = get_save_dbase($db, $mapping);
		if (str_between($db, "\"$key\":", "\"") == '([') {
			$result = str_between($db, "\"$key\":([", "]),");
			while (substr_count($result, '([') - $i) {
				$result .= str_between($db, $result, ']),').']),';
				$i++;
			}
		} else {
			$result = str_between($db, "\"$key\":", ",");
		}
		return $result;
	}

	function set_save($db, $mapping, $key, $value) {
		$key = str_replace('"', '\"', $key);
		$array = explode("\r\n", file_get_contents($db));
		for ($i = 0, $size = sizeof($array); $i < $size; $i++) {
			if ($array[$i][0] == '') continue;
			if (substr($array[$i], 0, strlen($mapping) + 3) == "$mapping ([") {
				if (is_int($value)) {
					$temp = sprintf("\"%s\":%s,", $key, get_save($db, $mapping, $key));
					if (strstr($array[$i], $temp)) {
						$result .= str_replace($temp, sprintf("\"%s\":%s,", $key, $value), $array[$i])."\r\n";
					} else {
						$result .= sprintf("%s\"%s\":%s,%s\r\n", substr($array[$i], 0, -2), $key, $value, substr($array[$i], -2, strlen($array[$i])));
					}
				} else {
					$temp = sprintf("\"%s\":\"%s\",", $key, get_save($db, $mapping, $key));
					if (strstr($array[$i], $temp)) {
						$result .= str_replace($temp, sprintf("\"%s\":\"%s\",", $key, $value), $array[$i])."\r\n";
					} else {
						$result .= sprintf("%s\"%s\":\"%s\",%s\r\n", substr($array[$i], 0, -2), $key, $value, substr($array[$i], -2, strlen($array[$i])));
					}
				}
			} else {
				$result .= "$array[$i]\r\n";
			}
		}
		if (!$result) return -1;
		$fh = fopen($db, 'w');
		$ret = fwrite($fh, $result);
		fclose($fh);
		return 1;
	}

	function get_user_save_2($id) {
		include("config_auth_ws.php");
		if (user_exist($auth_ws["domain_name"], $auth_ws["ws_username"], $auth_ws["ws_password"], $id) == -1) {
			return null;
		}
		$id = strtolower(str_replace('"', '\"', $id));
		$array = explode(',', get_save(sprintf("%s/%s", DATA_PATH, USER_AUTHENTIC_PATH), 'UserDatabase', $id));
		for ($i = 0, $size = sizeof($array); $i < $size; $i++) {
			if ($array[$i][0] == '') continue;
			$temp = explode(':', $array[$i]);
			$save[substr($temp[0], 1, -1)] = substr($temp[1], 1, -1);
		}
		return $save;
	}

	function set_user_save_2($id, $key, $value) {
		include("config_auth_ws.php");
		if (!isset($key) || !isset($value)) {
			return -1;
		}
		if (user_exist($auth_ws["domain_name"], $auth_ws["ws_username"], $auth_ws["ws_password"], $id) == -1) {
			if ($key == 'passwd') {
				new_user($auth_ws["domain_name"], $auth_ws["ws_username"], $auth_ws["ws_password"], $id, $value);
			} else {
				return -1;
			}
		}
		$id = strtolower(str_replace('"', '\"', $id));
		$array = explode("\r\n", file_get_contents(sprintf("%s/%s", DATA_PATH, USER_AUTHENTIC_PATH)));
		$user_save = get_user_save_2($id);
		foreach ($user_save as $k => $v) {
			if (is_int($v)) {
				$temp .= sprintf("\"%s\":%s,", $k, $user_save[$k]);
			} else {
				$temp .= sprintf("\"%s\":\"%s\",", $k, $user_save[$k]);
			}
		}
		$old_str = sprintf("\"%s\":([%s]),", $id, $temp);
		if (is_int($value)) {
			$new_str = sprintf("\"%s\":([%s]),", $id, str_replace("\"$key\":$user_save[$key]", "\"$key\":$value", $temp));
		} else {
			$new_str = sprintf("\"%s\":([%s]),", $id, str_replace("\"$key\":\"$user_save[$key]\"", "\"$key\":\"$value\"", $temp));
		}
		for ($i = 0, $size = sizeof($array); $i < $size; $i++) {
			if ($array[$i][0] == '') continue;
			if (substr($array[$i], 0, 15) == 'UserDatabase ([') {
				$result .= str_replace($old_str, $new_str, $array[$i])."\r\n";
			} else {
				$result .= "$array[$i]\r\n";
			}
		}
		$fh = fopen(sprintf("%s/%s", DATA_PATH, USER_AUTHENTIC_PATH), 'w');
		fwrite($fh, $result);
		fclose($fh);
		return 1;
	}

	function str_between($str, $start, $end) {
		$str = " $str";
		$i = strpos($str, $start);
		if ($i == 0) return "";
		$i += strlen($start);
		$len = strpos($str, $end, $i) - $i;
		return substr($str, $i, $len);
	}

	function md5_plus($str) {
		$len = strlen($str);
		$result = $str;
		for ($i = 0; $i < $len; $i++) {
			$result = md5($result);
		}
		return $result;
	}

	function check_secure($domain_name, $ws_username, $ws_password) {
		include("config_auth_ws.php");
		if (!isset($domain_name) || $auth_ws["domain_name"] != $domain_name) {
			return -1;
		}
		if (!isset($ws_username) || $auth_ws["ws_username"] != $ws_username) {
			return -1;
		}
		if (!isset($ws_password) || $auth_ws["ws_password"] != $ws_password) {
			return -1;
		}
		return 1;
	}

	$server = new soap_server();

	$server->configureWSDL('ws_database', 'urn:ws_database');

	$server->register('new_user', array('domain_name' => 'xsd:string', 'ws_username' => 'xsd:string', 'ws_password' => 'xsd:string', 'id' => 'xsd:string', 'passwd' => 'xsd:string'), array('return' => 'xsd:integer'), 'urn:ws_database');
	$server->register('user_exist', array('domain_name' => 'xsd:string', 'ws_username' => 'xsd:string', 'ws_password' => 'xsd:string', 'id' => 'xsd:string'), array('return' => 'xsd:integer'), 'urn:ws_database');
	$server->register('get_passwd', array('domain_name' => 'xsd:string', 'ws_username' => 'xsd:string', 'ws_password' => 'xsd:string', 'id' => 'xsd:string'), array('return' => 'xsd:string'), 'urn:ws_database');
	$server->register('set_passwd', array('domain_name' => 'xsd:string', 'ws_username' => 'xsd:string', 'ws_password' => 'xsd:string', 'id' => 'xsd:string', 'passwd' => 'xsd:string'), array('return' => 'xsd:integer'), 'urn:ws_database');
	$server->register('get_yuanbao', array('domain_name' => 'xsd:string', 'ws_username' => 'xsd:string', 'ws_password' => 'xsd:string', 'id' => 'xsd:string'), array('return' => 'xsd:int'), 'urn:ws_database');
	$server->register('set_yuanbao', array('domain_name' => 'xsd:string', 'ws_username' => 'xsd:string', 'ws_password' => 'xsd:string', 'id' => 'xsd:string', 'yuanbao' => 'xsd:int'), array('return' => 'xsd:integer'), 'urn:ws_database');
	$server->register('add_yuanbao', array('domain_name' => 'xsd:string', 'ws_username' => 'xsd:string', 'ws_password' => 'xsd:string', 'id' => 'xsd:string', 'yuanbao' => 'xsd:int'), array('return' => 'xsd:integer'), 'urn:ws_database');
	$server->register('get_premium_point', array('domain_name' => 'xsd:string', 'ws_username' => 'xsd:string', 'ws_password' => 'xsd:string', 'id' => 'xsd:string'), array('return' => 'xsd:int'), 'urn:ws_database');
	$server->register('set_premium_point', array('domain_name' => 'xsd:string', 'ws_username' => 'xsd:string', 'ws_password' => 'xsd:string', 'id' => 'xsd:string', 'point' => 'xsd:int'), array('return' => 'xsd:integer'), 'urn:ws_database');
	$server->register('add_premium_point', array('domain_name' => 'xsd:string', 'ws_username' => 'xsd:string', 'ws_password' => 'xsd:string', 'id' => 'xsd:string', 'point' => 'xsd:int'), array('return' => 'xsd:integer'), 'urn:ws_database');
	$server->register('get_event1_point', array('domain_name' => 'xsd:string', 'ws_username' => 'xsd:string', 'ws_password' => 'xsd:string', 'id' => 'xsd:string'), array('return' => 'xsd:int'), 'urn:ws_database');
	$server->register('set_event1_point', array('domain_name' => 'xsd:string', 'ws_username' => 'xsd:string', 'ws_password' => 'xsd:string', 'id' => 'xsd:string', 'point' => 'xsd:int'), array('return' => 'xsd:integer'), 'urn:ws_database');
	$server->register('add_event1_point', array('domain_name' => 'xsd:string', 'ws_username' => 'xsd:string', 'ws_password' => 'xsd:string', 'id' => 'xsd:string', 'point' => 'xsd:int'), array('return' => 'xsd:integer'), 'urn:ws_database');

	$HTTP_RAW_POST_DATA = isset($HTTP_RAW_POST_DATA) ? $HTTP_RAW_POST_DATA : '';
	$server->service($HTTP_RAW_POST_DATA);
?>
