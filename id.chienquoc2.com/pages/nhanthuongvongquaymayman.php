<?php
	if (!isset($_SESSION['username'])) {
		$_SESSION['error_msg'] = 'Bạn cần phải đăng nhập mới có thể thực hiện được việc này';
		die(header('location: http://'.$_SERVER['SERVER_NAME']));
	}
	$count = 0;
	foreach ($luckywheelsettings as $server_id => $settings) {
		if (time() > strtotime($settings['end_time2'])) continue;
		$count++;
	}
	if ($count == 0) {
		$_SESSION['error_msg'] = 'Thời gian nhận thưởng đã kết thúc';
		die(header('location: http://'.$_SERVER['SERVER_NAME']));
	}
	if (!empty($_GET['server']) && !isset($luckywheelsettings[$_GET['server']])) {
		$_SESSION['error_msg'] = 'Không có thông tin về hoạt động Vòng Quay May Mắn ở máy chủ này';
		die(header('location: http://'.$_SERVER['SERVER_NAME']));
	}
	if (!empty($_POST)) {
		if ($_SESSION['security_code'] != strtolower($_POST['txtCaptcha']) || empty($_SESSION['security_code'])) {
			$error_msg = 'Chuỗi mã xác nhận không đúng';
		}
		if ($error_msg == '') {
			if ($_GET['server'] == '' || $_GET['server'] == 0) {
				$error_msg = "Vui lòng chọn máy chủ sẽ nhận thưởng!";
			}
			$server_id = intval($_GET['server']);
		}
		if ($error_msg == '') {
			if (!@fsockopen($gamesettings[$server_id]['host'], 80, $errno, $errstr, 30)) {
				$error_msg = "Máy chủ đã chọn không tồn tại hoặc đang bảo trì!";
			}
		}
		if ($error_msg == '') {
			$userdb = @json_decode($Cipher->decrypt(file_get_contents('userdb.dat')), true);
			$userdb_luckywheel = $userdb[$_SESSION['username']]['luckywheel'][$_GET['server']][$luckywheelseason];
			$itemsData = array();
			if ($userdb_luckywheel['spincount'] >= 20 && $userdb_luckywheel['spinbonus']['20'] == 0) {
				$itemsData[] = array("item" => "item/test/tuidoanthach", "amount" => 1, "time" => 0);
				$userdb_luckywheel['spinbonus']['20'] = time();
			}
			if ($userdb_luckywheel['spincount'] >= 50 && $userdb_luckywheel['spinbonus']['50'] == 0) {
				$itemsData[] = array("item" => "item/test/tuilinhthach1-8", "amount" => 1, "time" => 0);
				$userdb_luckywheel['spinbonus']['50'] = time();
			}
			if ($userdb_luckywheel['spincount'] >= 100 && $userdb_luckywheel['spinbonus']['100'] == 0) {
				$itemsData[] = array("item" => "item/test/tuidoanthach11", "amount" => 1, "time" => 0);
				$userdb_luckywheel['spinbonus']['100'] = time();
			}
			if ($userdb_luckywheel['spincount'] >= 200 && $userdb_luckywheel['spinbonus']['200'] == 0) {
				$itemsData[] = array("item" => "item/sell/4032", "amount" => 1, "time" => 0);
				$userdb_luckywheel['spinbonus']['200'] = time();
			}
			if ($userdb_luckywheel['spincount'] >= 500 && $userdb_luckywheel['spinbonus']['500'] == 0) {
				$itemsData[] = array("item" => "item/sell/5032", "amount" => 1, "time" => 0);
				$userdb_luckywheel['spinbonus']['500'] = time();
			}
			if ($userdb_luckywheel['spincount'] >= 800 && $userdb_luckywheel['spinbonus']['800'] == 0) {
				$itemsData[] = array("item" => "item/test/hoahongchithach", "amount" => 250, "time" => 0);
				$userdb_luckywheel['spinbonus']['800'] = time();
			}
			if ($userdb_luckywheel['spincount'] >= 1000 && $userdb_luckywheel['spinbonus']['1000'] == 0) {
				$itemsData[] = array("item" => "item/test/hachoachithach", "amount" => 250, "time" => 0);
				$userdb_luckywheel['spinbonus']['1000'] = time();
			}
			if ($userdb_luckywheel['spincount'] >= 1500 && $userdb_luckywheel['spinbonus']['1500'] == 0) {
				$itemsData[] = array("item" => "item/sell/6032", "amount" => 1, "time" => 0);
				$userdb_luckywheel['spinbonus']['1500'] = time();
			}
			if (count($itemsData) == 0) {
				$error_msg = 'Bạn không có phần thưởng nào để nhận';
			}
		}
		if ($error_msg == '') {
			foreach ($itemsData as $itemData) {
				$add_item = @file_get_contents('http://'.$gamesettings[$server_id]['host'].'/userdb.php?id='.$_SESSION['username'].'&mod=add_item&item='.$itemData['item'].'&amount='.$itemData['amount'].'&time='.$itemData['time']);
			}
			$userdb[$_SESSION['username']]['luckywheel'][$_GET['server']][$luckywheelseason] = $userdb_luckywheel;
			@file_put_contents('userdb.dat', $Cipher->encrypt(json_encode($userdb)));
			$info_msg = 'Bạn đã nhận thưởng thành công, vui lòng đăng nhập vào game gặp Thất Quốc Tổng Quản ở Chu Quốc kênh 1 để nhận được lễ bao.';
		}
	} ?>
	<div id="masthead">
		
		<div class="content_pad">
			
			<h1 class="">Nhận thưởng Vòng Quay May Mắn</h1>
			
		</div> <!-- .content_pad -->
		
	</div> <!-- #masthead -->	
	
	<div id="content" class="xgrid">
		
		<div class="x8">
			
			<form action="#" method="post" class="form label-inline uniform" name="Xu2KNB" id="Xu2KNB">
						<h3>Thông tin tài khoản</h3>
							<div class="field">
								<label for="fname">Tên tài khoản </label> <p class="field_info"><?php echo $_SESSION['username']; ?></p>
								<label for="spincount">Số lần đã quay </label>
								<p class="field_info">
								<?php
									$userdb = @json_decode($Cipher->decrypt(file_get_contents('userdb.dat')), true);
									$userdb_luckywheel = $userdb[$_SESSION['username']]['luckywheel'][$_GET['server']][$luckywheelseason];
									echo (int) $userdb_luckywheel['spincount'];
								?>
								</p>
							</div>
						<h3>Phần thưởng</h3>
							<div class="field">
								<label>Mốc 20 lần
								<?php
									if ($userdb_luckywheel['spinbonus']['20'] > 0) {
										echo ' (Đã nhận)';
									} else if ($userdb_luckywheel['spincount'] >= 20) {
										echo ' (Có thể nhận)';
									}
								?>
								</label> <p class="field_info">Túi Đoạn Thạch</p>
								<label>Mốc 50 lần
								<?php
									if ($userdb_luckywheel['spinbonus']['50'] > 0) {
										echo ' (Đã nhận)';
									} else if ($userdb_luckywheel['spincount'] >= 50) {
										echo ' (Có thể nhận)';
									}
								?>
								</label> <p class="field_info">Túi Linh Thạch ngẫu nhiên (1~8)</p>
								<label>Mốc 100 lần
								<?php
									if ($userdb_luckywheel['spinbonus']['100'] > 0) {
										echo ' (Đã nhận)';
									} else if ($userdb_luckywheel['spincount'] >= 100) {
										echo ' (Có thể nhận)';
									}
								?>
								</label> <p class="field_info">Túi Đoạn Thạch 11</p>
								<label>Mốc 200 lần
								<?php
									if ($userdb_luckywheel['spinbonus']['200'] > 0) {
										echo ' (Đã nhận)';
									} else if ($userdb_luckywheel['spincount'] >= 200) {
										echo ' (Có thể nhận)';
									}
								?>
								</label> <p class="field_info">Cao Cấp Đoạn Thạch đặc biệt (Giúp trang bị 6 sao đạt trực tiếp 7 sao)</p>
								<label>Mốc 500 lần
								<?php
									if ($userdb_luckywheel['spinbonus']['500'] > 0) {
										echo ' (Đã nhận)';
									} else if ($userdb_luckywheel['spincount'] >= 500) {
										echo ' (Có thể nhận)';
									}
								?>
								</label> <p class="field_info">Cao Cấp Đoạn Thạch đặc biệt (Giúp trang bị 7 sao đạt trực tiếp 8 sao)</p>
								<label>Mốc 800 lần
								<?php
									if ($userdb_luckywheel['spinbonus']['800'] > 0) {
										echo ' (Đã nhận)';
									} else if ($userdb_luckywheel['spincount'] >= 800) {
										echo ' (Có thể nhận)';
									}
								?>
								</label> <p class="field_info">Hỏa Hồng Chi Thạch x250</p>
								<label>Mốc 1000 lần
								<?php
									if ($userdb_luckywheel['spinbonus']['1000'] > 0) {
										echo ' (Đã nhận)';
									} else if ($userdb_luckywheel['spincount'] >= 1000) {
										echo ' (Có thể nhận)';
									}
								?>
								</label> <p class="field_info">Hắc Hỏa Chi Thạch x250</p>
								<label>Mốc 1500 lần
								<?php
									if ($userdb_luckywheel['spinbonus']['1500'] > 0) {
										echo ' (Đã nhận)';
									} else if ($userdb_luckywheel['spincount'] >= 1500) {
										echo ' (Có thể nhận)';
									}
								?>
								</label> <p class="field_info">Cao Cấp Đoạn Thạch đặc biệt (Giúp trang bị 8 sao đạt trực tiếp 9 sao)</p>
							</div>
							<p style="font-size: 13px; color: #cc0000;">Lưu ý:<br>- Mỗi mốc phần thưởng chỉ có thể nhận được 1 lần.<br>- Phần thưởng sẽ được chuyển đến Thất Quốc Tổng Quản tại Chu Quốc.</p>
							
						<h3>Nhận thưởng</h3>
							<div class="field"><label for="txtCaptcha">Nhập mã xác nhận</label>                            
                            <input type="hidden" name="CAPTCHA_Postback" id="CAPTCHA_Postback" value="true" />
                           		<div style="float:left"><input id="txtCaptcha" name="txtCaptcha" size="6" type="text" class="txtCapcha" maxlength="6" /></div><div style="float:left"><a onclick="reloadCapcha();" href="javascript:void(0);"><img id="imgmCaptcha" src="secureimage.php?<?php echo time(); ?>" style="border-width:0px; width:120px; height:50px" /></a></div>
                            </div>
                            
							<br />
							<div class="buttonrow" id="submitbutton">
								<input id="validate" class="btn" type="submit" name="sigup" value="Xác Nhận">
								<input id="back" class="btn btn-blue" name="back" type="button" value="Quay Lại" onclick="window.location.href='/?page=vongquaymayman&server=<?php echo $_GET['server']; ?>'">
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
	
