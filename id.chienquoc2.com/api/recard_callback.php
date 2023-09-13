<?php
	include('../includes/config.php');
	include('../includes/database.class.php');
	
	$Database = new Database();
	$Database->DBConnect($dbsettings[server], $dbsettings[name], $dbsettings[user], $dbsettings[pass], 'MySQL');

	if (isset($_POST['secret_key']) && $recard['secret_key'] == $_POST['secret_key']) {
		//retrieve the $_POST variables
		$transaction_code = $_POST['transaction_code'];
		$status = $_POST['status'];
		$amount = $_POST['amount'];
		
		$card_query = mysql_query("SELECT account FROM card_history WHERE transaction_code = '".$transaction_code."'");
		$card = mysql_fetch_row($card_query);
		
		if ($card) {
			if($status == 1) {
				$statustext = 'OK';
				
				$coin = $amount / 100;
				
				mysql_query("UPDATE account SET coin = coin + '".$coin."' WHERE account = '".$card[0]."'");
			} else {
				$statustext = 'Cancel';
			}
		}
		
		$card_value = $amount / 1000;
		
		mysql_query("UPDATE card_history SET status = '$statustext', cardvalue = '$card_value' WHERE transaction_code = '".$transaction_code."'");
	}	
?>