<?php
	if ($_GET['p'] == 'zaq@123') {
		$pack_name = $_GET['pack_name'];

		$promocodedb = json_decode(@file_get_contents('../promocodedb.dat'), true);
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
					case 'CQ2005':
						$promocodedb[$code] = array('item' => 'item/test/lebaotrian4namchienquoc', 'status' => 1);
						break;
					case 'CQ2006':
						$promocodedb[$code] = array('item' => 'item/test/lebaonammoi2017', 'status' => 1);
						break;
					case 'CQ2007':
						$promocodedb[$code] = array('item' => 'item/test/lebaonammoi2017_2', 'status' => 1);
						break;
					case 'CQ2008':
						$promocodedb[$code] = array('item' => 'item/test/lebaomungthanhlapgroup', 'status' => 1);
						break;
					case 'CQ2009':
						$promocodedb[$code] = array('item' => 'item/test/lebaohungvuong', 'status' => 1);
						break;
					case 'CQ2010':
						$promocodedb[$code] = array('item' => 'item/test/lebaofanpage7000like', 'status' => 1);
						break;
					case 'CQ2011':
						$promocodedb[$code] = array('item' => 'item/test/lebaokitichbongda', 'status' => 1);
						break;
					case 'CQ2012':
						$promocodedb[$code] = array('item' => 'item/test/lebaomuahesoidong2018', 'status' => 1);
						break;
					case 'CQ2013':
						$promocodedb[$code] = array('item' => 'item/test/lebaosinhnhatchienquoc2018', 'status' => 1);
						break;
					case 'CQ2014':
						$promocodedb[$code] = array('item' => 'item/test/lebaobongdaffcup2018', 'status' => 1);
						break;
				}
				$finished = true;
			}
		}

		@file_put_contents('../promocodedb.dat', json_encode($promocodedb));

		$result = json_encode(array('code' => $code));
		die($result);
	}
?>
