<?php
	if (!isset($_SESSION['username'])) {
		$_SESSION['error_msg'] = 'Bạn cần phải đăng nhập mới có thể thực hiện được việc này';
		die(header('location: http://'.$_SERVER['SERVER_NAME']));
	}
	if (!empty($_POST)) {
		if ($_SESSION['security_code'] != strtolower($_POST['txtCaptcha']) || empty($_SESSION['security_code'])) {
			//$error_msg = 'Chuỗi mã xác nhận không đúng';
		}
		if ($error_msg == '') {
			if ($_POST['fieldServer'] == '' || $_POST['fieldServer'] == 0) {
				$error_msg = "Vui lòng chọn máy chủ sẽ nhận thưởng!";
			}
			$server_id = intval($_POST['fieldServer']);
		}
		if ($error_msg == '') {
			if (!@fsockopen($gamesettings[$server_id]['host'], 80, $errno, $errstr, 30)) {
				$error_msg = "Máy chủ đã chọn không tồn tại hoặc đang bảo trì!";
			}
		}
		if ($error_msg == '') {
			$code_decrypt = explode('-', $_POST['txtCode']);
			$code_settings = $promocodesettings[$code_decrypt[0]];
			if ($code_settings['type'] == 1) {
				$promocodedb = json_decode(@file_get_contents('promocodedb.dat'), true);
				if ($promocodedb[strtoupper($_POST['txtCode'])]['status'] != 1) {
					$error_msg = 'Mã quà tặng này không đúng hoặc đã được sử dụng';
				}
			}
			else if ($code_settings['type'] == 2) {
				if (strtoupper($code_decrypt[1]) != strtoupper($code_settings['value'])) {
					$error_msg = 'Mã quà tặng này không đúng hoặc đã được sử dụng';
				}
			}
			else {
				$error_msg = 'Mã quà bị lỗi, vui lòng liên hệ với bộ phận hỗ trợ để được giúp đỡ hoặc thử lại sau. (-1)';
			}
		}
		if ($error_msg == '') {
			if ($code_settings['expire'] != null && strtotime($code_settings['expire']) < time()) {
				$error_msg = 'Mã quà tặng này đã hết hạn';
			}
		}
		if ($error_msg == '') {
			if ($code_settings['type'] == 1) {
				if ($code_settings['item'][0] == '') {
					$error_msg = 'Mã quà bị lỗi, vui lòng liên hệ với bộ phận hỗ trợ để được giúp đỡ hoặc thử lại sau. (-2)';
				}
			}
		}
		if ($error_msg == '') {
			if ($code_settings['server'] != -1 && $code_settings['server'] != $server_id) {
				$error_msg = 'Quà tặng này chỉ có thể nhận ở máy chủ ' . $gamesettings[$code_settings['server']]['name'];
			}
		}
		if ($error_msg == '') {
			$userdb = @json_decode($Cipher->decrypt(file_get_contents('userdb.dat')), true);
			if ($code_settings['limit'] > 0 && $userdb[$_SESSION['username']][$code_settings['key']] != '') {
				$error_msg = 'Loại quà tặng này bạn chỉ có thể nhận ' . $code_settings['limit'] . ' lần duy nhất';
			}
		}
		if ($error_msg == '') {
			$userdb[$_SESSION['username']][$code_settings['key']] = $_POST['txtCode'];
			@file_put_contents('userdb.dat', $Cipher->encrypt(json_encode($userdb)));
			if ($code_settings['type'] == 1) {
				$promocodedb[$_POST['txtCode']]['status'] = 0;
				@file_put_contents('promocodedb.dat', json_encode($promocodedb));
				$add_item = @file_get_contents('http://'.$gamesettings[$server_id]['host'].'/userdb.php?id='.$_SESSION['username'].'&mod=add_item&item='.$code_settings['item'][0].'&amount=1&time=0');
			}
			else if ($code_settings['type'] == 2) {
				$add_item = @file_get_contents('http://'.$gamesettings[$server_id]['host'].'/userdb.php?id='.$_SESSION['username'].'&mod=add_item&item='.$code_settings['item'][0].'&amount=1&time=0');
			}
			if ($add_item == '1') {
				$info_msg = 'Bạn đã nhập mã quà tặng thành công. Vui lòng đăng nhập vào trò chơi tìm gặp Thất Quốc Tổng Quản ở Chu Quốc kênh 1 để nhận được lễ bao.';
			} else {
				$error_msg = 'Xảy ra lỗi, vui lòng liên hệ với bộ phận hỗ trợ khách hàng để được giúp đỡ';
			}
		}
	} ?>
	<div id="masthead">
		
		<div class="content_pad">
			
			<h1 class="">Nhập mã quà tặng</h1>
			
		</div> <!-- .content_pad -->
		
	</div> <!-- #masthead -->	
	
	<div id="content" class="xgrid">
		
		<div class="x8">
			
			<form action="#" method="post" class="form label-inline uniform" name="Xu2KNB" id="Xu2KNB">
						<h3>Thông tin tài khoản</h3>
							<div class="field"><label for="fname">Tên tài khoản </label> <p class="field_info"><?php echo $_SESSION['username']; ?></p></div>				
						<h3>Nhập mã quà tặng</h3>
							<div class="field"><label for="txtCaptcha">Mã quà tặng </label>                            
                           		<div style="float:left"><input id="txtCode" name="txtCode" type="text" class="txtCode" /></div>
							</div>
							<div class="field"><p class="field_help" style="font-size: 13px;">Lưu ý:<br>- Mỗi mã quà tặng chỉ được sử dụng một lần.</p></div>
						<h3>Lựa chọn máy chủ</h3>
							<div class="field">
								<label for="type">Nhập vào Máy chủ </label>
								<select id="type" class="medium" name="fieldServer" onchange="fnSubmitRegis(this.value);">
										<option value="" selected="selected">Hãy chọn máy chủ</option>
