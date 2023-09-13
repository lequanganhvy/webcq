	<div id="masthead">
		
		<div class="content_pad">
			
			<h1 class="">Lịch sử giao dịch</h1>
		
		</div> <!-- .content_pad -->
		
	</div> <!-- #masthead -->	
	
	<div id="content" class="xgrid">
		
		<div class="x8">
				<table class="data display datatable" id="example">
					<thead>                    
						<tr>
							<th>STT</th>
							<th>Mã thẻ</th>							
							<th>Mệnh giá</th>							
							<th>Loại thẻ</th>
                            <th>Trạng thái</th>
							<th>Ngày nạp</th>
						</tr>
					</thead>
					<tbody>
<?php $i = 0;
	$sql_query = @mysql_query("SELECT * FROM card_history WHERE account = '".$_SESSION['username']."' ORDER BY id DESC");
	while ($card = @mysql_fetch_array($sql_query)) {
		$i++;
		echo '
						<tr class="'.$card['status'].'">
							<td><strong>'.$i.'</strong></td>
							<td>'.$card['cardseri'].'</td>
							<td>'.$card['cardvalue'].'</td>
                            <td><strong>'.$card['cardtype'].'</strong></td>
							<td style="text-align:center">'.$card['status'].'</td>
							<td style="text-align:right">'.$card['time'].'</td>
						</tr>';
	}
?>

					</tbody>
				</table>
			
		</div> <!-- .x8 -->
		<div class="apg-mini apg-mini-1" style="padding-top:43px">
				<div class="apg-option">
					
					<div class="apg-header">
			
						<h1>Lịch sử giao dịch</h1>
					</div>
					
					<div class="apg-content">
					
					<ul>
						<li><strong><a href="/?page=lichsugiaodich">Lịch sử nạp Xu</a></strong></li>
						<li><strong><a href="/?page=lichsuknb">Lịch sử đổi Xu</a></strong></li>
					</ul>
					</div>
					
					
					<div class="apg-footer">
						<span class="apg-price">Bạn còn <strong><?php echo intval($pspuser['coin']); ?> Xu</strong></span>
                        <?php if ($pspuser['coin'] > 0) { ?><a href="/?page=kimnguyenbao" class="btn btn-small">Đổi Xu</a><?php } else { ?><a href="/?page=napthe" class="btn btn-small">Nạp Xu</a><?php } ?>	
					 </div>
					 
				</div>
		</div>		
		
	</div> <!-- #content -->
	
