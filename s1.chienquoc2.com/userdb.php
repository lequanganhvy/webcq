<?php
	date_default_timezone_set('Asia/Saigon');

	require_once("security.php");

	define('SAVE_EXTENSION', '.dat');
	define('DATA_PATH', 'E:/Server 1/current');
	define('USER_DATA_PATH', 'data');

	$id = $_GET['id'];
	$save_path = sprintf("%s/%s/%s/%s/%s/%s", DATA_PATH, USER_DATA_PATH, substr($id, 0, 1), substr($id, 1, 1), substr($id, 2, 1), $id);
	$save_file = sprintf("%s/%s", $save_path, 'list'.SAVE_EXTENSION);

	if ($_GET['mod'] == 'yongqiling') {
		if ($_GET['i'] > 1) {
			$user_file = sprintf("%s/%s", $save_path.'#'.$_GET['i'], 'user'.SAVE_EXTENSION);
		} else {
			$user_file = sprintf("%s/%s", $save_path, 'user'.SAVE_EXTENSION);
		}
		if (file_exists($user_file)) {
			if (get_save($user_file, 'SaveDatabase', 'yongqiling') == 0) {
				set_save($user_file, 'SaveDatabase', 'yongqiling', time()+60*24*60*60);
				die('1');
			} else {
				die('-1');
			}
		}
		die('0');
	}
	else if ($_GET['mod'] == 'add_item') {
		if ($_GET['item'] == '') {
			die('-1');
		}
		if ($_GET['item'] != 'cash' && $_GET['item'] != 'exp') {
			$item_file = sprintf("%s/%s", DATA_PATH, $_GET['item'].'.c');
			if (!file_exists($item_file)) {
				die('-2'); // item not found
			}
		}
		$content = sprintf("%s %s %d %d\n", $_GET['id'], $_GET['item'], $_GET['amount'], $_GET['time']);
		file_put_contents(sprintf("%s/%s/%s", DATA_PATH, 'test_db', 'reward_db'.SAVE_EXTENSION), $content, FILE_APPEND | LOCK_EX);
		if ($_GET['flag'] != '-99') @file_put_contents("logs/log_give_item.txt", date('Y-m-d H:i:s ') . $content, FILE_APPEND | LOCK_EX);
		die('1');
	}
	else if ($_GET['mod'] == 'change_name') {
		if (!file_exists($save_file)) {
			die('null');
		}
		$legal_name = file_get_contents('http://' . $_SERVER['SERVER_NAME'] . '/namedb.php?legal');
		if (isset($_GET['legal_name'])) {
			die($legal_name);
		}
		if (strlen($_GET['name']) < 2 || strlen($_GET['name']) > 12) {
			die('-1');
		}
		$array = array();
		for ($i = 0, $size = mb_strlen($legal_name, 'utf-8'); $i < $size; $i++) $array[] = mb_substr($legal_name, $i, 1, 'utf-8');
		for ($i = 0, $size = mb_strlen($_GET['name'], 'utf-8'); $i < $size; $i++) {
			if (!in_array(mb_strtolower(mb_substr($_GET['name'], $i, 1, 'utf-8'), 'utf-8'), $array)) {
				die('-2');
			}
		}
		$list_contents = file_get_contents($save_file);
		$list_character_contents = str_between($list_contents, '"' . $_GET['i'] . '":([', ']),');
		if ($list_character_contents == '') die('0');
		if ($_GET['i'] > 1) {
			$user_file = sprintf("%s/%s", $save_path.'#'.$_GET['i'], 'user'.SAVE_EXTENSION);
		} else {
			$user_file = sprintf("%s/%s", $save_path, 'user'.SAVE_EXTENSION);
		}
		if (file_exists($user_file)) {
			$result = file_get_contents('http://' . $_SERVER['SERVER_NAME'] . '/namedb.php');
			$array = array();
			if (get_save_var($user_file, 'SubNames') != '') {
				$array = explode('","', str_between(get_save_var($user_file, 'SubNames'), '({"', '",})'));
			}
			if (in_array($_GET['name'], json_decode($result, true)) == true && in_array($_GET['name'], $array) == false && $_GET['name'] != get_save_var($user_file, 'RealName')) {
				die('-3');
			}
			$result = file_get_contents('http://' . $_SERVER['SERVER_NAME'] . '/namedb.php?add&name=' . $_GET['name']);
			if ($result == '1' || in_array($_GET['name'], $array) == true || $_GET['name'] == get_save_var($user_file, 'RealName')) {
				$user_contents = file_get_contents($user_file);
				$name = get_save_var($user_file, 'Name');
				$list_contents = str_replace('"name":"' . $name . '",', '"name":"' . $_GET['name'] . '",', $list_contents);
				if (get_save_var($user_file, 'RealName') == '') {
					$user_contents .= "RealName \"" . $name . "\"\r\n";
				}
				if ($_GET['name'] != get_save_var($user_file, 'RealName')) {
					if (get_save_var($user_file, 'SubNames') != '') {
						$array = explode('","', str_between(get_save_var($user_file, 'SubNames'), '({"', '",})'));
						if (!in_array($_GET['name'], $array)) {
							$array[] = $_GET['name'];
						}
						$string = implode('","', $array);
						$user_contents = str_replace("\r\nSubNames " . get_save_var($user_file, 'SubNames') . "\r\n", "\r\nSubNames ({\"" . $string . "\",})\r\n", $user_contents);
					} else {
						$user_contents .= "SubNames ({\"" . $_GET['name'] . "\",})\r\n";
					}
				}
				$user_contents = str_replace("\r\nName \"" . $name . "\"\r\n", "\r\nName \"" . $_GET['name'] . "\"\r\n", $user_contents);
				file_put_contents($save_file, $list_contents);
				file_put_contents($user_file, $user_contents);
				@file_put_contents("logs/log_change_name.txt", date('Y-m-d H:i:s ') . $name . ' ' . $_GET['name'] . "\n", FILE_APPEND | LOCK_EX);
				die('1');
			}
		}
		die('0');
	}
	else if ($_GET['mod'] == 'change_gender') {
		if (!file_exists($save_file)) {
			die('null');
		}
		$list_contents = file_get_contents($save_file);
		$list_character_contents = str_between($list_contents, '"' . $_GET['i'] . '":([', ']),');
		if ($list_character_contents == '') die('0');
		if ($_GET['i'] > 1) {
			$user_file = sprintf("%s/%s", $save_path.'#'.$_GET['i'], 'user'.SAVE_EXTENSION);
		} else {
			$user_file = sprintf("%s/%s", $save_path, 'user'.SAVE_EXTENSION);
		}
		if (file_exists($user_file)) {
			$user_contents = file_get_contents($user_file);
			$shape = str_between($list_character_contents, '"shape":', ',');
			$hair = get_save_var($user_file, 'Hair');
			$photo = get_save_var($user_file, 'PersonPhoto');
			$user_inventory = explode('","', str_between(get_save_var($user_file, 'Inventory'), '({"', '",})'));
			if (get_save_var($user_file, 'Gender') == 2) {
				$list_contents = str_replace('"shape":' . $shape . ',', '"shape":' . ($shape - 1000) . ',', $list_contents);
				$list_contents = str_replace('"hair":' . $hair . ',', '"hair":' . ($hair - 1000) . ',', $list_contents);
				$list_contents = str_replace('"photo":' . $photo . ',', '"photo":' . ($photo - 1000) . ',', $list_contents);
				$user_contents = str_replace("\r\nHair " . $hair . "\r\n", "\r\nHair " . ($hair - 1000) . "\r\n", $user_contents);
				$user_contents = str_replace("\r\nPersonPhoto " . $photo . "\r\n", "\r\nPersonPhoto " . ($photo - 1000) . "\r\n", $user_contents);
				$user_contents = str_replace("\r\nGender 2\r\n", "\r\nGender 1\r\n", $user_contents);
				foreach ($user_inventory as $value) {
					$array = explode(';', $value);
					if ($array[2] >= 101 && $array[2] <= 110) {
						$array[0] = str_replace('/item/61/', '/item/60/', $array[0]);
						$array[0] = str_replace('/item/66/', '/item/65/', $array[0]);
						if ($array[0] == '/item/test/chirepifeng') $array[0] = '/item/test/wangzhepifeng';
						if ($array[0] == '/item/test/shenglingzhiyichunbai') $array[0] = '/item/test/shenglingzhiyidamhuang';
						$string = implode(';', $array);
						$user_contents = str_replace($value, $string, $user_contents);
					}
				}
			} else {
				$list_contents = str_replace('"shape":' . $shape . ',', '"shape":' . ($shape + 1000) . ',', $list_contents);
				$list_contents = str_replace('"hair":' . $hair . ',', '"hair":' . ($hair + 1000) . ',', $list_contents);
				$list_contents = str_replace('"photo":' . $photo . ',', '"photo":' . ($photo + 1000) . ',', $list_contents);
				$user_contents = str_replace("\r\nHair " . $hair . "\r\n", "\r\nHair " . ($hair + 1000) . "\r\n", $user_contents);
				$user_contents = str_replace("\r\nPersonPhoto " . $photo . "\r\n", "\r\nPersonPhoto " . ($photo + 1000) . "\r\n", $user_contents);
				$user_contents = str_replace("\r\nGender 1\r\n", "\r\nGender 2\r\n", $user_contents);
				foreach ($user_inventory as $value) {
					$array = explode(';', $value);
					if ($array[2] >= 101 && $array[2] <= 110) {
						$array[0] = str_replace('/item/60/', '/item/61/', $array[0]);
						$array[0] = str_replace('/item/65/', '/item/66/', $array[0]);
						if ($array[0] == '/item/test/wangzhepifeng') $array[0] = '/item/test/chirepifeng';
						if ($array[0] == '/item/test/shenglingzhiyidamhuang') $array[0] = '/item/test/shenglingzhiyichunbai';
						$string = implode(';', $array);
						$user_contents = str_replace($value, $string, $user_contents);
					}
				}
			}
			file_put_contents($save_file, $list_contents);
			file_put_contents($user_file, $user_contents);
			die('1');
		}
		die('0');
	}
	else if ($_GET['mod'] == 'fix_stuck') {
		if (!file_exists($save_file)) {
			die('null');
		}
		$list_contents = file_get_contents($save_file);
		$list_character_contents = str_between($list_contents, '"' . $_GET['i'] . '":([', ']),');
		if ($list_character_contents == '') die('0');
		if ($_GET['i'] > 1) {
			$user_file = sprintf("%s/%s", $save_path.'#'.$_GET['i'], 'user'.SAVE_EXTENSION);
			$mail_file = sprintf("%s/%s", $save_path.'#'.$_GET['i'], 'mail'.SAVE_EXTENSION);
		} else {
			$user_file = sprintf("%s/%s", $save_path, 'user'.SAVE_EXTENSION);
			$mail_file = sprintf("%s/%s", $save_path, 'mail'.SAVE_EXTENSION);
		}
		if (file_exists($user_file)) {
			$user_contents = file_get_contents($user_file);
			// Reset hot keys
			$user_contents = str_replace("\"hotkey\":([" . str_between($user_contents, '"hotkey":([', ']),') . "]),", "\"hotkey\":([\"9\":\"61\",\"8\":\"906\",\"10\":\"51\",]),", $user_contents);
			// Reset start place
			if (get_save_var($user_file, 'Level') > 10) {
				$user_contents = str_replace("\r\nStartPlace \"" . get_save_var($user_file, 'StartPlace') . "\"\r\n", "\r\nStartPlace \"80:(" . (292 + rand(-2, 2)) . "," . (184 + rand(-2, 2)) . ")3\"\r\n", $user_contents);
			} else {
				$user_contents = str_replace("\r\nStartPlace \"" . get_save_var($user_file, 'StartPlace') . "\"\r\n", "\r\nStartPlace \"1:(" . (63 + rand(-2, 2)) . "," . (143 + rand(-2, 2)) . ")3\"\r\n", $user_contents);
			}
			// Reset friend list
			$user_contents = str_replace("\r\nFriends " . get_save_var($user_file, 'Friends') . "\r\n", "\r\nFriends ([])\r\n", $user_contents);
			file_put_contents($user_file, $user_contents);
			// Delete mails
			unlink($mail_file);
			die('1');
		}
		die('0');
	}
	if (!file_exists($save_file)) {
		die('null');
	}
	for ($i = 1; $i <= 6; $i++) {
		$list_contents = file_get_contents($save_file);
		$list_character_contents = str_between($list_contents, '"' . $i . '":([', ']),');
		if ($list_character_contents == '') continue;
		if ($i > 1) {
			$user_file = sprintf("%s/%s", $save_path.'#'.$i, 'user'.SAVE_EXTENSION);
		} else {
			$user_file = sprintf("%s/%s", $save_path, 'user'.SAVE_EXTENSION);
		}
		if (file_exists($user_file)) {
			$SubNames = explode('","', str_between(get_save_var($user_file, 'SubNames'), '({"', '",})'));
			$OrgName = str_between(get_save_var($user_file, 'Org'), '(/"', '",');
			$list[$i] = array(
				'Gender' => intval(get_save_var($user_file, 'Gender')),
				'Level' => intval(get_save_var($user_file, 'MaxLevel')),
				'OrgName' => $OrgName,
				'Birthday' => get_save_var($user_file, 'Birthday'),
				'IdNumber' => get_save_var($user_file, 'IdNumber'),
				'Name' => get_save_var($user_file, 'Name'),
				'RealName' => get_save_var($user_file, 'RealName'),
				'SubNames' => $SubNames
			);	
		}
	}
	die(json_encode($list));

	function get_save_var($db, $var) {
		$array = explode("\r\n", file_get_contents($db));
		for ($i = 0, $size = sizeof($array); $i < $size; $i++) {
			if (substr($array[$i], 0, strlen($var) + 1) == "$var ") {
				$temp = substr($array[$i], strlen($var) + 1);
				break;
			}
		}
		if (substr($temp, 0, 1) == '"') {
			$temp = substr($temp, 1, -1);
		}
		return $temp;
	}

	function get_save_dbase($db, $mapping) {
		$array = explode("\r\n", file_get_contents($db));
		for ($i = 0, $size = sizeof($array); $i < $size; $i++) {
			if (substr($array[$i], 0, strlen($mapping) + 3) == "$mapping ([") {
				$temp = substr($array[$i], $mapping + 3, -2);
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

	function str_between($str, $start, $end) {
		$str = " $str";
		$i = strpos($str, $start);
		if ($i == 0) return "";
		$i += strlen($start);
		$len = strpos($str, $end, $i) - $i;
		return substr($str, $i, $len);
	}
?>
