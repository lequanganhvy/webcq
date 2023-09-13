<?php
	if (!isset($_SESSION['username'])) {
		$_SESSION['error_msg'] = 'Bạn cần phải đăng nhập mới có thể thực hiện được việc này';
		die(header('location: http://'.$_SERVER['SERVER_NAME']));
	}
	if (!empty($_GET['server']) && !isset($luckywheelsettings[$_GET['server']])) {
		$_SESSION['error_msg'] = 'Không có thông tin về hoạt động Vòng Quay May Mắn ở máy chủ này';
		die(header('location: http://'.$_SERVER['SERVER_NAME']));
	} ?>
	<div id="masthead">
		
		<div class="content_pad">
			
			<h1 class="">Vòng Quay May Mắn</h1>
			
		</div> <!-- .content_pad -->
		
	</div> <!-- #masthead -->	
	
	<div id="content" class="xgrid">
		
		<div class="x8">
			
			<form action="#" method="post" class="form label-inline uniform" name="Xu2KNB" id="Xu2KNB">
						<h3>Thông tin tài khoản</h3>
							<div class="field">
								<label for="fname">Tên tài khoản </label> <p class="field_info"><?php echo $_SESSION['username']; ?></p>
								<?php if (!empty($_GET['server'])) { ?>
								<label for="spincount">Số lần đã quay </label>
								<p class="field_info">
								<?php
									$userdb = @json_decode($Cipher->decrypt(file_get_contents('userdb.dat')), true);
									echo (int) $userdb[$_SESSION['username']]['luckywheel'][$_GET['server']][$luckywheelseason]['spincount'];
								?>
									<a href="?page=nhanthuongvongquaymayman&server=<?php echo $_GET['server']; ?>">(Nhận thưởng)</a>
								</p>
								<?php } ?>
								<label for="type">Máy chủ nhận thưởng </label>
								<select id="type" class="medium" name="fieldServer" onchange="javascript:location.href = this.value;">
										<option value="?page=vongquaymayman&server=0" selected="selected">Hãy chọn máy chủ</option>
<?php foreach ($gamesettings as $k => $v) {
	if ($_GET['server'] == $k) echo '<option value="'.$k.'" selected>'.$v['name'].'</option>';
    else echo '<option value="/?page=vongquaymayman&server='.$k.'">'.$v['name'].'</option>';
} ?>
								</select>
							</div>
<?php if (count($luckywheelsettings[$_GET['server']]['items']) > 0) { ?>
						<h3>Quay thưởng</h3>
							<link href="http://netdna.bootstrapcdn.com/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
							<link href='http://fonts.googleapis.com/css?family=Exo+2:900' rel='stylesheet' type='text/css'>
							<link rel="stylesheet" href="./css/luckywheel.min.css?<?php echo time(); ?>" type="text/css" media="screen"/>
							<script src='//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js'></script>
							<script src='./js/luckywheel.min.js?<?php echo time(); ?>'></script>
<?php if (!preg_match('/Mozilla/i', $_SERVER['HTTP_USER_AGENT'])) { ?>
							<div id="field" align="center">
<?php } ?>
									<div id="wheel">
										<div id="spin">
											<div id="inner-spin"></div>
										</div>
										<div id="inner-wheel">
										<?php foreach($luckywheelsettings[$_GET['server']]['items'] as $index => $item) { ?>
											<div class="sec">
												<a href="/luckywheel.php?iteminfo&id=<?php echo $index; ?>&server=<?php echo $_GET['server']; ?>">
													<img src="<?php echo $item['image']; ?>" class="fa">
												</a>
											</div>
										<?php } ?>
										</div>       
										<style>
										#inner-wheel a {
											display:block;
											width: 45px;
											height:45px;
											margin-left:-22.5px;
										}
										#inner-wheel {
											position: relative;
											z-index: 999;
										}
										</style>
										<!--div id="shine"></div-->
									</div>
									<div id="txt"></div>
<?php if (!preg_match('/Mozilla/i', $_SERVER['HTTP_USER_AGENT'])) { ?>
							</div>
<?php } ?>
							<p style="font-size: 13px; color: #cc0000;">Lưu ý:<br>- Mỗi lần quay sẽ tự động khấu trừ 50 xu, nếu không đủ sẽ không thể quay.<br>- Phần thưởng sẽ được chuyển đến Thất Quốc Tổng Quản tại Chu Quốc.</p>
<?php } ?>
						</form>
			<link href="https://cdn.jsdelivr.net/qtip2/2.2.1/jquery.qtip.min.css" rel="stylesheet">
			<script src="https://cdn.jsdelivr.net/qtip2/2.2.1/jquery.qtip.min.js"></script>
			<script>
				 $(document).ready(function() {
					$('.sec a').click(function() {
						return false;
					});
					$('.sec a').each(function() {
						$(this).qtip({
							content: {
								text: function(event, api) {
									$.ajax({url: api.elements.target.attr('href') }).then(function(content) {
										api.set('content.text', content);
									}, function(xhr, status, error) {
										api.set('content.text', status + ': ' + error);
									});
									return 'Loading...'; // Set some initial text
								}
							},
							position: { viewport: $(window) }, style: 'qtip-wiki'
						 });
					 });
				 });
			</script>
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
	
