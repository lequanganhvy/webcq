<?php
	if (!isset($_SESSION['username'])) {
		$_SESSION['error_msg'] = 'Bạn cần phải đăng nhập mới có thể thực hiện được việc này';
		die(header('location: http://'.$_SERVER['SERVER_NAME']));
	}
	if ($_GET['action'] == 'reset') {
		unset($_SESSION['SERVER_TRANSFER_SERVER_SOURCE']);
		unset($_SESSION['SERVER_TRANSFER_CHARACTER_SOURCE']);
		die(header('location: http://'.$_SERVER['SERVER_NAME'].'?page=chuyenserver'));
	}
	if (!empty($_POST)) {
		if ($_SESSION['security_code'] != strtolower($_POST['txtCaptcha']) || empty($_SESSION['security_code'])) {
			$error_msg = 'Chuỗi mã xác nhận không đúng';
		}
		if (!isset($_SESSION['SERVER_TRANSFER_SERVER_SOURCE'])) {
			if ($error_msg == '') {
				if ($_POST['fieldServer'] == '' || $_POST['fieldServer'] == 0) {
					$error_msg = 'Vui lòng chọn máy chủ có nhân vật cần chuyển đi!';
				}
				$server_id = intval($_POST['fieldServer']);
			}
			if ($error_msg == '') {
				if (!@fsockopen($gamesettings[$server_id]['host'], 80, $errno, $errstr, 30)) {
					$error_msg = 'Máy chủ đã chọn không tồn tại hoặc đang bảo trì!';
				}
			}
			if ($error_msg == '') {
				$online = json_decode(@preg_replace('/[\x00-\x1F\x80-\xFF]/', '', @file_get_contents('http://'.$gamesettings[$server_id]['host'].'/gm/?online')), true);
				if (count($online) > 0 && in_array($_SESSION['username'], $online) == true) {
					$error_msg = 'Tài khoản đang trực tuyến, không thể tiến hành chuyển máy chủ';
				}
			}
			if ($error_msg == '') {
				if ($_POST['fieldCharacter'] < 1) {
					$error_msg = 'Bạn chưa chọn nhân vật sẽ chuyển đi!';
				}
			}
			if ($error_msg == '') {
				if (!in_array($server_id, array(3))) {
					$error_msg = 'Máy chủ không hợp lệ!';
				}
			}
			if ($error_msg == '') {
				$userdb = json_decode(@file_get_contents('http://'.$gamesettings[$server_id]['host'].'/userdb.php?id='.$_SESSION['username']), true);
				if (!isset($userdb[$_POST['fieldCharacter']])) {
					$error_msg = 'Nhân vật không tồn tại!';
				} else {
					$character_slot = intval($_POST['fieldCharacter']);
				}
			}
			if ($error_msg == '') {
				$_SESSION['SERVER_TRANSFER_SERVER_SOURCE'] = $server_id;
				$_SESSION['SERVER_TRANSFER_CHARACTER_SOURCE'] = $_POST['fieldCharacter'];
				$_SESSION['SERVER_TRANSFER_CHARACTER_NAME_SOURCE'] = $userdb[$_POST['fieldCharacter']]['Name'];
			}
		} else {
			if ($error_msg == '') {
				if ($_POST['fieldServer'] == '' || $_POST['fieldServer'] == 0) {
					$error_msg = 'Vui lòng chọn máy chủ có nhân vật cần chuyển đến!';
				}
				$server_id = intval($_POST['fieldServer']);
			}
			if ($error_msg == '') {
				$online = json_decode(@preg_replace('/[\x00-\x1F\x80-\xFF]/', '', @file_get_contents('http://'.$gamesettings[$server_id]['host'].'/gm/?online')), true);
				if (count($online) > 0 && in_array($_SESSION['username'], $online) == true) {
					$error_msg = 'Tài khoản đang trực tuyến, không thể tiến hành chuyển máy chủ';
				}
			}
			if ($error_msg == '') {
				if ($_POST['fieldCharacter'] < 1) {
					$error_msg = 'Bạn chưa chọn nhân vật sẽ chuyển đến!';
				}
			}
			if ($error_msg == '') {
				if ($server_id == $_SESSION['SERVER_TRANSFER_SERVER_SOURCE']) {
					$error_msg = 'Máy chủ không hợp lệ!';
				}
			}
			if ($error_msg == '') {
				if (!in_array($server_id, array(2))) {
					$error_msg = 'Máy chủ không hợp lệ!';
				}
			}
			if ($error_msg == '') {
				$userdb = json_decode(@file_get_contents('http://'.$gamesettings[$server_id]['host'].'/userdb.php?id='.$_SESSION['username']), true);
				if (!isset($userdb[$_POST['fieldCharacter']])) {
					$error_msg = 'Nhân vật không tồn tại!';
				} else {
					$character_slot = intval($_POST['fieldCharacter']);
				}
			}
			if ($error_msg == '') {
				$result = json_decode(@file_get_contents('http://'.$gamesettings[$server_id]['host'].'/server_transfer.php?id='.$_SESSION['username'].'&char_src='.$_SESSION['SERVER_TRANSFER_CHARACTER_SOURCE'].'&char_dst='.$character_slot.'&server_src='.$_SESSION['SERVER_TRANSFER_SERVER_SOURCE'].'&server_dst='.$server_id), true);
				if ($result['result'] == 1) {
					$info_msg = 'Nhân vật bạn đã được chuyển thành công, vui lòng đăng nhập để kiểm tra!';
				} else if ($result['result'] == -1) {
					$error_msg = 'Nhân vật ở server nguồn không tồn tại!';
				} else if ($result['result'] == -2) {
					$error_msg = 'Nhân vật ở server đích không tồn tại!';
				} else {
					$error_msg = 'Lỗi hệ thống, vui lòng liên hệ hỗ trợ!';
				}
			}
		}
	} ?>
	<div id="masthead">
		
		<div class="content_pad">
			
			<h1 class="">Chuyển máy chủ</h1>
			
		</div> <!-- .content_pad -->
		
	</div> <!-- #masthead -->	
	
	<div id="content" class="xgrid">
		
		<div class="x8">
			
			<form action="#" method="post" class="form label-inline uniform" name="Xu2KNB" id="Xu2KNB">
						<h3>Thông tin tài khoản</h3>
							<div class="field">
								<label for="fname">Tên tài khoản </label> <p class="field_info"><?php echo $_SESSION['username']; ?></p>
