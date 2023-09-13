<?php
	include('includes/GB_API.php');

	if (!isset($_SESSION['username'])) {
		$_SESSION['error_msg'] = 'Bạn cần phải đăng nhập mới có thể thực hiện được việc này';
		die(header('location: http://'.$_SERVER['SERVER_NAME']));
	}
	$can_recharge = false;
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
				$telcoCode = 'VTT';
				$telcoStatus = -1;
			} else if ($_POST['fieldType'] == 'VMS') {
				$cardtype = 2;
				$cardname = 'MobiPhone';
				$telcoCode = 'VMS';
				$telcoStatus = -1;
			} else if ($_POST['fieldType'] == 'VNP') {
				$cardtype = 3;
				$cardname = 'VinaPhone';
				$telcoCode = 'VNP';
				$telcoStatus = -1;
			} else if ($_POST['fieldType'] == 'GATE') {
				$cardtype = 4;
				$cardname = 'Gate';
				$telcoCode = 'GATE';
			} else {
				$error_msg = 'Lỗi hệ thống thanh toán. Vui lòng thông báo cho NPH';
				$cardstatus = 'Cancel';
			}
			if ($error_msg == '') {
				if (@mysql_num_rows(mysql_query("SELECT * FROM card_history WHERE cardseri = '".$_POST['fieldSerial']."' AND status = 'OK'"))) {
					$error_msg = 'Thẻ đã có trong hệ thống. Nếu bạn chưa nhận được Xu. Vui lòng liên hệ với NPH';
				}
			}
			if ($error_msg == '') {
				if ($telcoStatus == -1) {
					$cardstatus = 'OK';
					@mysql_query("INSERT INTO card_history (account, cardseri, cardcode, cardvalue, cardtype, status, time) values ('".$_SESSION['username']."', '".stripslashes($_POST['fieldSerial'])."', '".stripslashes($_POST['fieldCode'])."', '0', '$cardname', '$cardstatus', '".date("d/m/Y H:i:s")."')");
					$error_msg = 'Hệ thống tạm giữ thẻ, vui lòng giữ lại thẻ đến khi nhận được Xu hoặc gửi tin nhắn qua fanpage để được xử lý nhanh nhất';
					mail("datyb1993@gmail.com", "Thong bao lien quan den nap the", "Kiem tra lich su, co nguoi nap the", "MIME-Version: 1.0\r\nContent-type: text/html; charset=utf-8\r\nFrom: Chien Quoc 2 <no-reply@chienquoc2.com>\r\n");
				}
			}
			if ($error_msg == '') {
				$merchant_id = 959;
				$api_user = "54c0d02daeea1";
				$api_password = "5ecc62049e31aac6f3af1dd77644429f";
				$api_option = "Nạp xu vào tài khoản " . strtolower($_SESSION['username']) . " tại hệ thống id.chienquoc2.com";
				$gb_api = new GB_API();
				$gb_api->setMerchantId($merchant_id);
				$gb_api->setApiUser($api_user);
				$gb_api->setApiPassword($api_password);
				$gb_api->setPin($_POST['fieldCode']);
				$gb_api->setSeri($_POST['fieldSerial']);
				$gb_api->setCardType(intval($cardtype));
				$gb_api->setNote($api_option);
				$gb_api->cardCharging();
				$code = intval($gb_api->getCode());
				$cardresult = intval($gb_api->getInfoCard());
				if ($cardresult < 10000) {
					$error_msg = $gb_api->getMsg();
					$cardresult = 0;
					$cardstatus = 'Cancel';
				} else {
					@mysql_query("UPDATE account SET coin = '".($pspuser['coin']+($cardresult/100))."' WHERE account = '".$_SESSION['username']."' AND password = '".$_SESSION['password']."'");
					$info_msg = 'Chúc mừng bạn đã nạp thẻ thành công thẻ mệnh giá '.number_format($cardresult, 0, ',', '.').' đồng!<br>Tài khoản của bạn đã nhận được '.($cardresult/100).' Xu';
					$pspuser = $Database->DBFetch("SELECT * FROM account WHERE account = '".$_SESSION['username']."'");
					$cardstatus = 'OK';
				}
				@mysql_query("INSERT INTO card_history (account, cardseri, cardcode, cardvalue, cardtype, status, time) values ('".$_SESSION['username']."', '".stripslashes($_POST['fieldSerial'])."', '".stripslashes($_POST['fieldCode'])."', '".($cardresult/1000)."', '$cardname', '$cardstatus', '".date("d/m/Y H:i:s")."')");
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
							<div class="field"><label for="fieldSerial">Số Seri </label> <input id="fieldSerial" name="fieldSerial" size="50" type="text" class="medium" maxlength="16" /></div>				
							<div class="field"><label for="fieldCode">Mã số nạp tiền </label> <input id="fieldCode" name="fieldCode" size="50" type="text" class="medium" maxlength="16" />
							<p class="field_help">Mã số nạp tiền nằm bên dưới lớp tráng bạc</p>
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

	
