<?php
	require('includes/config.php');
	include('includes/database.class.php');

	$Database = new Database();
	$Database->DBConnect($dbsettings[server], $dbsettings[name], $dbsettings[user], $dbsettings[pass], 'MySQL');

	if ($_GET['type'] == 'id') {
		if (preg_match ('/[^A-z0-9]/', $_GET['id']) != 0) {
			die('Tài khoản không hợp lệ. Tài khoản có chứa từ hoặc cụm từ bị cấm.');
		} else if (strlen($_GET['id']) < 4) {
			die('Tài khoản không hợp lệ, tài khoản phải có từ 4 - 10 ký tự.');
		} else if (@mysql_num_rows(mysql_query("SELECT * FROM account WHERE account = '".stripslashes($_GET['id'])."'")) > 0) {
			die('Tài khoản đã có người sử dụng.');
		}
	} else if ($_GET['type'] == 'email') {
		if (@mysql_num_rows(mysql_query("SELECT * FROM account WHERE email = '".stripslashes($_GET['id'])."'")) > 0) {
			die('Email đã có người sử dụng.');
		}
	} else if ($_GET['type'] == 'check') {
		$sql_query = @mysql_query("SELECT * FROM account");
		while ($acc = @mysql_fetch_array($sql_query)) {
			if (@mysql_num_rows(mysql_query("SELECT * FROM account WHERE account = '".$acc['account']."'")) > 1) {
				echo $acc['account'].'<br>';
			}
		}
	}
?>