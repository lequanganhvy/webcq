<?php
	if ($_GET['p'] == 'zaq@123') {
		$pack_name = $_GET['pack_name'];

		$promocodedb = json_decode(@file_get_contents('../../promocodedb.dat'), true);
		$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$characters_length = strlen($characters);
		$finished = false;
		while (!$finished) {
			$result = '';
			for ($i = 0; $i < 18; $i++) {
				if ($i % 6 == 0) $result .= '-';
				$result .= $characters[rand(0, $characters_length - 1)];
			}
			if (!$promocodedb[$result]) {
				$code = $pack_name . $result;
				switch ($pack_name) {
					case 'CQ2001':
						$promocodedb[$code] = array('item' => 'item/test/lebaokhaosatlan2loai1', 'status' => 1);
						break;
					case 'CQ2002':
						$promocodedb[$code] = array('item' => 'item/test/lebaokhaosatlan2loai2', 'status' => 1);
						break;
					case 'CQ2003':
						$promocodedb[$code] = array('item' => 'item/test/lebaokhaosatlan2loai3', 'status' => 1);
						break;
					case 'CQ2004':
						$promocodedb[$code] = array('item' => 'item/test/lebaotanthumactu1', 'status' => 1);
						break;
				}
				$finished = true;
			}
		}

		@file_put_contents('../../promocodedb.dat', json_encode($promocodedb));

		$result = json_encode(array('code' => $code));
		die($result);
	}
?>