<?php foreach ($gamesettings as $k => $v) {
    echo '<option value="'.$k.'">'.$v['name'].'</option>';
} ?>
								</select>
							</div>
							
							<div class="field"><label for="txtCaptcha">Nhập mã xác nhận</label>                            
                            <input type="hidden" name="CAPTCHA_Postback" id="CAPTCHA_Postback" value="true" />
                           		<div style="float:left"><input id="txtCaptcha" name="txtCaptcha" size="6" type="text" class="txtCapcha" maxlength="6" /></div><div style="float:left"><a onclick="reloadCapcha();" href="javascript:void(0);"><img id="imgmCaptcha" src="secureimage.php?<?php echo time(); ?>" style="border-width:0px; width:120px; height:50px" /></a></div>
                            </div>
                            
							<br />
							<div class="buttonrow" id="submitbutton" style="display:none">
								<input id="validate" class="btn" type="submit" name="sigup" value="Xác Nhận">
								<input id="reset" class="btn btn-black" name="reset" type="button" value="Reset">
							</div>

						</form>
			
		</div> <!-- .x8 -->
		<div class="apg-mini apg-mini-1">
            <div class="apg-mini apg-mini-1">
                    <div class="apg-option ">
                        
                        <div class="apg-header">			
                            <h1>Quản lý tài khoản</h1>
                        </div>
                        
                        <div class="apg-content">
                        <p>Chào mừng bạn đến với Chiến Quốc 2.</p>					
                        <ul>
                            <li><strong><a href="/?page=taikhoan">Thay đổi mật khẩu</a></strong></li>			
                            <li><strong><a href="/?page=taikhoan">Thay đổi thông tin cá nhân</a></strong></li>
                            <li><strong><a href="/?page=lichsugiaodich">Lịch sử giao dịch</a></strong></li>
                        </ul>
                        </div>
                        <div class="apg-footer">
                            <span class="apg-price">Bạn còn <strong><?php echo intval($pspuser['coin']); ?> Xu</strong></span>
                            <?php if ($pspuser['coin'] > 0) { ?><a href="/?page=kimnguyenbao" class="btn btn-small">Đổi Xu</a><?php } else { ?><a href="/?page=napthe" class="btn btn-small">Nạp Xu</a><?php } ?>							 
                         </div>					 
                    </div>
            </div>

		</div>
	</div> <!-- #content -->
	
