	<div id="masthead">
		
		<div class="content_pad">
			
			<h1 class="">Lấy lại mật khẩu</h1>
		
		</div> <!-- .content_pad -->
		
	</div> <!-- #masthead -->	
	
	<div id="content" class="xgrid">
		
		<div class="x9">
			
			
	<div class="accordion_container">	
<?php if (empty($_GET['temp'])) {
	if (!empty($_POST)) {
		if ($_SESSION['security_code'] != strtolower($_POST['txtCaptcha']) || empty($_SESSION['security_code'])) {
			$error_msg = 'Chuỗi mã xác nhận không đúng';
		}
		if ($error_msg == '') {
			$account = stripslashes($_POST['fieldAcount']);
			if (@mysql_num_rows(mysql_query("SELECT * FROM account WHERE account = '".$account."'")) == 0) {
				$error_msg = 'Tài khoản không tồn tại';
			}
		}
		if ($error_msg == '') {
			$pspuser = $Database->DBFetch("SELECT * FROM account WHERE account = '$account'");
			if ($_POST['fieldEmail'] != $pspuser['email']) {
				$error_msg = 'Email không đúng';
			}
		}
		if ($error_msg == '') {
			$exp_date = date("Y-m-d H:i:s", mktime(date('H'), date('i'), date('s'), date('m'), date('d')+3, date('Y')));
			$key = md5($account.'_'.$pspuser['email'].rand(0, 10000).$exp_date);
			if (@mysql_query("INSERT INTO recovery_pwd (`id`, `account`, `key`, `exp_date`) VALUES (NULL, '$account', '$key', '$exp_date')")) {
				$message = "<div>\n\n<div>\n\n<img src=\"http://chienquoc2.com/templates/chienquoc/images/logo-chienquoc.png\" style=\"padding-top:20px\">\n\n<p>THÔNG TIN LẤY LẠI MẬT KHẨU TÀI KHOẢN GAME</p>\n\n<p><strong>Xin chào bạn!</strong><br>\n\nBạn đã sử dụng chức năng lấy lại mật khẩu tài khoản Game của chúng tôi. <br>\n\n<a href=\"http://chienquoc2.com\" target=\"_blank\">http://<span class=\"il\">chienquoc2</span>.com</a>\n\n<br><br>\n\n<strong>Bạn hãy nhấn vào đường link dưới đây để nhận mật khẩu mới của tài khoản Game</strong></p>\n\n<div>\n\n<table width=\"60%\" border=\"0\" cellspacing=\"4\" cellpadding=\"4\" align=\"center\" bgcolor=\"#CCCCCC\">\n\n<tr>\n\n<td>\n\n<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"1\" bgcolor=\"#CCCCCC\" align=\"center\">\n\n<tr>\n\n<td><table width=\"100%\" border=\"0\" cellspacing=\"5\" cellpadding=\"5\" bgcolor=\"#CCFFCC\">\n\n<tr>\n\n<td align=\"center\" height=\"30\"><a href=\"http://id.chienquoc2.com/?page=quenmatkhau&amp;temp=$key\" target=\"_blank\"><strong>Nhận mật khẩu mới tài khoản Game</strong></a></td>\n\n</tr>\n\n</table></td>\n\n</tr>\n\n</table> </td>\n\n</tr></table>\n\n<p> <strong>Link nhận mật khẩu mới tài khoản Game:</strong><br>\n\n<a href=\"http://id.chienquoc2.com/?page=quenmatkhau&temp=$key\" target=\"_blank\">http://<span class=\"il\">id.chienquoc2</span>.com/?page=quenmatkhau&temp=$key</a> </p>\n\n</div>\n\n<p><em> Đây là Email tự động, vui lòng đừng trả lời Email này. Nếu có vấn đề xin liên hệ với chúng tôi.</em></p>\n\n<p><em>Neu khong doc duoc xin vui long chon View/Unicode UTF-8 tren trinh duyet dang dung.</em></p>\n\n<div></div>\n\n<p align=\"center\">© Bản quyền của Chiến Quốc 2</p>\n\n</div>\n\n</div>";
				$headers = "MIME-Version: 1.0\r\nContent-type: text/html; charset=utf-8\r\nFrom: Chien Quoc 2 <no-reply@chienquoc2.com>\r\n";
				$email = $pspuser['email'];
				$email_title = 'Tin Nhan Tu Chien Quoc 2 - Ma xac nhan tai khoan game';
				if (strstr($email, '@') == 'yahoo.com') {
					$result = mail($email, $email_title, $message, $headers);
					mail($email.'.vn', $email_title, $message, $headers);
				} else if (strstr($email, '@') == 'yahoo.com.vn') {
					$result = mail($email, $email_title, $message, $headers);
					mail(substr($email, -3), $email_title, $message, $headers);
				} else {
					$result = mail($email, $email_title, $message, $headers);
				}
				if ($result == true) {
					$info_msg = 'Thông tin hướng dẫn đổi mật khẩu đã được gửi về Email cho bạn.';
				} else {
					$error_msg = 'Gửi mail không thành công!';
				}
			} else {
				$error_msg = 'Xảy ra lỗi trong lúc lấy lại mật khẩu!';
			}
		}
	} ?>

	<h2 class="accordion_panel"><a href="#">Gửi mật khẩu qua SMS</a></h2> 
	<div class="accordion_content"> 
		<div class="block"> 
			<p><img src="/images/matkhausms.jpg"></p> 
		</div> 
	</div>
	<h2 class="accordion_panel"><a href="#">Gửi mật khẩu qua Email</a></h2> 
	<div class="accordion_content"> 
		<div class="block">
			<form action="#" method="post" class="form label-inline" id="formpass">	
                    <div class="field"><label for="fieldAcount">Tên đăng nhập: </label> <input id="fieldAcount" name="fieldAcount" size="50" type="text" class="medium" /></div>				
                    <div class="field"><label for="fieldEmail">Địa chỉ Email: </label> <input id="fieldEmail" name="fieldEmail" size="50" type="text" class="medium" /></div>
                    <div class="field"><label for="txtCaptcha">Nhập mã xác nhận</label>                            
                    <input type="hidden" name="CAPTCHA_Postback" id="CAPTCHA_Postback" value="true" />
                        <div style="float:left"><input id="txtCaptcha" name="txtCaptcha" size="6" type="text" class="txtCapcha" maxlength="6" /></div><div style="float:left"><a onclick="reloadCapcha();" href="javascript:void(0);"><img id="imgmCaptcha" src="secureimage.php?<?php echo time(); ?>" style="border-width:0px; width:120px; height:50px" /></a></div>
                    </div>
                    <br />
                    <div class="buttonrow">
                        <input id="validate" class="btn" type="submit" name="sigup" value="Lấy lại mật khẩu">
                        <input id="reset" class="btn btn-black" name="reset" type="button" value="Reset">
                    </div>
                </form>             	
		</div> 
	</div>

<?php } else {
	if (!empty($_POST)) {
		if ($_POST['fieldPasswordNew'] != $_POST['fieldRePassword']) {
			$error_msg = 'Vui lòng xác nhận lại chính xác mật khẩu';
		}
		if ($error_msg == '') {
			if ($_SESSION['security_code'] != strtolower($_POST['txtCaptcha']) || empty($_SESSION['security_code'])) {
				$error_msg = 'Chuỗi mã xác nhận không đúng';
			}
		}
		if ($error_msg == '') {
			$key = stripslashes($_GET['temp']);
			if (@mysql_num_rows(mysql_query("SELECT * FROM `recovery_pwd` WHERE `key` = '$key'")) == 0) {
				$error_msg = 'Mã đổi mật khẩu đã hết hạn';
			}
		}
		if ($error_msg == '') {
			foreach ($gamesettings as $game) {
				if (!@fsockopen($game['host'], 80, $errno, $errstr, 30)) {
					$error_msg = 'Xảy ra lỗi truyền nhận dữ liệu, vui lòng thử lại sau.';
					break;
				}
			}
		}
		if ($error_msg == '') {
			$password = md5_plus($_POST['fieldPasswordNew']);
			$pspuser = $Database->DBFetch("SELECT * FROM `recovery_pwd` WHERE `key` = '$key'");
			$sql_query = @mysql_query("UPDATE account SET password = '$password' WHERE account = '".$pspuser['account']."'");
			if ($sql_query) {
				foreach ($gamesettings as $k => $v) {
					$nusoap_client = new nusoap_client($v['auth_remote']['url'], true);
					$nusoap_client->call('set_passwd', array('domain_name' => 'chienquoc2.com', 'ws_username' => $v['auth_remote']['account'], 'ws_password' => $v['auth_remote']['password'], 'id' => $pspuser['account'], 'passwd' => $_POST['fieldPasswordNew']));
				}
				$info_msg = 'Thay đổi mật khẩu thành công';
				@mysql_query("DELETE FROM `recovery_pwd` WHERE `key` = '$key'");
			}
		}
	} ?>
  
	<h2 class="accordion_panel"><a href="#">Xác lập mật khẩu mới</a></h2> 
	<div class="accordion_content"> 
		<div class="block"> 
            <form action="#" method="post" class="form label-inline" id="formpass2">	
                <div class="field"><label for="fieldPasswordNew">Mật khẩu mới: </label> <input id="fieldPasswordNew" name="fieldPasswordNew" size="50" type="password" class="medium" placeholder="Vui lòng điền mật khẩu mới của bạn" /></div>				
                <div class="field"><label for="fieldRePassword">Xác nhận lại mật khẩu: </label> <input id="fieldRePassword" name="fieldRePassword" size="50" type="password" class="medium" placeholder="Xác nhận lại mật khẩu mới của bạn" /></div>
               
                <div class="field"><label for="txtCaptcha">Nhập mã xác nhận</label>                            
                <input type="hidden" name="CAPTCHA_Postback" id="CAPTCHA_Postback" value="true" />
                    <div style="float:left"><input id="txtCaptcha" name="txtCaptcha" size="6" type="text" class="txtCapcha" maxlength="6" /></div><div style="float:left"><a onclick="reloadCapcha();" href="javascript:void(0);"><img id="imgmCaptcha" src="secureimage.php?<?php echo time(); ?>" style="border-width:0px; width:120px; height:50px" /></a></div>
                </div>
                
                <br />
                <div class="buttonrow">
                    <input id="validate" class="btn" type="submit" name="sigup" value="Thay đổi mật khẩu">
                    <input id="reset" class="btn btn-black" name="reset" type="reset" value="Reset">
                </div>
            </form>
		</div> 
	</div>
<?php } ?>
 	
	
</div> <!-- .accordion_container -->

		</div> <!-- .x9 -->
		
		
		<div class="x3">
		
			<p><img src="images/teaser.png" /></p>
			
		</div> <!-- .x3 -->
		
	</div> <!-- #content -->

	
