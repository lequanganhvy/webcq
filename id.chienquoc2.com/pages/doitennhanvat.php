<?php
	if (!isset($_SESSION['username'])) {
		$_SESSION['error_msg'] = 'Bạn cần phải đăng nhập mới có thể thực hiện được việc này';
		die(header('location: http://'.$_SERVER['SERVER_NAME']));
	}
	if (!empty($_POST)) {
		if ($_SESSION['security_code'] != strtolower($_POST['txtCaptcha']) || empty($_SESSION['security_code'])) {
			$error_msg = 'Chuỗi mã xác nhận không đúng';
		}
		if ($error_msg == '') {
			if ($_POST['fieldServer'] == '' || $_POST['fieldServer'] == 0) {
				$error_msg = "Vui lòng chọn máy chủ có nhân vật cần đổi tên!";
			}
			$server_id = intval($_POST['fieldServer']);
		}
		if ($error_msg == '') {
			if (!@fsockopen($gamesettings[$server_id]['host'], 80, $errno, $errstr, 30)) {
				$error_msg = "Máy chủ đã chọn không tồn tại hoặc đang bảo trì!";
			}
		}
		if ($error_msg == '') {
			if ($_POST['fieldCharacter'] < 1) {
				$error_msg = 'Bạn chưa chọn nhân vật sẽ đổi tên!';
			}
		}
		if ($error_msg == '') {
			if ($pspuser['coin'] < 2000) {
				$error_msg = 'Số xu trong tài khoản của bạn không đủ để thực hiện việc này!';
			}
		}
		if ($error_msg == '') {
			$name = stripslashes($_POST['txtName']);
			if (strlen($name) < 2 || strlen($name) > 12) {
				$error_msg = 'Tên nhân vật tối thiểu phải có 2 ký tự và tối đa 12 ký tự';
			}
		}
		if ($error_msg == '') {
			$legal_name = @file_get_contents('http://'.$gamesettings[$server_id]['host'].'/namedb.php?legal');
			$array = array();
			for ($i = 0, $size = mb_strlen($legal_name, 'utf-8'); $i < $size; $i++) $array[] = mb_substr($legal_name, $i, 1, 'utf-8');
			for ($i = 0, $size = mb_strlen($name, 'utf-8'); $i < $size; $i++) {
				if (!in_array(mb_strtolower(mb_substr($name, $i, 1, 'utf-8'), 'utf-8'), $array)) {
					$error_msg = 'Tên nhân vật có chứa ký tự không hợp lệ';
					break;
				}
			}
		}
		if ($error_msg == '') {
			$online = json_decode(@preg_replace('/[\x00-\x1F\x80-\xFF]/', '', @file_get_contents('http://'.$gamesettings[$server_id]['host'].'/gm/?online')), true);
			if (count($online) > 0 && in_array($_SESSION['username'], $online) == true) {
				$error_msg = 'Nhân vật đã chọn đang trực tuyến, không thể tiến hành đổi tên';
			}
		}
		if ($error_msg == '') {
			$userdb = json_decode(@preg_replace('/[\x00-\x1F\x80-\xFF]/', '', @file_get_contents('http://'.$gamesettings[$server_id]['host'].'/userdb.php?id='.$_SESSION['username'])), true);
			if (!isset($userdb[$_POST['fieldCharacter']])) {
				$error_msg = 'Nhân vật không tồn tại!';
			}
		}
		if ($error_msg == '') {
			$namedb = json_decode(@preg_replace('/[\x00-\x1F\x80-\xFF]/', '', @file_get_contents('http://'.$gamesettings[$server_id]['host'].'/namedb.php')), true);
			if (!is_array($namedb)) {
				$error_msg = 'Lỗi hệ thống, vui lòng thử lại';
			}
		}
		if ($error_msg == '') {
			if (in_array($name, $namedb, true) == true && in_array($name, $userdb[$_POST['fieldCharacter']]['SubNames']) == false && $name != $userdb[$_POST['fieldCharacter']]['RealName']) {
				$error_msg = 'Tên nhân vật đã có người sử dụng';
			}
		}
		if ($error_msg == '') {
			if ($userdb[$_POST['fieldCharacter']]['OrgName'] != '') {
				$error_msg = 'Nhân vật muốn đổi tên trước tiên phải thoát khỏi bang phái.';
			}
		}
		if ($error_msg == '') {
			$pspuser['coin'] -= 2000;
			@mysql_query("UPDATE account SET coin = '" . $pspuser['coin'] . "' WHERE account = '" . $_SESSION['username'] . "' AND password = '" . $_SESSION['password'] . "'");
			if (@mysql_num_rows(@mysql_query("SELECT * FROM `luckywheel` WHERE `account` = '" . $_SESSION['username'] . "'")) == 0) {
				@mysql_query("INSERT INTO `luckywheel` (`account`, `free_spin_num`) VALUES ('".$_SESSION['username']."', '10')");
			} else {
				@mysql_query("UPDATE `luckywheel` SET `free_spin_num` = `free_spin_num`+'10' WHERE `account` = '" . $_SESSION['username'] . "'");
			}
			$result = @file_get_contents('http://'.$gamesettings[$server_id]['host'].'/userdb.php?id='.$_SESSION['username'].'&mod=change_name&name=' . $name . '&i='.$_POST['fieldCharacter']);
			if ($result == '1') {
				$userdb = json_decode(@preg_replace('/[\x00-\x1F\x80-\xFF]/', '', @file_get_contents('http://'.$gamesettings[$server_id]['host'].'/userdb.php?id='.$_SESSION['username'])), true);
				$info_msg = 'Đã đổi tên nhân vật của bạn thành ' . $userdb[$_POST['fieldCharacter']]['Name'];
				unset($_POST['txtName']);
			} else if ($result == '-1') {
				$error_msg = 'Tên nhân vật tối thiểu phải có 2 ký tự và tối đa 12 ký tự';
			} else if ($result == '-2') {
				$error_msg = 'Tên nhân vật có chứa ký tự không hợp lệ';
			} else if ($result == '-3') {
				$error_msg = 'Tên nhân vật đã có người sử dụng';
			} else {
				$error_msg = 'Xảy ra lỗi, vui lòng liên hệ với bộ phận hỗ trợ khách hàng để được giúp đỡ';
			}
			$logcontent = @file_get_contents("logs/log_character_rename.txt");
			$logcontent .= date("Y-m-d H:i:s") . " " . $_SESSION['username'] . " đổi tên thành " . $name . ". Result: " . $result . "\n";
			@file_put_contents("logs/log_character_rename.txt", $logcontent);
		}
	} ?>
	<div id="masthead">
		
		<div class="content_pad">
			
			<h1 class="">Đổi tên nhân vật</h1>
			
		</div> <!-- .content_pad -->
		
	</div> <!-- #masthead -->	
	
	<div id="content" class="xgrid">
		
		<div class="x8">
			
			<form action="#" method="post" class="form label-inline uniform" name="Xu2KNB" id="Xu2KNB">
						<h3>Thông tin tài khoản</h3>
							<div class="field"><label for="fname">Tên tài khoản </label> <p class="field_info"><?php echo $_SESSION['username']; ?></p></div>				
						<h3>Nhập tên muốn đổi</h3>
							<div class="field"><label for="txtCaptcha">Tên mới </label>                            
                           		<div style="float:left"><input id="txtName" name="txtName" type="text" class="txtName" value="<?php echo $_POST['txtName']; ?>" /></div>
							</div>
						<h3>Lựa chọn máy chủ</h3>
							<div class="field">
								<label for="type">Chọn Máy chủ </label>
								<select id="type" class="medium" name="fieldServer" onchange="fnSubmitRegis(this.value);">
										<option value="" selected="selected">Hãy chọn máy chủ</option>
<?php foreach ($gamesettings as $k => $v) {
	echo '<option value="'.$k.'">'.$v['name'].'</option>';
} ?>
								</select>
							</div>
							<div class="field" style="display:none" id="selectcharacter">
								<label for="fieldCharacter">Chọn nhân vật </label>
								<select id="fieldCharacter" class="medium" name="fieldCharacter">
										<option value="" selected="selected">Hãy chọn nhân vật</option>
								</select>
							</div>
							<div class="field" style="display:block"><p class="field_help" id="charactername" style="font-size:14px; font-weight:bold"></p></div>
							<div class="field"><p class="field_help" style="font-size: 13px;">Lưu ý:<br>- Thực hiện đổi tên nhân vật sẽ tiêu hao 2000 xu trong tài khoản.<br>- Nhân vật muốn đổi tên cần phải thoát khỏi bang phái.<br>- Phải thoát nhân vật trước khi đổi tên.</p></div>
							
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
	