<?php if (isset($_SESSION['SERVER_TRANSFER_SERVER_SOURCE'])) { ?>
								<label for="fname">Máy chủ nguồn </label>
								<p class="field_info">
									<?php echo $gamesettings[$_SESSION['SERVER_TRANSFER_SERVER_SOURCE']]['name']; ?>
									<a href="?page=chuyenserver&action=reset">(Chọn lại)</a>
								</p>
								<label for="fname">Nhân vật </label>
								<p class="field_info">
									<?php echo $_SESSION['SERVER_TRANSFER_CHARACTER_NAME_SOURCE']; ?>
								</p>
							</div>
						<h3>Lựa chọn máy chủ đích</h3>
							<div class="field">
								<label for="type">Chọn Máy chủ </label>
								<select id="type" class="medium" name="fieldServer" onchange="fnSubmitRegis(this.value);">
										<option value="" selected="selected">Hãy chọn máy chủ</option>
<?php foreach ($gamesettings as $k => $v) {
	if (!in_array($k, array(2))) continue;
	echo '<option value="'.$k.'">'.$v['name'].'</option>';
} ?>
								</select>
							</div>
<?php } else { ?>
							</div>
						<h3>Lựa chọn máy chủ nguồn</h3>
							<div class="field">
								<label for="type">Chọn Máy chủ </label>
								<select id="type" class="medium" name="fieldServer" onchange="fnSubmitRegis(this.value);">
										<option value="" selected="selected">Hãy chọn máy chủ</option>
<?php foreach ($gamesettings as $k => $v) {
	if (!in_array($k, array(3))) continue;
	echo '<option value="'.$k.'">'.$v['name'].'</option>';
} ?>
								</select>
							</div>
<?php } ?>
							<div class="field" style="display:none" id="selectcharacter">
								<label for="fieldCharacter">Chọn nhân vật </label>
								<select id="fieldCharacter" class="medium" name="fieldCharacter">
										<option value="" selected="selected">Hãy chọn nhân vật</option>
								</select>
							</div>
							<div class="field" style="display:block"><p class="field_help" id="charactername" style="font-size:14px; font-weight:bold"></p></div>
							<div class="field"><p class="field_help" style="font-size: 13px;">Lưu ý:<br>- Để chuyển nhân vật sang máy chủ khác cần có sẵn nhân vật tương ứng ở máy chủ đích để thay thế.<br>- Nhân vật muốn chuyển máy chủ cần phải thoát khỏi bang phái.<br>- Phải thoát nhân vật trước khi chuyển máy chủ.</p></div>
							
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
	
