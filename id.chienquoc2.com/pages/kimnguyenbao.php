<?php
	if (!isset($_SESSION['username'])) {
		$_SESSION['error_msg'] = 'Bạn cần phải đăng nhập mới có thể thực hiện được việc này';
		die(header('location: http://'.$_SERVER['SERVER_NAME']));
	}
	if ($pspuser['socialnumber'] == '') {
		$_SESSION['null_socialnumber'] = 1;
		die(header('location: http://'.$_SERVER['SERVER_NAME'].'/?page=taikhoan'));
	}
	if ($can_recharge != true) {
		$_SESSION['error_msg'] = 'Hệ thống thanh toán tạm đóng để bảo trì';
		die(header('location: http://'.$_SERVER['SERVER_NAME']));
	}
	$recharge_bonus = $Database->DBFetch("SELECT * FROM `recharge_bonus`");
	$bonus_rate = $recharge_bonus['bonus_rate'];
	$bonus_start_time = strtotime($recharge_bonus['start_date']);
	$bonus_end_time = strtotime($recharge_bonus['end_date']);
	if (!empty($_POST)) {
		if ($error_msg == '') {
			if ($_SESSION['security_code'] != strtolower($_POST['txtCaptcha']) || empty($_SESSION['security_code'])) {
				$error_msg = 'Chuỗi mã xác nhận không đúng';
			}
			unset($_SESSION['security_code']);
		}
		if ($error_msg == '') {
			if (!isset($gamesettings[$_POST['fieldServer']])) {
				$error_msg = 'Server không tồn tại';
			}
		}
		if ($error_msg == '') {
			if ($gamesettings[$_POST['fieldServer']]['can_recharge'] != 1) {
				$error_msg = 'Không thể chuyển xu vào server này';
			}
		}
		if ($error_msg == '') {
			$trade_coin = intval(stripslashes($_POST['fieldXu']));
			if ($trade_coin < 1) {
				$error_msg = 'Xảy ra lỗi, vui lòng thử lại sau';
			}
		}
		if ($error_msg == '') {
			$pspuser = $Database->DBFetch("SELECT * FROM account WHERE account = '".$_SESSION['username']."' AND password = '".$_SESSION['password']."'");
			if ($pspuser['coin'] < $trade_coin) {
				$error_msg = 'Số dư tài khoản không đủ để thực hiện giao dịch';
			} else {
				if (time() >= $bonus_start_time && time() <= $bonus_end_time) {
					$bonus_yuanbao = ($trade_coin/100*50*10)/100*$bonus_rate;
				}
//				if ($_POST['fieldServer'] == 2 && time() >= strtotime('2016-07-20 00:00') && time() <= strtotime('2016-07-27 24:00')) {
//					$bonus_yuanbao = ($trade_coin/100*50*10)/100*20;
//				}
				@mysql_query("UPDATE account SET coin = '".($pspuser['coin']-$trade_coin)."' WHERE account = '".$_SESSION['username']."' AND password = '".$_SESSION['password']."'");
				$nusoap_client = new nusoap_client($gamesettings[$_POST['fieldServer']]['auth_remote']['url'], true);
				$result = $nusoap_client->call('add_yuanbao', array('domain_name' => 'chienquoc2.com', 'ws_username' => $gamesettings[$_POST['fieldServer']]['auth_remote']['account'], 'ws_password' => $gamesettings[$_POST['fieldServer']]['auth_remote']['password'], 'id' => $_SESSION['username'], 'yuanbao' => ($trade_coin/100*50*10) + $bonus_yuanbao));
//				if ($result == 1) {
					$nusoap_client->call('add_premium_point', array('domain_name' => 'chienquoc2.com', 'ws_username' => $gamesettings[$_POST['fieldServer']]['auth_remote']['account'], 'ws_password' => $gamesettings[$_POST['fieldServer']]['auth_remote']['password'], 'id' => $_SESSION['username'], 'point' => ($trade_coin/10)));
					$nusoap_client->call('add_event1_point', array('domain_name' => 'chienquoc2.com', 'ws_username' => $gamesettings[$_POST['fieldServer']]['auth_remote']['account'], 'ws_password' => $gamesettings[$_POST['fieldServer']]['auth_remote']['password'], 'id' => $_SESSION['username'], 'point' => round($trade_coin/200, 0, PHP_ROUND_HALF_DOWN)));
					$info_msg = 'Giao dịch thành công';
					$pspuser = $Database->DBFetch("SELECT * FROM account WHERE account = '".$_SESSION['username']."'");
					$status = 'OK';
//				} else {
//					$error_msg = 'Giao dịch không thành công';
//					$status = 'Cancel';
//				}
				if ($status == 'OK') {
					@mysql_query("INSERT INTO trade_history (account, coin, received, server, status, time) values ('".$_SESSION['username']."', '".$trade_coin."', '".(($trade_coin/100*50)+($bonus_yuanbao/10))."', '".stripslashes($gamesettings[$_POST['fieldServer']]['name'])."', '".$status."', '".date('d/m/Y H:i:s')."')");
					if (@mysql_num_rows(@mysql_query("SELECT * FROM `luckywheel` WHERE `account` = '" . $_SESSION['username'] . "'")) == 0) {
						@mysql_query("INSERT INTO `luckywheel` (`account`, `free_spin_num`) VALUES ('".$_SESSION['username']."', '" . floor($trade_coin / 200) . "')");
					} else {
						@mysql_query("UPDATE `luckywheel` SET `free_spin_num` = `free_spin_num`+'" . floor($trade_coin / 200) . "' WHERE `account` = '" . $_SESSION['username'] . "'");
					}
				}
			}
		}
	} ?>
	<div id="masthead">
		
		<div class="content_pad">
			
			<h1 class="">Đổi Kim Bảo</h1>
			
		</div> <!-- .content_pad -->
		
	</div> <!-- #masthead -->	
	
	<div id="content" class="xgrid">
		
		<div class="x8">
			
			<form action="#" method="post" class="form label-inline uniform" name="Xu2KNB" id="Xu2KNB">
						<h3>Thông tin tài khoản</h3>
							<div class="field"><label for="fname">Tên tài khoản </label> <p class="field_info"><?php echo $_SESSION['username']; ?></p></div>				
							<div class="field"><label for="lname">Số dư tài khoản </label><p class="field_info"><?php echo intval($pspuser['coin']); ?> Xu</p></div>
						<h3>Chuyển tiền</h3>
							<div class="field">
								<label for="type">Số Xu muốn chuyển</label>
								<select id="type" class="medium" name="fieldXu">
										<option value="100" selected="selected">100 Xu = 50 Kim Bảo</option>
										<option value="200">200 Xu = 100 Kim Bảo</option>
										<option value="300">300 Xu = 150 Kim Bảo</option>
										<option value="500">500 Xu = 250 Kim Bảo</option>
										<option value="1000">1000 Xu = 500 Kim Bảo</option>
										<option value="2000">2000 Xu = 1000 Kim Bảo</option>
										<option value="3000">3000 Xu = 1500 Kim Bảo</option>
										<option value="5000">5000 Xu = 2500 Kim Bảo</option>
								</select>							
							</div>
							<div class="field"><p class="field_help" style="font-size: 13px;">Tỷ lệ quy đổi: 10 Xu nhận được 5 Kim Bảo<?php if (time() >= $bonus_start_time && time() <= $bonus_end_time) { ?><br>Tăng thêm <?php echo $bonus_rate; ?>% giá trị chuyển đổi xu vào Chiến Quốc từ <?php echo date('H:i d/m/Y', $bonus_start_time); ?> đến <?php echo date('H:i d/m/Y', $bonus_end_time); ?><?php } ?></p></div>
						<h3>Lựa chọn máy chủ</h3>
							<div class="field">
								<label for="type">Chuyển vào Máy chủ </label>
								<select id="type" class="medium" name="fieldServer" onchange="fnSubmitRegis(this.value, 'napthe');">
										<option value="" selected="selected">Hãy chọn máy chủ nhận tiền</option>
<?php foreach ($gamesettings as $k => $v) {
	if ($v['can_recharge'] == 0) continue;
    echo '																		
										<option value="'.$k.'">'.$v['name'].'</option>
								
';
} ?>
								</select>
							</div>
							<div class="field" style="display:block"><p class="field_help" id="charactername" style="font-size:14px; font-weight:bold"></p></div>
							
							<div class="field"><label for="txtCaptcha">Nhập mã xác nhận</label>                            
                            <input type="hidden" name="CAPTCHA_Postback" id="CAPTCHA_Postback" value="true" />
                           		<div style="float:left"><input id="txtCaptcha" name="txtCaptcha" size="6" type="text" class="txtCapcha" maxlength="6" /></div><div style="float:left"><a onclick="reloadCapcha();" href="javascript:void(0);"><img id="imgmCaptcha" src="secureimage.php?<?php echo time(); ?>" style="border-width:0px; width:120px; height:50px" /></a></div>
                            </div>
                            
							<br />
							<div class="buttonrow" id="submitbutton" style="display:none">
								<input id="validate" class="btn" type="submit" name="sigup" value="Đổi Xu">
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
	
