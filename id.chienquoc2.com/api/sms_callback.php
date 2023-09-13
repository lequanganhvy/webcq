<?php
	if ($_REQUEST['secretKey'] != 'WCPWGDSUT76E') {
		die("0|Loi khong xac dinh, vui long bao cho NPH.");
	}

	include('../includes/config.php');
	include('../includes/database.class.php');
	include('../includes/soap/nusoap.php');

	function md5_plus($str) {
		$len = strlen($str);
		$result = $str;
		for ($i = 0; $i < $len; $i++) {
			$result = md5($result);
		}
		return $result;
	}

	$Database = new Database();
	$Database->DBConnect($dbsettings[server], $dbsettings[name], $dbsettings[user], $dbsettings[pass], 'MySQL');

	$code = $_REQUEST['code'];
	$subCode = $_REQUEST['subCode'];
	$mobile = mysql_real_escape_string($_REQUEST['mobile']);
	$serviceNumber = $_REQUEST['serviceNumber'];
	$info = explode(' ', strtoupper($_REQUEST['info']));

	switch ($info[2]) {
		case 'MATKHAU':
			if ($serviceNumber != 8185) {
				die('0|Dau so khong hop le, vui long kiem tra lai');
			}
			if (substr($mobile, 0, 2) == '84') {
				$mobile = '0' . substr($mobile, 2);
			}
			if ($responseInfo == '') {
				$account = strtolower(stripslashes($info[3]));
				if (@mysql_num_rows(mysql_query('SELECT * FROM `account` WHERE `account`=\'' . $account . '\' AND `phonenumber`=\'' . $mobile . '\'')) == 0) {
					$responseInfo = 'Tai khoan khong ton tai hoac so dien thoai khong dung, thao tac that bai.';
				}
			}
			if ($responseInfo == '') {
				foreach ($gamesettings as $k => $v) {
					if ($socket =@ fsockopen('s'.$k.'.chienquoc2.com', 80, $errno, $errstr, 30)) {
						continue;
					}
					$responseInfo = 'Xay ra loi, vui long thu lai sau.';
					break;
				}
			}
			if ($responseInfo == '') {
				$password = substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 8);
				if (@mysql_query('UPDATE `account` SET `password`=\'' . md5_plus($password) .'\' WHERE account=\'' . $account . '\'')) {
					foreach ($gamesettings as $k => $v) {
						$nusoap_client = new nusoap_client($v['auth_remote']['url'], true);
						$nusoap_client->call('set_passwd', array('domain_name' => 'chienquoc2.com', 'ws_username' => $v['auth_remote']['account'], 'ws_password' => $v['auth_remote']['password'], 'id' => $account, 'passwd' => $password));
					}
				}
				$responseInfo = "Mat khau moi cua tai khoan $account la $password";
			}
			break;

		case 'SDT':
			if ($serviceNumber != 8185) {
				die('0|Dau so khong hop le, vui long kiem tra lai');
			}
			if (substr($mobile, 0, 2) == '84') {
				$mobile = '0' . substr($mobile, 2);
			}
			if ($responseInfo == '') {
				$account = strtolower(stripslashes($info[3]));
				$newPhoneNumber = strtolower(stripslashes($info[4]));
				if (@mysql_num_rows(mysql_query('SELECT * FROM `account` WHERE `account`=\'' . $account . '\' AND `phonenumber`=\'' . $mobile . '\'')) == 0) {
					$responseInfo = 'Tai khoan khong ton tai hoac so dien thoai khong dung, thao tac that bai.';
				}
			}
			if ($responseInfo == '') {
				@mysql_query('UPDATE `account` SET `phonenumber`=\'' . $newPhoneNumber .'\' WHERE account=\'' . $account . '\'');
				$responseInfo = 'Thay doi so dien thoai thanh cong';
			}
			break;

		case 'GRGIFTCODE':
			if ($serviceNumber != 8385) {
				die('0|Dau so khong hop le, vui long kiem tra lai');
			}
			$result = json_decode(@file_get_contents('http://id.chienquoc2.com/api/giftcode_gen.php?pack_name=CQ2008&p=zaq@123'), true);
			$responseInfo = 'Ban nhan duoc gift code mung thanh lap group Chien Quoc 2, day la gift code cua ban: ' . $result['code'];
			break;
	}

	if (empty($responseInfo)) {
		$responseInfo = 'Tin nhan sai cu phap!';
	}

	die("0|$responseInfo");
?>
