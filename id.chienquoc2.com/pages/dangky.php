<?php
	if (isset($_SESSION['username'])) {
		die(header('location: http://'.$_SERVER['SERVER_NAME']));
	}
	if (!empty($_POST)) {
		if ($_SESSION['security_code'] != strtolower($_POST['txtCaptcha']) || empty($_SESSION['security_code'])) {
			$error_msg = 'Mã số xác nhận không đúng.<br>Bạn hãy kiểm tra lại trước khi tiếp tục';
		}
		if ($error_msg == '') {
			$account = strtolower(stripslashes($_POST['fieldAcount']));
			$password = md5_plus($_POST['fieldPassword']);
			$email = stripslashes($_POST['fieldEmail']);
			$fullname = stripslashes($_POST['fieldFullname']);
			$dob = stripslashes($_POST['fieldDob']);
			$gender = stripslashes($_POST['fieldGender']);
			$socialnumber = stripslashes($_POST['fieldSocialNumber']);
			$phonenumber = stripslashes($_POST['fieldPhoneNumber']);
			$address = stripslashes($_POST['fieldAddress']);
			$city = stripslashes($_POST['fieldCity']);
		}
		if ($error_msg == '') {
			if (preg_match ('/[^A-z0-9]/', $account) != 0) {
				$error_msg = 'Tài khoản không hợp lệ. Tài khoản có chứa từ hoặc cụm từ bị cấm.';
			}
		}
		if ($error_msg == '') {
			if (strlen($account) < 4) {
				$error_msg = 'Tài khoản không hợp lệ, tài khoản phải có từ 4 - 10 ký tự.';
			}
		}
		if ($error_msg == '') {
			if (@mysql_num_rows(mysql_query("SELECT * FROM account WHERE account = '".$account."'")) > 0) {
				$error_msg = 'Tài khoản đã có người sử dụng.';
			}
		}
		if ($error_msg == '') {
			if (@mysql_num_rows(mysql_query("SELECT * FROM account WHERE email = '".$email."'")) > 0) {
				$error_msg = 'Email đã có người sử dụng.';
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
			$sql_query = @mysql_query("INSERT INTO account (account, password, email, fullname, dob, gender, socialnumber, phonenumber, address, city, reg_time) values ('$account', '$password', '$email', '$fullname', '$dob', '$gender', '$socialnumber', '$phonenumber', '$address', '$city', '".time()."')");
			if ($sql_query) {
				foreach ($gamesettings as $k => $v) {
					$nusoap_client = new nusoap_client($v['auth_remote']['url'], true);
					$nusoap_client->call('new_user', array('domain_name' => 'chienquoc2.com', 'ws_username' => $v['auth_remote']['account'], 'ws_password' => $v['auth_remote']['password'], 'id' => $account, 'passwd' => $_POST['fieldPassword']));
				}
				$_SESSION['last_register'] = $account;
				$_SESSION['username'] = $account;
				$_SESSION['password'] = $password;
				$_SESSION['logintime'] = time();
				die(header('location: http://'.$_SERVER['SERVER_NAME']));
			}
		}
	}
	?>
	<div id="masthead">
		
		<div class="content_pad">
			
			<h1 class="">Đăng ký tài khoản</h1>
			
		</div> <!-- .content_pad -->
		
	</div> <!-- #masthead -->	
	<div id="content" class="xgrid">
		
		<div class="x12">
			
			<form action="#" method="post" class="form label-inline uniform" id="formtest" name="formtest">
			
            <h3>Thông tin bắt buộc</h3>                       
							<div class="field"><label for="fieldAcount">Tên đăng nhập </label> <input id="fieldAcount" name="fieldAcount" size="50" type="text" class="medium" maxlength="16" value="" placeholder="Từ 6 - 16 ký tự, không dùng ký tự đặc biệt" onchange="findNewUser(this.value, 'id');" /></div>
							<div class="field"><label for="fieldPassword">Mật khẩu </label> <input id="fieldPassword" name="fieldPassword" size="50" type="password" class="medium" maxlength="30" placeholder="Bao gồm từ 6 - 30 ký tự" /></div>
							<div class="field"><label for="fieldRePassword">Xác nhận mật khẩu </label> <input id="fieldRePassword" name="fieldRePassword" size="50" type="password" class="medium" maxlength="30" placeholder="Nhập lại chính xác mật khẩu của bạn" /></div>							
							<div class="field"><label for="fieldEmail">Địa chỉ Email </label> <input id="fieldEmail" name="fieldEmail" size="50" type="text" class="medium" value="" onchange="findNewUser(this.value, 'email');" /></div>
							<div class="field"><p class="field_help"></p></div>
            <h3>Thông tin không bắt buộc</h3>														
							<div class="field"><label for="fieldFullname">Họ và tên </label> <input id="fieldFullname" name="fieldFullname" size="50" type="text" class="medium" value="" /></div>							
							<div class="field"><label for="fieldDob">Ngày sinh </label> <input id="datepicker" name="fieldDob" size="50" type="text" class="medium" readonly="readonly" value="" /> </div>							
							
							<div class="controlset field">
								<span class="label">Giới tính</span>								
								<div class="controlset-pad">
								<input name="fieldGender" id="radio1" value="1" type="radio" checked="checked" /> <label for="radio1">Nam</label><br />
								<input name="fieldGender" id="radio2" value="0" type="radio" /> <label for="radio2">Nữ</label><br />
							</div>
							</div>								
							
							<div class="field"><label for="fieldSocialNumber">Số chứng minh nhân dân </label> <input id="fieldSocialNumber" name="fieldSocialNumber" size="50" type="text" class="medium" value="" maxlength="9"/> </div>							
							<div class="field"><label for="fieldPhoneNumber">Số di động </label> <input id="fieldPhoneNumber" name="fieldPhoneNumber" size="50" type="text" class="medium" value="" /> </div>							
							<div class="field"><label for="fieldAddress">Địa chỉ </label> <input id="fieldAddress" name="fieldAddress" size="50" type="text" class="medium" value="" /> </div>
							
							<div class="field">
								<label for="type">Thành phố </label>
								<select id="type" class="medium" name="fieldCity">
										 <option  value="Hà Nội">Hà Nội</option>
										 <option  value="Hồ Chí Minh">Hồ Chí Minh</option>
										 <option  value="An Giang">An Giang</option>
										 <option  value="Bà Rịa Vũng Tàu">Bà Rịa Vũng Tàu</option>
										 <option  value="Bắc Cạn">Bắc Cạn</option>
										 <option  value="Bắc Giang">Bắc Giang</option>
										 <option  value="Bạc Liêu">Bạc Liêu</option>
										 <option  value="Bắc Ninh">Bắc Ninh</option>
										 <option  value="Bến Tre">Bến Tre</option>
										 <option  value="Bình Định">Bình Định</option>
										 <option  value="Bình Dương">Bình Dương</option>
										 <option  value="Bình Phước">Bình Phước</option>
										 <option  value="Bình Thuận">Bình Thuận</option>
										 <option  value="Cà Mau">Cà Mau</option>
										 <option  value="Cần Thơ">Cần Thơ</option>
										 <option  value="Cao Bằng">	Cao Bằng</option>
										 <option  value="Đà Nẵng">Đà Nẵng</option>
										 <option  value="Đăk Lăk">Đăk Lăk</option>
										 <option  value="Điện Biên">Điện Biên</option>
										 <option  value="Đồng Nai">Đồng Nai</option>
										 <option  value="Đồng Tháp">Đồng Tháp</option>
										 <option  value="Gia Lai">Gia Lai</option>
										 <option  value="Hà Giang">Hà Giang</option>
										 <option  value="Hạ Long">Hạ Long</option>
										 <option  value="Hà Nam">Hà Nam</option>
										 <option  value="Hà Tây">Hà Tây</option>
										 <option  value="Hà Tĩnh">Hà Tĩnh</option>
										 <option  value="Hải Dương">Hải Dương</option>
										 <option  value="Hải Phòng">Hải Phòng</option>
										 <option  value="Hòa Bình">Hòa Bình</option>
										 <option  value="Hưng Yên">Hưng Yên</option>
										 <option  value="Khánh Hòa">Khánh Hòa</option>
										 <option  value="Kiên Giang">Kiên Giang</option>
										 <option  value="KomTum">KomTum</option>
										 <option  value="Lai Châu">Lai Châu</option>
										 <option  value="Lâm Đồng">Lâm Đồng</option>
										 <option  value="Lạng Sơn">Lạng Sơn</option>
										 <option  value="Lào Cai">Lào Cai</option>
										 <option  value="Long An">Long An</option>
										 <option  value="Nam Định">Nam Định</option>
										 <option  value="Nghệ An">Nghệ An</option>
										 <option  value="Ninh Bình">Ninh Bình</option>
										 <option  value="Ninh Thuận">Ninh Thuận</option>
										 <option  value="Phú Thọ">Phú Thọ</option>
										 <option  value="Phú Yên">Phú Yên</option>
										 <option  value="Quảng Bình">Quảng Bình</option>
										 <option  value="Quảng Nam">Quảng Nam</option>
										 <option  value="Quảng Ngãi">Quảng Ngãi</option>
										 <option  value="Quảng Ninh">Quảng Ninh</option>
										 <option  value="Quảng Trị">Quảng Trị</option>
										 <option  value="Sóc Trăng">Sóc Trăng</option>
										 <option  value="Sơn La">Sơn La</option>
										 <option  value="Tây Ninh">Tây Ninh</option>
										 <option  value="Thái Bình">Thái Bình</option>
										 <option  value="Thái Nguyên">Thái Nguyên</option>
										 <option  value="Thanh Hóa">Thanh Hóa</option>
										 <option  value="Thừa Thiên Huế">Thừa Thiên Huế</option>
										 <option  value="Tiền Giang">Tiền Giang</option>
										 <option  value="Trà Vinh">Trà Vinh</option>
										 <option  value="Tuyên Quang">Tuyên Quang</option>
										 <option  value="Vĩnh Long">Vĩnh Long</option>
										 <option  value="Vĩnh Phúc">Vĩnh Phúc</option>
										 <option  value="Yên Bái">Yên Bái</option>
										 <option  value="Nơi khác">Nơi khác</option>			
								</select>
							</div>							
							
							<div class="field"><label for="txtCaptcha">Nhập mã xác nhận</label>                            
                            <input type="hidden" name="CAPTCHA_Postback" id="CAPTCHA_Postback" value="true" />
                           		<div style="float:left"><input id="txtCaptcha" name="txtCaptcha" size="6" type="text" class="txtCapcha" maxlength="6" /></div><div style="float:left"><a onclick="reloadCapcha();" href="javascript:void(0);"><img id="imgmCaptcha" src="secureimage.php?<?php echo time(); ?>" style="border-width:0px; width:120px; height:50px" /></a></div>
                            </div>
                                                        
							<br />
							<div class="buttonrow">
								<input id="validate" class="btn" type="submit" name="sigup" value="Đăng ký tài khoản">
								<input id="reset" class="btn btn-black" name="reset" type="button" value="Reset">
							</div>

						</form>
			
		</div> <!-- .x12 -->
		
	</div> <!-- #content -->


	
