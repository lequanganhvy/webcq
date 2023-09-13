<?php
	require('includes/config.php');

	session_start();

	if (!isset($_SERVER["HTTP_REFERER"])) die(' ');

	$server_id = intval($_GET['server']);
	if (!isset($gamesettings[$server_id])) die(' ');
	if (!@fsockopen($gamesettings[$server_id]['host'], 80, $errno, $errstr, 30)) {
		die('offline');
	}
	if ($_GET['page'] == 'napthe') {
		die('Bạn có thể chuyển xu vào máy chủ này');
	} else {
		$result = file_get_contents('http://'.$gamesettings[$server_id]['host'].'/userdb.php?id='.$_SESSION['username']);
		if ($result == 'null') {
			die();
		} else {
			$data = json_decode($result, true);
			echo '<option value="" selected="selected">Hãy chọn nhân vật</option>';
			foreach ($data as $k => $v) {
				echo '<option value="'.$k.'">'.$v['Name'].' ('.$v['IdNumber'].')'.'</option>';
			}
			die();
		}
	}
?>
Offline
Nhân vật: KhưuLệY
Level: 1