<?php
	if (!isset($_SESSION['username'])) {
		$_SESSION['error_msg'] = 'Bạn cần phải đăng nhập mới có thể thực hiện được việc này';
		die(header('location: http://'.$_SERVER['SERVER_NAME']));
	}

	if ($can_recharge != true) {
		$_SESSION['error_msg'] = 'Hệ thống thanh toán tạm đóng để bảo trì';
		die(header('location: http://'.$_SERVER['SERVER_NAME']));
	}
	if ($pspuser['socialnumber'] == '') {
		$_SESSION['null_socialnumber'] = 1;
		die(header('location: http://'.$_SERVER['SERVER_NAME'].'/?page=taikhoan'));
	}
	if (!empty($_POST)) {
		if ($_SESSION['security_code'] != strtolower($_POST['txtCaptcha']) || empty($_SESSION['security_code'])) {
			$error_msg = 'Chuỗi mã xác nhận không đúng';
		}
		if ($error_msg == '') {
			if (strlen($_POST['fieldCode']) < 10 || strlen($_POST['fieldCode']) > 15) {
				$error_msg = 'Mã thẻ phải có 10 hoặc 15 ký tự';
			}
		}
		if ($error_msg == '') {
			if ($_POST['fieldType'] == 'VIETTEL') {
				$cardtype = 1;
				$cardname = 'Viettel';
			} else if ($_POST['fieldType'] == 'VMS') {
				$cardtype = 2;
				$cardname = 'MobiPhone';
			} else if ($_POST['fieldType'] == 'VNP') {
				$cardtype = 3;
				$cardname = 'VinaPhone';
			}
			
			$serial = $_POST['fieldSerial'];
			$code = $_POST['fieldCode'];
			$amount = $_POST['fieldAmount'];
			
			if (@mysql_num_rows(mysql_query("SELECT * FROM card_history WHERE cardseri = '".$serial."' AND status = 'OK'"))) {
				$error_msg = 'Thẻ đã có trong hệ thống. Nếu bạn chưa nhận được Xu. Vui lòng liên hệ với NPH';
			}
			
			if ($error_msg == '') {
				$data = $recard['merchant_id'] . $cardtype . $serial . $code . $amount;

				$signature = hash_hmac('sha256', $data, $recard['secret_key']);		
				
				$ch = curl_init();
				
				curl_setopt_array($ch, array(
					CURLOPT_RETURNTRANSFER => 1,
					CURLOPT_URL => 'https://recard.vn/api/card',
					CURLOPT_POST => 1,
					CURLOPT_SSL_VERIFYPEER => 0,
					CURLOPT_POSTFIELDS => array(
						'merchant_id' => $recard['merchant_id'],
						'secret_key' => $recard['secret_key'],
						'type' => $cardtype,
						'serial' => $serial,
						'code' => $code,
						'amount' => $amount,
						'signature' => $signature
					)
				));
				
				$resp = curl_exec($ch);
				
				$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
				
				if($resp === false) {
					$resp = curl_error($ch);
				}
				
				curl_close($ch);

				$resp = json_decode($resp, true);
				
				// The results returned successfully
				if ($httpcode === 200 && $resp['success'] == 1) {
					$transaction_code = $resp['transaction_code'];
					
					$cardstatus = 'Pending';
					
					$cardvalue = $amount / 1000;
					
					@mysql_query("INSERT INTO card_history (account, cardseri, cardcode, cardvalue, cardtype, status, time, transaction_code) values ('".$_SESSION['username']."', '".stripslashes($serial)."', '".stripslashes($code)."', '$cardvalue', '$cardname', '$cardstatus', '".date("d/m/Y H:i:s")."', '$transaction_code')");

					$info_msg = 'Bạn đã nạp thẻ ' . $cardname . ' với mệnh giá ' . $amount . '. Vui lòng chờ ít phút để hệ thống duyệt thẻ.';					
				} else {
					foreach($resp as $msg) {
						$error_msg = $msg[0];
					}
				}
			}
		}
	}
?>
	<div id="masthead">
		
		<div class="content_pad">
			
			<h1 class="">Nạp thẻ</h1>
			
		</div> <!-- .content_pad -->
		
	</div> <!-- #masthead -->	
	
	<div id="content" class="xgrid">
		
		<div class="x8">
			
			<form action="#" method="post" class="form label-inline uniform" name="depform" id="depform">
							<div class="field"><label for="fieldSerial">Số Seri </label> <input id="fieldSerial" name="fieldSerial" size="50" type="text" class="medium" maxlength="15" /></div>				
							<div class="field"><label for="fieldCode">Mã số nạp tiền </label> <input id="fieldCode" name="fieldCode" size="50" type="text" class="medium" maxlength="15" />
							<p class="field_help">Mã số nạp tiền nằm bên dưới lớp tráng bạc</p>
							</div>		
							<div class="field">
								<label for="fieldAmount">Mệnh giá </label>
								<select id="fieldAmount" name="fieldAmount" class="medium">
									<option value="">Chọn mệnh giá</option>
									<option value="10000">10.000 VNĐ</option>
									<option value="20000">20.000 VNĐ</option>
									<option value="30000">30.000 VNĐ</option>
									<option value="50000">50.000 VNĐ</option>
									<option value="100000">100.000 VNĐ</option>
									<option value="200000">200.000 VNĐ</option>
									<option value="300000">300.000 VNĐ</option>
									<option value="500000">500.000 VNĐ</option>
									<option value="1000000">1.000.000 VNĐ</option>
								</select>
							</div>
							
							<div class="controlset field">
								<span class="label">Loại thẻ nạp</span>								
								<div class="controlset-pad">
									<label for="cardTypeVIETTEL">
	<div id="VIETTEL_card">
		<input type="radio" id="cardTypeVIETTEL" name="fieldType" value="VIETTEL" />
	</div>
</label>
<label for="cardTypeMOBI">
	<div id="MOBI_card">
		<input type="radio" id="cardTypeMOBI" name="fieldType" value="VMS" />
	</div>
</label>
<label for="cardTypeVINA">
	<div id="VINA_card">
		<input type="radio" id="cardTypeVINA" name="fieldType" value="VNP" />
	</div>
</label>
<!--label for="cardTypeGATE">
	<div id="GATE_card">
		<input type="radio" id="cardTypeGATE" name="fieldType" value="GATE" />
	</div>
</label-->

								</div>
							</div>

							<div class="field"><label for="txtCaptcha">Nhập mã xác nhận</label>                            
                            <input type="hidden" name="CAPTCHA_Postback" id="CAPTCHA_Postback" value="true" />
                           		<div style="float:left"><input id="txtCaptcha" name="txtCaptcha" size="6" type="text" class="txtCapcha" maxlength="6" /></div><div style="float:left"><a onclick="reloadCapcha();" href="javascript:void(0);"><img id="imgmCaptcha" src="secureimage.php?<?php echo time(); ?>" style="border-width:0px; width:120px; height:50px" /></a></div>
                            </div>
                            
							<br />
							<div class="buttonrow" style="display:block" id="buttonNap">
								<input id="validate" class="btn" type="submit" name="sigup" value="Nạp thẻ">
								<input id="reset" class="btn btn-black" name="reset" type="button" value="Reset">
							</div>
							<div class="buttonrow" style="text-align:center; display:none" id="loadingNap">
								<b><img src="/images/loading.gif"><br>Hệ thống đang xử lý thẻ nạp. Bạn vui lòng chờ trong giây lát</b>
							</div>
							
							<div style="text-align:center; display:none; color:#F00; font-weight:bold; font-size:16px;" id="javascript_countdown_time">
								
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

	
