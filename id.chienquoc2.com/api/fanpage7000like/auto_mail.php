<?php
	function check_sent($email) {
		$sent_list = explode("\n", @file_get_contents('sent_list.dat'));
		for ($i = 0, $c = count($sent_list); $i < $c; $i++) {
			if ($email == $sent_list[$i]) {
				return true;
			}
		}
		return false;
	}
	if (!$_GET['p']) {
		die();
	}
	$email_list = explode("\n", strtolower(@file_get_contents('email_list.dat')));
	for ($i = 0, $c = count($email_list); $i < $c; $i++) {
		if ($email_list[$i] == '') continue;
		if (check_sent($email_list[$i]) == false) {
			$result = json_decode(@file_get_contents('http://id.chienquoc2.com/api/giftcode_gen.php?pack_name=CQ2010&p=' . $_GET['p']), true);

			if ($result['code'] != '') {
				$title = 'Tin Nhan Tu Chien Quoc 2 - Ma qua tang mung fanpage dat 7000 like';
				$message = "<div>\n\n<div>\n\n<img src=\"http://chienquoc2.com/templates/chienquoc/images/logo-chienquoc.png\" style=\"padding-top:20px\">\n\n<p>THÔNG TIN MÃ QUÀ TẶNG MỪNG FANPAGE ĐẠT 7000 LIKE</p>\n<br>\n<p><strong>Xin chào bạn!</strong><br>\n\nBạn đã sử dụng địa chỉ email này để đăng ký nhận mã quà tặng mừng fanpage game Chiến Quốc 2 đạt 7000 like của chúng tôi. Để tiến hành sử dụng, bạn vui lòng truy cập đường dẫn bên dưới: <br>\n\n<a href=\"http://id.chienquoc2.com/?page=maquatang\" target=\"_blank\">http://<span class=\"il\">id.chienquoc2</span>.com/?page=maquatang</a>\n\n<br><br>\n\n<strong>Mã quà tặng của bạn là:</strong></p>\n<br>\n<div>\n\n<table width=\"60%\" border=\"0\" cellspacing=\"4\" cellpadding=\"4\" align=\"center\" bgcolor=\"#CCCCCC\">\n\n<tr>\n\n<td>\n\n<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" bgcolor=\"#CCCCCC\" align=\"center\">\n\n<tr>\n\n<td><table width=\"100%\" border=\"0\" cellspacing=\"5\" cellpadding=\"5\" bgcolor=\"#CCFFCC\">\n\n<tr>\n\n<td align=\"center\" height=\"30\"><strong>" . $result['code'] . "</strong></td>\n\n</tr>\n\n</table></td>\n\n</tr>\n\n</table> </td>\n\n</tr></table>\n\n<p> </p>\n\n</div>\n<br>\n<p><em> Đây là Email tự động, vui lòng đừng trả lời Email này. Nếu có vấn đề xin liên hệ với chúng tôi.</em></p>\n\n<p><em>Neu khong doc duoc xin vui long chon View/Unicode UTF-8 tren trinh duyet dang dung.</em></p>\n\n<div></div>\n\n<p align=\"center\">© Bản quyền của Chiến Quốc 2</p>\n\n</div>\n\n</div>";
				$headers = "MIME-Version: 1.0\r\nContent-type: text/html; charset=utf-8\r\nFrom: Chien Quoc 2 <no-reply@chienquoc2.com>\r\n";
				@file_put_contents('sent_list.dat', $email_list[$i] . "\n", FILE_APPEND | LOCK_EX);
				mail($email_list[$i], $title, $message, $headers);
			}
		}
	}
?>