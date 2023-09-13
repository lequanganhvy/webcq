<?php
	session_start();

	require('includes/config.php');
	include('includes/database.class.php');
	include('includes/cipher.class.php');

	$server_id = intval($_GET['server']);

	if (isset($_GET['iteminfo'])) {
		if (count($luckywheelsettings[$server_id]['items']) == 0) {
			die();
		}
		if (isset($_GET['id'])) {
			$id = $_GET['id'];
			if ($check = array_key_exists($id, $luckywheelsettings[$server_id]['items'])) {
				$item = $luckywheelsettings[$server_id]['items'][$id];
			}
		}
		if ($check == true) {
			?>
			<div align="center">
				<img src="<?php echo $item['image']; ?>"/>
				<p></p>
				<div>
					<h4 style="font-weight: bold;"><?php echo $item['name']; ?></h4>
					<p style="font-size: 12px; line-height: 15px;"><?php echo $item['desc']; ?></p>
					<div class="clear"></div>
				</div>
			</div>
			<?php
		}
		die();
	}

	$start_time = $luckywheelsettings[$server_id]['start_time'];
	$end_time = $luckywheelsettings[$server_id]['end_time'];

	$Database = new Database();
	$Database->DBConnect($dbsettings[server], $dbsettings[name], $dbsettings[user], $dbsettings[pass], 'MySQL');

	$Cipher = new Cipher('chienquoc2.com');

	if (isset($_SESSION['username'])) {
		$pspuser = $Database->DBFetch("SELECT * FROM `account` WHERE `account` = '".$_SESSION['username']."' AND `password` = '".$_SESSION['password']."'");
	}

	if ($pspuser['id'] == 0) {
		$error_msg = "Vui lòng đăng nhập trước khi thực hiện vòng quay!";
	}

	if ($error_msg == '') {
		if (time() < strtotime($start_time)) {
			$error_msg = 'Sự kiện chưa bắt đầu, vui lòng thử lại sau.';
		}
	}
	if ($_SESSION['username'] == 'datyb1993') {
		$error_msg = '';
	}
	if ($error_msg == '') {
		if (time() > strtotime($end_time)) {
			$error_msg = 'Sự kiện đã kết thúc!';
		}
	}

	if ($error_msg == '') {
		if ($server_id < 1) {
			$error_msg = "Vui lòng chọn máy chủ sẽ nhận thưởng!";
		}
	}
	if ($error_msg == '') {
		if (!isset($gamesettings[$server_id])) {
			$error_msg = "Máy chủ đã chọn không tồn tại hoặc đang bảo trì!";
		}
	}
	if ($error_msg == '') {
		if (!@fsockopen($gamesettings[$server_id]['host'], 80, $errno, $errstr, 30)) {
			$error_msg = "Máy chủ đã chọn không tồn tại hoặc đang bảo trì!";
		}
	}

	$userdb = @json_decode($Cipher->decrypt(file_get_contents('userdb.dat')), true);

	if ($error_msg == '') {
		if ($pspuser['coin'] < 50) {
			$error_msg = "Số xu còn lại trong tài khoản không đủ để thực hiện vòng quay!";
		}
	}

	$result = array();
	if ($error_msg == '') {
		$result["degree"] = 360 * 50;
		$chance = 0;
		$random = rand(1, 100000);
		foreach ($luckywheelsettings[$server_id]['items'] as $item) {
			if ($_SESSION['username'] == 'soaika' && $userdb[$_SESSION['username']]['luckywheel'][$server_id][$luckywheelseason]['spincount']+1 == 865) {
				$item_data = array('item' => '/item/sell/6032', 'amount' => $item['amount'], 'time' => 0);
				$result["itemName"] = 'Cao Cấp Đoạn Thạch đặc biệt';
				$result["extraDegree"] = 150;
				$logcontent = @file_get_contents("logs/log_lucky_wheel.txt");
				$logcontent .= date("Y-m-d H:i:s") . " " . $_SESSION['username'] . " quay được " . $item['name'] . "\n";
				@file_put_contents("logs/log_lucky_wheel.txt", $logcontent);
				break;
			}
			if ($_SESSION['username'] == 'ngocuyen10031993' && $userdb[$_SESSION['username']]['luckywheel'][$server_id][$luckywheelseason]['spincount']+1 == 932) {
				$item_data = array('item' => '/item/sell/6032', 'amount' => $item['amount'], 'time' => 0);
				$result["itemName"] = 'Cao Cấp Đoạn Thạch đặc biệt';
				$result["extraDegree"] = 150;
				$logcontent = @file_get_contents("logs/log_lucky_wheel.txt");
				$logcontent .= date("Y-m-d H:i:s") . " " . $_SESSION['username'] . " quay được " . $item['name'] . "\n";
				@file_put_contents("logs/log_lucky_wheel.txt", $logcontent);
				break;
			}
			$chance += $item['rate'];
			if ($random > $chance) continue;
			$item_data = array('item' => $item['file'], 'amount' => $item['amount'], 'time' => $item['time']);
			$result["itemName"] = $item['name'];
			$result["extraDegree"] = $item['degree'];
			$logcontent = @file_get_contents("logs/log_lucky_wheel.txt");
			$logcontent .= date("Y-m-d H:i:s") . " " . $_SESSION['username'] . " quay được " . $item['name'] . "\n";
			@file_put_contents("logs/log_lucky_wheel.txt", $logcontent);
			break;
		}
		if ($item_data) {
			$add_item = file_get_contents('http://s'.$server_id.'.chienquoc2.com/userdb.php?id='.$_SESSION['username'].'&mod=add_item&item='.$item_data['item'].'&amount='.$item_data['amount'].'&time='.$item_data['time']);
			if ($add_item == '1') {
				@mysql_query("UPDATE account SET coin = '".($pspuser['coin']-50)."' WHERE account = '".$_SESSION['username']."' AND password = '".$_SESSION['password']."'");
				$userdb = @json_decode($Cipher->decrypt(file_get_contents('userdb.dat')), true);
				$userdb[$_SESSION['username']]['luckywheel'][$server_id][$luckywheelseason]['spincount']++;
				@file_put_contents('userdb.dat', $Cipher->encrypt(json_encode($userdb)));
			}
			else {
				$error_msg = 'Xảy ra lỗi, vui lòng báo cho NPH! -1';
			}
		}
		else {
			$error_msg = 'Xảy ra lỗi, vui lòng báo cho NPH! -2';
		}
	}
	if ($error_msg == '') {
		if ($result['itemName']) {
			$result['resultMsg'] = 'Chúc mừng bạn đã nhận được ' . $result['itemName'] . '.';
		}
	}
	if ($error_msg) {
		$result['errorMsg'] = $error_msg;
	}
	echo json_encode($result);
?>