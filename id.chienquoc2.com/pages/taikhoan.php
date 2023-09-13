<?php
	if (!isset($_SESSION['username'])) {
		$_SESSION['error_msg'] = 'Bạn cần phải đăng nhập mới có thể thực hiện được việc này';
		die(header('location: http://'.$_SERVER['SERVER_NAME']));
	}
	if (!empty($_POST)) {
		if ($_SESSION['security_code'] != strtolower($_POST['txtCaptcha']) || empty($_SESSION['security_code'])) {
			$error_msg = 'Mã số xác nhận không đúng.<br>Bạn hãy kiểm tra lại trước khi tiếp tục';
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
			$account = stripslashes($_POST['fieldAcount']);
			$fullname = stripslashes($_POST['fieldFullname']);
			$dob = stripslashes($_POST['fieldDob']);
			$gender = stripslashes($_POST['fieldGender']);
			$address = stripslashes($_POST['fieldAddress']);
			$city = stripslashes($_POST['fieldCity']);
			if ($_SESSION['fullcontrol'] == true) {
				$socialnumberold = stripslashes($_POST['fieldSocialNumberOld']);
				if ($socialnumberold != $pspuser['socialnumber']) {
					$error_msg = 'Số chứng minh nhân dân cũ không đúng, không thể thay đổi thông tin';
				}
			}
			if ($error_msg == '') {
				$sql_query = @mysql_query("UPDATE account SET fullname = '$fullname', dob = '$dob', gender = '$gender', address = '$address', city = '$city' WHERE account = '".$_SESSION['username']."' AND password = '".$_SESSION['password']."'");
			}
			if ($error_msg == '') {
				if ($sql_query) {
					if (!empty($_POST['fieldPassword'])) {
						$password = md5_plus($_POST['fieldPassword']);
						$sql_query = @mysql_query("UPDATE account SET password = '$password' WHERE account = '".$_SESSION['username']."' AND password = '".$_SESSION['password']."'");
						if ($sql_query) {
							foreach ($gamesettings as $k => $v) {
								$nusoap_client = new nusoap_client($v['auth_remote']['url'], true);
								$nusoap_client->call('set_passwd', array('domain_name' => 'chienquoc2.com', 'ws_username' => $v['auth_remote']['account'], 'ws_password' => $v['auth_remote']['password'], 'id' => $_SESSION['username'], 'passwd' => $_POST['fieldPassword']));
							}
							$_SESSION['password'] = $password;
						}
					}
					if ($error_msg == '') {
						if (!empty($_POST['fieldPassword2'])) {
							if (@mysql_num_rows(mysql_query("SELECT * FROM account WHERE account = '" . $_SESSION['username'] . "' AND password = '" . md5_plus($_POST['fieldPassword2']) . "'")) > 0) {
								$error_msg = 'Mật khẩu cấp 2 không được giống với mật khẩu cấp 1';
							}
						}
					}
					if ($error_msg == '') {
						if (!empty($_POST['fieldPassword2'])) {
							$password2 = stripslashes(md5($_SESSION['username'].$_POST['fieldPassword2'], true));
							if ($pspuser['password2'] == '' || $_SESSION['fullcontrol'] == true) {
								@mysql_query("UPDATE account SET password2 = '$password2' WHERE account = '".$_SESSION['username']."' AND password = '".$_SESSION['password']."'");
							}
						}
					}
					if ($error_msg == '') {
						if ($pspuser['email'] == '' || $_SESSION['fullcontrol'] == true) {
							$email = stripslashes($_POST['fieldEmail']);
							@mysql_query("UPDATE account SET email = '$email' WHERE account = '".$_SESSION['username']."' AND password = '".$_SESSION['password']."'");
						}
					}
					if ($error_msg == '') {
						if ($pspuser['socialnumber'] == '' || $_SESSION['fullcontrol'] == true) {
							$socialnumber = stripslashes($_POST['fieldSocialNumber']);
							if ($socialnumber == substr($pspuser['socialnumber'], 0, -4)."****") {
								$socialnumber = $pspuser['socialnumber'];
							}
							@mysql_query("UPDATE account SET socialnumber = '$socialnumber' WHERE account = '".$_SESSION['username']."' AND password = '".$_SESSION['password']."'");
						}
					}
					if ($error_msg == '') {
						if ($pspuser['phonenumber'] == '') {
							$phonenumber = stripslashes($_POST['fieldPhoneNumber']);
							@mysql_query("UPDATE account SET phonenumber = '$phonenumber' WHERE account = '".$_SESSION['username']."' AND password = '".$_SESSION['password']."'");
						}
					}
					if ($error_msg == '') {
						$info_msg = 'Cập nhật thông tin tài khoản thành công';
						$pspuser = $Database->DBFetch("SELECT * FROM account WHERE account = '".$_SESSION['username']."' AND password = '".$_SESSION['password']."'");
					}
				} else {
						$error_msg = 'Cập nhật thông tin tài khoản không thành công';
				}
			}
		}
	}
	if ($info_msg == '') {
		if (isset($_SESSION['null_socialnumber'])) {
			$error_msg = 'Bạn chưa cập nhật thông tin tài khoản.<br>Vui lòng bổ sung số điện thoại và số CMND để bảo vệ tài khoản';
			unset($_SESSION['null_socialnumber']);
		}
	} ?>
	<div id="masthead">
		
		<div class="content_pad">
			
			<h1 class="">Thông tin tài khoản</h1>
			
		</div> <!-- .content_pad -->
		
	</div> <!-- #masthead -->	
	
	<div id="content" class="xgrid">
		
		<div class="x9">
			
			
	<div class="accordion_container">	
				
				
				<h2 class="accordion_panel"><a href="#">Thông tin tài khoản</a></h2> 
	<div class="accordion_content"> 
		<div class="block"> 
			<form action="#" method="post" class="form label-inline uniform" id="updateinfo">
	
							<div class="field"><label for="fieldAcount">Tên đăng nhập </label> <input id="fieldAcount" name="fieldAcount" size="50" type="text" class="medium" maxlength="16" value="<?php echo $_SESSION['username']; ?>" disabled="disabled" /></div>				
							<div class="field"><label for="fieldPassword">Mật khẩu </label> <input id="fieldPassword" name="fieldPassword" size="50" type="password" class="medium" maxlength="30" placeholder="Nhập mật khẩu mới nếu bạn muốn thay đổi" /></div>
							<div class="field"><label for="fieldRePassword">Xác nhận mật khẩu </label> <input id="fieldRePassword" name="fieldRePassword" size="50" type="password" class="medium" maxlength="30" placeholder="Xác nhận lại mật khẩu mới của bạn" /></div>							
							<div class="field"><label for="fieldPassword2">Mật khẩu cấp 2 </label> <input id="fieldPassword2" name="fieldPassword2" size="50" type="password" class="medium" maxlength="30" placeholder="Nhập mật khẩu cấp 2 mới nếu bạn muốn thay đổi" <?php if (!empty($pspuser['password2']) && !$_SESSION['fullcontrol']) echo 'disabled="disabled"'; ?>/></div>
							<div class="field"><label for="fieldRePassword2">Xác nhận mật khẩu cấp 2 </label> <input id="fieldRePassword2" name="fieldRePassword2" size="50" type="password" class="medium" maxlength="30" placeholder="Xác nhận lại mật khẩu cấp 2 mới của bạn" <?php if (!empty($pspuser['password2']) && !$_SESSION['fullcontrol']) echo 'disabled="disabled"'; ?>/></div>							
							<div class="field"><label for="fieldEmail">Địa chỉ Email </label> <input id="fieldEmail" name="fieldEmail" size="50" type="text" class="medium" value="****<?php echo substr($pspuser['email'], 4); ?>" <?php if (!$_SESSION['fullcontrol']) echo 'disabled="disabled"'; ?> onchange="findNewUser(this.value, 'email');" /></div>
							
							<div class="field"><label for="fieldFullname">Họ và tên </label> <input id="fieldFullname" name="fieldFullname" size="50" type="text" class="medium" value="<?php echo $pspuser['fullname']; ?>" /></div>							
							<div class="field"><label for="fieldDob">Ngày sinh </label> <input id="datepicker" name="fieldDob" size="50" type="text" class="medium" readonly="readonly" value="<?php echo $pspuser['dob']; ?>" /> </div>							
							
							<div class="controlset field">
								<span class="label">Giới tính</span>								
								<div class="controlset-pad">
								<input name="fieldGender" id="radio1" value="1" type="radio" <?php if ($pspuser['gender'] == 1) echo 'checked="checked"'; ?> /> <label for="radio1">Nam</label><br />
								<input name="fieldGender" id="radio2" value="0" type="radio" <?php if ($pspuser['gender'] == 0) echo 'checked="checked"'; ?> /> <label for="radio2">Nữ</label><br />
							</div>
							</div>								
							
							<div class="field"><label for="fieldSocialNumber">Số chứng minh nhân dân </label> <input placeholder="Gồm 9 chữ số" id="fieldSocialNumber" name="fieldSocialNumber" size="50" type="text" maxlength="9" class="medium" value="<?php echo substr($pspuser['socialnumber'], 0, -4); ?>****" <?php if (!empty($pspuser['socialnumber']) && !$_SESSION['fullcontrol']) echo 'disabled="disabled"'; ?> maxlength="9"/> </div>							
							<?php if ($_SESSION['fullcontrol']) { ?>
							<div class="field"><label for="fieldSocialNumberOld">Nhập lại số CMND cũ</label> <input placeholder="Gồm 9 chữ số" id="fieldSocialNumberOld" name="fieldSocialNumberOld" size="50" type="text" maxlength="9" class="medium" value="" maxlength="9"/> </div>							
							<?php } ?>
							<div class="field"><label for="fieldPhoneNumber">Số di động </label> <input placeholder="Gồm 10 hoặc 11 chữ số" id="fieldPhoneNumber" name="fieldPhoneNumber" size="50" type="text" maxlength="11" class="medium" value="<?php echo $pspuser['phonenumber']; ?>" <?php if (!empty($pspuser['phonenumber'])) echo 'disabled="disabled"'; ?>/> </div>							
							<div class="field"><p class="field_help" style="font-size: 13px;">Để thay đổi số điện thoại, vui lòng dùng số điện thoại hiện tại soạn tin nhắn theo cú pháp:<br>- DV CQ2 SDT taikhoan sodienthoai<br>Và gửi đến 8185, cước phí mỗi tin nhắn là 1.000 VNĐ.</div>
							<div class="field"><label for="fieldAddress">Địa chỉ </label> <input id="fieldAddress" name="fieldAddress" size="50" type="text" class="medium" value="<?php echo $pspuser['address']; ?>" /> </div>
							
							<div class="field">
								<label for="type">Thành phố </label>
								<select id="type" class="medium" name="fieldCity">									
										 <option <?php if ($pspuser['city'] == 'Hà Nội') echo 'selected="selected"'; ?> value="Hà Nội">Hà Nội</option>
										 <option <?php if ($pspuser['city'] == 'Hồ Chí Minh') echo 'selected="selected"'; ?> value="Hồ Chí Minh">Hồ Chí Minh</option>
										 <option <?php if ($pspuser['city'] == 'An Giang') echo 'selected="selected"'; ?> value="An Giang">An Giang</option>
										 <option <?php if ($pspuser['city'] == 'Bà Rịa Vũng Tàu') echo 'selected="selected"'; ?> value="Bà Rịa Vũng Tàu">Bà Rịa Vũng Tàu</option>
										 <option <?php if ($pspuser['city'] == 'Bắc Cạn') echo 'selected="selected"'; ?> value="Bắc Cạn">Bắc Cạn</option>
										 <option <?php if ($pspuser['city'] == 'Bắc Giang') echo 'selected="selected"'; ?> value="Bắc Giang">Bắc Giang</option>
										 <option <?php if ($pspuser['city'] == 'Bạc Liêu') echo 'selected="selected"'; ?> value="Bạc Liêu">Bạc Liêu</option>
										 <option <?php if ($pspuser['city'] == 'Bắc Ninh') echo 'selected="selected"'; ?> value="Bắc Ninh">Bắc Ninh</option>
										 <option <?php if ($pspuser['city'] == 'Bến Tre') echo 'selected="selected"'; ?> value="Bến Tre">Bến Tre</option>
										 <option <?php if ($pspuser['city'] == 'Bình Định') echo 'selected="selected"'; ?> value="Bình Định">Bình Định</option>
										 <option <?php if ($pspuser['city'] == 'Bình Dương') echo 'selected="selected"'; ?> value="Bình Dương">Bình Dương</option>
										 <option <?php if ($pspuser['city'] == 'Bình Phước') echo 'selected="selected"'; ?> value="Bình Phước">Bình Phước</option>
										 <option <?php if ($pspuser['city'] == 'Bình Thuận') echo 'selected="selected"'; ?> value="Bình Thuận">Bình Thuận</option>
										 <option <?php if ($pspuser['city'] == 'Cà Mau') echo 'selected="selected"'; ?> value="Cà Mau">Cà Mau</option>
										 <option <?php if ($pspuser['city'] == 'Cần Thơ') echo 'selected="selected"'; ?> value="Cần Thơ">Cần Thơ</option>
										 <option <?php if ($pspuser['city'] == 'Cao Bằng') echo 'selected="selected"'; ?> value="Cao Bằng">	Cao Bằng</option>
										 <option <?php if ($pspuser['city'] == 'Đà Nẵn') echo 'selected="selected"'; ?> value="Đà Nẵng">Đà Nẵng</option>
										 <option <?php if ($pspuser['city'] == 'Đăk Lăk') echo 'selected="selected"'; ?> value="Đăk Lăk">Đăk Lăk</option>
										 <option <?php if ($pspuser['city'] == 'Điện Biên') echo 'selected="selected"'; ?> value="Điện Biên">Điện Biên</option>
										 <option <?php if ($pspuser['city'] == 'Đồng Nai') echo 'selected="selected"'; ?> value="Đồng Nai">Đồng Nai</option>
										 <option <?php if ($pspuser['city'] == 'Đồng Tháp') echo 'selected="selected"'; ?> value="Đồng Tháp">Đồng Tháp</option>
										 <option <?php if ($pspuser['city'] == 'Gia Lai') echo 'selected="selected"'; ?> value="Gia Lai">Gia Lai</option>
										 <option <?php if ($pspuser['city'] == 'Hà Giang') echo 'selected="selected"'; ?> value="Hà Giang">Hà Giang</option>
										 <option <?php if ($pspuser['city'] == 'Hạ Long') echo 'selected="selected"'; ?> value="Hạ Long">Hạ Long</option>
										 <option <?php if ($pspuser['city'] == 'Hà Nam') echo 'selected="selected"'; ?> value="Hà Nam">Hà Nam</option>
										 
										 <option <?php if ($pspuser['city'] == 'Hà Tây') echo 'selected="selected"'; ?> value="Hà Tây">Hà Tây</option>
										 <option <?php if ($pspuser['city'] == 'Hà Tĩnh') echo 'selected="selected"'; ?> value="Hà Tĩnh">Hà Tĩnh</option>
										 <option <?php if ($pspuser['city'] == 'Hải Dương') echo 'selected="selected"'; ?> value="Hải Dương">Hải Dương</option>
										 <option <?php if ($pspuser['city'] == 'Hải Phòng') echo 'selected="selected"'; ?> value="Hải Phòng">Hải Phòng</option>
										 
										 <option <?php if ($pspuser['city'] == 'Hòa Bình') echo 'selected="selected"'; ?> value="Hòa Bình">Hòa Bình</option>
										 <option <?php if ($pspuser['city'] == 'Hưng Yên') echo 'selected="selected"'; ?> value="Hưng Yên">Hưng Yên</option>
										 <option <?php if ($pspuser['city'] == 'Khánh Hòa') echo 'selected="selected"'; ?> value="Khánh Hòa">Khánh Hòa</option>
										 <option <?php if ($pspuser['city'] == 'Kiên Giang') echo 'selected="selected"'; ?> value="Kiên Giang">Kiên Giang</option>
										 <option <?php if ($pspuser['city'] == 'KomTum') echo 'selected="selected"'; ?> value="KomTum">KomTum</option>
										 <option <?php if ($pspuser['city'] == 'Lai Châu') echo 'selected="selected"'; ?> value="Lai Châu">Lai Châu</option>
										 <option <?php if ($pspuser['city'] == 'Lâm Đồng') echo 'selected="selected"'; ?> value="Lâm Đồng">Lâm Đồng</option>
										 <option <?php if ($pspuser['city'] == 'Lạng Sơn') echo 'selected="selected"'; ?> value="Lạng Sơn">Lạng Sơn</option>
										 <option <?php if ($pspuser['city'] == 'Lào Cai') echo 'selected="selected"'; ?> value="Lào Cai">Lào Cai</option>
										 <option <?php if ($pspuser['city'] == 'Long An') echo 'selected="selected"'; ?> value="Long An">Long An</option>
										 <option <?php if ($pspuser['city'] == 'Nam Định') echo 'selected="selected"'; ?> value="Nam Định">Nam Định</option>
										 <option <?php if ($pspuser['city'] == 'Nghệ An') echo 'selected="selected"'; ?> value="Nghệ An">Nghệ An</option>
										 <option <?php if ($pspuser['city'] == 'Ninh Bình') echo 'selected="selected"'; ?> value="Ninh Bình">Ninh Bình</option>
										 <option <?php if ($pspuser['city'] == 'Ninh Thuận') echo 'selected="selected"'; ?> value="Ninh Thuận">Ninh Thuận</option>
										 <option <?php if ($pspuser['city'] == 'Phú Thọ') echo 'selected="selected"'; ?> value="Phú Thọ">Phú Thọ</option>
										 <option <?php if ($pspuser['city'] == 'Phú Yên') echo 'selected="selected"'; ?> value="Phú Yên">Phú Yên</option>
										 <option <?php if ($pspuser['city'] == 'Quảng Bình') echo 'selected="selected"'; ?> value="Quảng Bình">Quảng Bình</option>
										 <option <?php if ($pspuser['city'] == 'Quảng Nam') echo 'selected="selected"'; ?> value="Quảng Nam">Quảng Nam</option>
										 <option <?php if ($pspuser['city'] == 'Quảng Ngãi') echo 'selected="selected"'; ?> value="Quảng Ngãi">Quảng Ngãi</option>
										 <option <?php if ($pspuser['city'] == 'Quảng Ninh') echo 'selected="selected"'; ?> value="Quảng Ninh">Quảng Ninh</option>
										 <option <?php if ($pspuser['city'] == 'Quảng Trị') echo 'selected="selected"'; ?> value="Quảng Trị">Quảng Trị</option>
										 <option <?php if ($pspuser['city'] == 'Sóc Trăng') echo 'selected="selected"'; ?> value="Sóc Trăng">Sóc Trăng</option>
										 <option <?php if ($pspuser['city'] == 'Sơn La') echo 'selected="selected"'; ?> value="Sơn La">Sơn La</option>
										 <option <?php if ($pspuser['city'] == 'Tây Ninh') echo 'selected="selected"'; ?> value="Tây Ninh">Tây Ninh</option>
										 <option <?php if ($pspuser['city'] == 'Thái Bình') echo 'selected="selected"'; ?> value="Thái Bình">Thái Bình</option>
										 <option <?php if ($pspuser['city'] == 'Thái Nguyên') echo 'selected="selected"'; ?> value="Thái Nguyên">Thái Nguyên</option>
										 <option <?php if ($pspuser['city'] == 'Thanh Hóa') echo 'selected="selected"'; ?> value="Thanh Hóa">Thanh Hóa</option>
										 <option <?php if ($pspuser['city'] == 'Thừa Thiên Huế') echo 'selected="selected"'; ?> value="Thừa Thiên Huế">Thừa Thiên Huế</option>
										 <option <?php if ($pspuser['city'] == 'Tiền Giang') echo 'selected="selected"'; ?> value="Tiền Giang">Tiền Giang</option>
										 <option <?php if ($pspuser['city'] == 'Trà Vinh') echo 'selected="selected"'; ?> value="Trà Vinh">Trà Vinh</option>
										 <option <?php if ($pspuser['city'] == 'Tuyên Quang') echo 'selected="selected"'; ?> value="Tuyên Quang">Tuyên Quang</option>
										 <option <?php if ($pspuser['city'] == 'Vĩnh Long') echo 'selected="selected"'; ?> value="Vĩnh Long">Vĩnh Long</option>
										 <option <?php if ($pspuser['city'] == 'Vĩnh Phúc') echo 'selected="selected"'; ?> value="Vĩnh Phúc">Vĩnh Phúc</option>
										 <option <?php if ($pspuser['city'] == 'Yên Bái') echo 'selected="selected"'; ?> value="Yên Bái">Yên Bái</option>
										 <option <?php if ($pspuser['city'] == 'Nơi khác') echo 'selected="selected"'; ?> value="Nơi khác">Nơi khác</option>			
								</select>
							</div>							
							
							<div class="field"><label for="txtCaptcha">Nhập mã xác nhận</label>                            
                            <input type="hidden" name="CAPTCHA_Postback" id="CAPTCHA_Postback" value="true" />
                           		<div style="float:left"><input id="txtCaptcha" name="txtCaptcha" size="6" type="text" class="txtCapcha" maxlength="6" /></div><div style="float:left"><a onclick="reloadCapcha();" href="javascript:void(0);"><img id="imgmCaptcha" src="secureimage.php?<?php echo time(); ?>" style="border-width:0px; width:120px; height:50px" /></a></div>
                            </div>
                            
							<br />
							<div class="buttonrow">
                            	<input type="hidden" value="<?php echo $_SESSION['username']; ?>" name="virtualForm" />
								<input id="validate" class="btn" type="submit" name="sigup" value="Cập nhật thông tin tài khoản">
								<input id="reset" class="btn btn-black" name="reset" type="reset" value="Reset">
							</div>

						</form>
		</div> 
	</div> 
	
	 
</div> <!-- .accordion_container -->

		</div> <!-- .x9 -->
		
		
		<div class="x3">
		
			<p><img src="images/teaser.png" /></p>
			
		</div> <!-- .x3 -->
		
	</div> <!-- #content -->
    
    
	
