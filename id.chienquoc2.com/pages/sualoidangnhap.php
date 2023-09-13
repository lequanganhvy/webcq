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
				$error_msg = "Vui lòng chọn máy chủ có nhân vật cần sửa lỗi!";
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
				$error_msg = 'Bạn chưa chọn nhân vật cần sửa lỗi!';
			}
		}
		if ($error_msg == '') {
			$result = @file_get_contents('http://'.$gamesettings[$server_id]['host'].'/gm/?online');
			$data = json_decode($result, true);
			if (in_array($_SESSION['username'], $data)) {
				$error_msg = 'Nhân vật đã chọn đang trực tuyến, không thể tiến hành sửa lỗi';
			}
		}
		if ($error_msg == '') {
			$result = @file_get_contents('http://'.$gamesettings[$server_id]['host'].'/userdb.php?id='.$_SESSION['username'].'&mod=fix_stuck&i='.$_POST['fieldCharacter']);
			if ($result == '1') {
				$info_msg = 'Đã xử lý xong, vui lòng đăng nhập lại để kiểm tra';
			} else {
				$error_msg = 'Xảy ra lỗi, vui lòng liên hệ với bộ phận hỗ trợ khách hàng để được giúp đỡ';
			}
		}
	} ?>
	<div id="masthead">
		
		<div class="content_pad">
			
			<h1 class="">Sửa lỗi đăng nhập</h1>
			
		</div> <!-- .content_pad -->
		
	</div> <!-- #masthead -->	
	
	<div id="content" class="xgrid">
		
		<div class="x8">
			
			<form action="#" method="post" class="form label-inline uniform" name="Xu2KNB" id="Xu2KNB">
						<h3>Thông tin tài khoản</h3>
							<div class="field"><label for="fname">Tên tài khoản </label> <p class="field_info"><?php echo $_SESSION['username']; ?></p></div>				
						<h3>Lựa chọn máy chủ</h3>
							<div class="field">
								<label for="type">Chọn Máy chủ </label>
								<select id="type" class="medium" name="fieldServer" onchange="fnSubmitRegis(this.value);">
										<option value="" selected="selected">Hãy chọn máy chủ</option>
<?php foreach ($gamesettings as $k => $v) {
    echo '																		
										<option value="'.$k.'">'.$v['name'].'</option>
								
';
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
							<div class="field"><p class="field_help" style="font-size: 13px;">Lưu ý:<br>- Thực hiện sửa lỗi đăng nhập sẽ xóa toàn bộ danh sách bạn bè của bạn.<br>- Vị trí nhân vật sẽ tự động tái thiết lập về Thiên Đàn Tân Thủ Thôn hoặc Chu Quốc.<br>- Các phím nóng đã thiết lập (Kỹ năng, vật phẩm, ...) sẽ trở về mặc định.</p></div>
							
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
	
