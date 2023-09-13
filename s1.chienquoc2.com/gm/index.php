<?php
	header("Content-Type: text/html; charset=utf-8");

	error_reporting(0);

	require_once("../auth.php");

	if (isset($_GET[top_exp])) {
		$Exp_players = str_between(file_get_contents('E:\Server 1\current\data\mingren.dat'), 'Exp_players ([', "])\r\n");
		$Exp_players = str_replace('"size":10,', '', $Exp_players);
		$Exp_players = explode(']),', $Exp_players);
		for ($i = 0, $size = sizeof($Exp_players); $i < $size; $i++) {
			if (!strstr($Exp_players[$i], '"name":')) continue;
			$temp = explode(':([', $Exp_players[$i]);
			$line[sizeof($line)] = $temp[1];
		}
		unset($Exp_players);
		for ($i = 0, $size = sizeof($line); $i < $size; $i++) {
			$pos = str_between($line[$i], '"pos":', ',');
			$id = str_between($line[$i], '"id":', ',');
			$level = str_between($line[$i], '"level":', ',');
			$name = str_replace('"', '', str_between($line[$i], '"name":', ','));
			$family = str_replace('"', '', str_between($line[$i], '"family":', ','));
			$sum_exp = str_between($line[$i], '"sum_exp":', ',');
			$skills_level = str_between($line[$i], '"skills_level":', ',');
			$account = str_between($line[$i], '"account":', ',');
			$Exp_players[$pos] = array('sum_exp' => $sum_exp, 'id' => $id, 'level' => $level, 'family' => $family, 'name' => $name, 'skills_level' => $skills_level, 'account' => $account);
		}
		for ($i = 1, $size = sizeof($Exp_players) + 1; $i < $size; $i++) {
			echo "$i {<br><div style=\"padding-left: 30px;\">[id] => ".$Exp_players[$i][id].",<br>[name] => ".$Exp_players[$i][name].",<br>[level] => ".$Exp_players[$i][level].",<br>[family] => ".$Exp_players[$i][family].",<br>[sum_exp] => ".$Exp_players[$i][sum_exp].",<br>[skills_level] => ".$Exp_players[$i][skills_level].",<br></div>}<br><br>";
		}
	} else if (isset($_GET[top_cash])) {
		$Cash_players = str_between(file_get_contents('E:\Server 1\current\data\mingren.dat'), 'Cash_players ([', "])\r\n");
		$Cash_players = str_replace('"size":10,', '', $Cash_players);
		$Cash_players = str_replace('"', '', $Cash_players);
		$Cash_players = explode(']),', $Cash_players);
		for ($i = 0, $size = sizeof($Cash_players); $i < $size; $i++) {
			if (!strstr($Cash_players[$i], 'name:')) continue;
			$temp = explode(':([', $Cash_players[$i]);
			$line[sizeof($line)] = $temp[1];
		}
		unset($Cash_players);
		for ($i = 0, $size = sizeof($line); $i < $size; $i++) {
			$pos = str_between($line[$i], 'pos:', ',');
			$id = str_between($line[$i], 'id:', ',');
			$level = str_between($line[$i], 'level:', ',');
			$name = str_between($line[$i], 'name:', ',');
			$family = str_between($line[$i], 'family:', ',');
			$cash = str_between($line[$i], 'cash:', ',');
			$account = str_between($line[$i], 'account:', ',');
			$Cash_players[$pos] = array('id' => $id, 'level' => $level, 'family' => $family, 'name' => $name, 'cash' => $cash, 'account' => $account);
		}
		for ($i = 1, $size = sizeof($Cash_players) + 1; $i < $size; $i++) {
			echo "$i {<br><div style=\"padding-left: 30px;\">[id] => ".$Cash_players[$i][id].",<br>[name] => ".$Cash_players[$i][name].",<br>[level] => ".$Cash_players[$i][level].",<br>[family] => ".$Cash_players[$i][family].",<br>[cash] => ".$Cash_players[$i][cash].",<br>[account] => ".$Cash_players[$i][account].",<br></div>}<br><br>";
		}
	} else if (isset($_GET[top_gongde])) {
		$Gongde_players = str_between(file_get_contents('E:\Server 1\current\data\mingren.dat'), 'Gongde_players ([', "])\r\n");
		$Gongde_players = str_replace('"size":10,', '', $Gongde_players);
		$Gongde_players = str_replace('"', '', $Gongde_players);
		$Gongde_players = explode(']),', $Gongde_players);
		for ($i = 0, $size = sizeof($Gongde_players); $i < $size; $i++) {
			if (!strstr($Gongde_players[$i], 'name:')) continue;
			$temp = explode(':([', $Gongde_players[$i]);
			$line[sizeof($line)] = $temp[1];
		}
		unset($Gongde_players);
		for ($i = 0, $size = sizeof($line); $i < $size; $i++) {
			$pos = str_between($line[$i], 'pos:', ',');
			$id = str_between($line[$i], 'id:', ',');
			$level = str_between($line[$i], 'level:', ',');
			$name = str_between($line[$i], 'name:', ',');
			$family = str_between($line[$i], 'family:', ',');
			$gongde = str_between($line[$i], 'gongde:', ',');
			$account = str_between($line[$i], 'account:', ',');
			$Gongde_players[$pos] = array('level' => $level, 'id' => $id, 'family' => $family, 'name' => $name, 'gongde' => $gongde, 'account' => $account);
		}
		for ($i = 1, $size = sizeof($Gongde_players) + 1; $i < $size; $i++) {
			echo "$i {<br><div style=\"padding-left: 30px;\">[id] => ".$Gongde_players[$i][id].",<br>[name] => ".$Gongde_players[$i][name].",<br>[level] => ".$Gongde_players[$i][level].",<br>[family] => ".$Gongde_players[$i][family].",<br>[gongde] => ".$Gongde_players[$i][gongde].",<br></div>}<br><br>";
		}
	} else if (isset($_GET[who])) {
		$iChannelSize = 4;
		$iMaxUser = 60;
		$iCurUser = 0;
		if ($_GET['do'] == 'refresh') {
			$default = "UserOnline ({})\r\n";
			for ($i = 1; $i <= $iChannelSize; $i++) {
				$fh = fopen('E:/Server 1/current/test_db/sys_channel/'.$i.'/online.dat', 'w');
				fwrite($fh, $default);
				fclose($fh);
			}
			sleep(2);
		}
		for ($i = 1; $i <= $iChannelSize; $i++) {
			if (file_exists('E:/Server 1/current/test_db/sys_channel/'.$i.'/online.dat')) {
				$who[$i] = explode(',', str_between(file_get_contents('E:/Server 1/current/test_db/sys_channel/'.$i.'/online.dat'), 'UserOnline ({', ',})'));
				$size[$i] = sizeof($who[$i]);
				if ($who[$i][0] == '') $size[$i] -= 1;
			} else {
				$status[$i] = -1;
			}
		}
		for ($i = 1; $i <= $iChannelSize; $i++) {
			$iCurUser += $size[$i];
			if ($status[$i] == -1) {
				echo 'Channel '.$i.' (Offline)<br><br>';
			} else {
				echo 'Channel '.$i.' ('.$size[$i].'/'.$iMaxUser.') {<div style="padding-left: 30px;">';
				for ($n = 0; $n < $size[$i]; $n++) {
					echo '['.($n + 1).'] => '.$who[$i][$n].',<br>';
				}
				echo '</div>}<br><br>';
			}
		}
		$iCurUserRate = intval($iCurUser * 100 / ($iMaxUser * $iChannelSize));
		if ($iCurUser > 0 && $iCurUserRate < 1) $iCurUserRate = 1;
		echo 'Members: '.$iCurUser.'/'.($iMaxUser * $iChannelSize).' ('.$iCurUserRate.'%)';
	} else if (isset($_GET[shout])) {
		$iChannelSize = 4;
		$arg = $_POST[arg];
		if (isset($_POST[arg])) {
			if (strlen($arg) >= 1) {
				for ($i = 1; $i <= $iChannelSize; $i++) {
					$result = file_get_contents('E:\Server 1\current\test_db\cache\chat\chat2_'.$i.'.dat');
					$result = str_between($result, 'SaveDbase3 ({', '})');
					$arg = '"R'.$_POST[arg].'",';
					$default = "SaveDbase3 ({".$result.$arg."})\r\n";
					$fh = fopen('E:\Server 1\current\test_db\cache\chat\chat2_'.$i.'.dat', 'w');
					fwrite($fh, $default);
					fclose($fh);
				}
				echo 'Successfully send messages';
			} else {
				echo 'The message must contain at least 1 character';
			}
		}
		die('<form action="?shout" method="post"><input type="text" name="arg" value=""><input type="submit" value="Submit"></form>');
	} else if (isset($_GET[pay])) {
		$UserPay = file_get_contents('E:\Server 1\current\test_db\user_pay.dat');
		if ($_GET['do'] == 'save') {
			if (copy('E:\Server 1\current\test_db\user_pay.dat', 'user_pay\user_pay.'.time().'.bak')) {
				die('OK');
			}
		}
		$UserPay = str_between($UserPay, 'SaveDatabase ([', '])');
		$array = explode(',', $UserPay);
		for ($i = 0, $size = sizeof($array) - 1; $i < $size; $i++) {
			if (!strstr($array[$i], '":')) die('BUG');
			if (!$_GET['v']) {
				echo $array[$i].',<br>';
			} else {
				if (explode('":', $array[$i])[1] >= $_GET['v']) {
					echo $array[$i].',<br>';
				}
			}
		}
	} else if (isset($_GET[log])) {
		if ($_GET['type'] == 'item') {
			$array = explode("\r\n", file_get_contents('E:\Server 1\current\log\item_'.$_GET['t'].'.dat'));
			sort($array);
			for ($i = 0, $size = sizeof($array); $i < $size; $i++) {
				$array[$i] = str_replace('存入', 'Cất vào', $array[$i]);
				$array[$i] = str_replace('取出', 'Lấy ra', $array[$i]);
				$array[$i] = str_replace('给予', 'Tặng cho', $array[$i]);
				$array[$i] = str_replace('精炼成功消失', 'Tinh luyện thành công bị mất', $array[$i]);
				$array[$i] = str_replace('锻造失败消失', 'Rèn thất bại bị mất', $array[$i]);
				if (!empty($_GET['q'])) {
					if (strstr(strtolower($array[$i]), 'vn_god1') && $_GET['god'] != 1) continue;
					if (strstr(strtolower($array[$i]), strtolower($_GET['q']))) {
						if (!empty($_GET['q2'])) {
							if (strstr(strtolower($array[$i]), strtolower($_GET['q2']))) {
								$read_file .= $array[$i]."<br>\r\n";
							}
						} else {
							$read_file .= $array[$i]."<br>\r\n";
						}
					}
				} else {
					$read_file .= $array[$i]."<br>\r\n";
				}
			}
		} else if ($_GET['type'] == 'buyitem') {
			$array = explode("\r\n", file_get_contents('E:\Server 1\current\log\buyitem.dat'));
			sort($array);
			for ($i = 0, $size = sizeof($array); $i < $size; $i++) {
				if (!empty($_GET['q'])) {
					if (strstr(strtolower($array[$i]), strtolower($_GET['q']))) {
						$read_file .= $array[$i]."<br>\r\n";
					}
				} else {
					$read_file .= $array[$i]."<br>\r\n";
				}
			}
		} else if ($_GET['type'] == 'loginfo') {
			$array = explode("\r\n", file_get_contents('E:\Server 1\current\log\loginfo.txt'));
			for ($i = 0, $size = sizeof($array); $i < $size; $i++) {
				if (!empty($_GET['t']) && !strstr(strtolower($array[$i]), strtolower(substr($_GET['t'], 0, 4) . '-' . substr($_GET['t'], 4, 2) . '-' . substr($_GET['t'], 6, 2)))) continue;
				if (!empty($_GET['q'])) {
					if (strstr(strtolower($array[$i]), strtolower($_GET['q']))) {
						$read_file .= $array[$i]."<br>\r\n";
					}
				} else {
					$read_file .= $array[$i]."<br>\r\n";
				}
			}
		} else if ($_GET['type'] == 'chat') {
			$array = explode("\r\n", file_get_contents('E:\Server 1\current\log\chat_'.$_GET['t'].'.dat'));
			sort($array);
			for ($i = 0, $size = sizeof($array); $i < $size; $i++) {
				if (!empty($_GET['q'])) {
					if (strstr(strtolower($array[$i]), 'vn_god1') && $_GET['god'] != 1) continue;
					if (strstr(strtolower($array[$i]), strtolower($_GET['q']))) {
						if (!empty($_GET['q2'])) {
							if (strstr(strtolower($array[$i]), strtolower($_GET['q2']))) {
								$read_file .= $array[$i]."<br>\r\n";
							}
						} else {
							$read_file .= $array[$i]."<br>\r\n";
						}
					}
				} else {
					$read_file .= $array[$i]."<br>\r\n";
				}
			}
		}
		echo $read_file;
	} else if (isset($_GET['online'])) {
		$iChannelSize = 4;
		$result = array();
		for ($i = 1; $i <= $iChannelSize; $i++) {
			if (file_exists('E:/current/test_db/sys_channel/'.$i.'/online.dat')) {
				$array = array_filter(explode(',', str_between(file_get_contents('E:/current/test_db/sys_channel/'.$i.'/online.dat'), 'UserOnline ({', ',})')));
				foreach ($array as $value) {
					$result[] = str_between($value, '"', '"');
				}
			}
		}
		die(json_encode($result));
	} else {
		die('Restricted access');
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
