<?php
	ini_set('max_execution_time', 300);

	require_once("security.php");

	define('SAVE_EXTENSION', '.dat');
	define('DATA_PATH', 'E:/Server 1/current');

	$result = array();

	for ($i = 0; $i < 20000; $i++) {
		if ($i > 0) $file = sprintf("%s/%s", DATA_PATH . '/test_db', 'user_check' . ($i + 1) . SAVE_EXTENSION);
		else $file = sprintf("%s/%s", DATA_PATH . '/test_db', 'user_check' . SAVE_EXTENSION);
		if (!file_exists($file)) break;
		$result = array_merge($result, explode('","', str_between(rawurldecode(file_get_contents($file)), 'PlayerName ({"', '",})')));
	}

	if (!empty($result)) {
		$result = utf8ize($result);
	}

	if (isset($_GET['add'])) {
		if (strlen($_GET['name']) < 3) die('0');
		if (in_array($_GET['name'], $result)) die('-1');
		for ($i = 0; $i < 20000; $i++) {
			if ($i > 0) $file = sprintf("%s/%s", DATA_PATH . '/test_db', 'user_check' . ($i + 1) . SAVE_EXTENSION);
			else $file = sprintf("%s/%s", DATA_PATH . '/test_db', 'user_check' . SAVE_EXTENSION);
			if (!file_exists($file)) break;
			$array = explode('","', str_between(file_get_contents($file), 'PlayerName ({"', '",})'));
			if (count($array) >= 100000) continue;
			$array[] = rawurlencode($_GET['name']);
			file_put_contents($file, "#/sys/sys/test_db.c\r\nPlayerName ({\"" . implode('","', $array) . "\",})\r\n");
			die('1');
			break;
		}
	} else if (isset($_GET['legal'])) {
		die('aáàảãạăắằẳẵặâấầẩẫậbcdđeéèẻẽẹêếềểễệfghiíìỉĩịjklmnoóòỏõọôốồổỗộơớờởỡợpqrstuúùủũụưứừửữựvwxyýỳỷỹỵz0123456789 ');
	}

	die(json_encode($result));

	function str_between($str, $start, $end) {
		$str = " $str";
		$i = strpos($str, $start);
		if ($i == 0) return "";
		$i += strlen($start);
		$len = strpos($str, $end, $i) - $i;
		return substr($str, $i, $len);
	}

	function utf8ize($d) {
		if (is_array($d)) {
			foreach ($d as $k => $v) {
				$d[$k] = utf8ize($v);
			}
		} else if (is_string ($d)) {
			return utf8_encode($d);
		}
		return $d;
	}
?>