<?php
	date_default_timezone_set('Asia/Saigon');

	$sitesettings = array (
		'name' => 'Chiến Quốc 2 | Hệ Thống Quản Lý Tài Khoản', 
		'url' => 'http://id.chienquoc2.com', 
		'login' => 1, 
		'debug' => 0, 
		'offline' => 0
	);

	$dbsettings = array (
		'server' => '127.0.0.1', 
		'user' => 'root', 
		'pass' => '123456', 
		'name' => 'id_chienquoc2'
	);

	$can_recharge = true;
	
	$recard = array (
		'merchant_id' => '293059f0-36f3-46e4-88d0-603410a8f6c4',
		'secret_key' => 'dYg9lrrqJduO',
	);	

	$gamesettings = array (
		'1' => array (
			'name' => 'Tôn Tử', 
			'auth_remote' => array (
				'account' => 'chienquoc2_api', 
				'password' => 'c0bedcebc92765fa3b38375fa890f8f8', 
				'url' => 'http://s1.chienquoc2.com/wsapi.php?wsdl'
			),
			'host' => 's1.chienquoc2.com',
			'can_recharge' => 1,
		),
	);

	$promocodesettings = array (
		'CQ2001' => array (
			'key' => 'codekhaosatlan2',
			'limit' => 1,
			'server' => -1,
			'expire' => null,
			'item' => array (
				'item/test/lebaokhaosatlan2loai1',
			),
			'type' => 1,
			'value' => null,
		),
		'CQ2002' => array (
			'key' => 'codekhaosatlan2',
			'limit' => 1,
			'server' => -1,
			'expire' => null,
			'item' => array (
				'item/test/lebaokhaosatlan2loai2',
			),
			'type' => 1,
			'value' => null,
		),
		'CQ2003' => array (
			'key' => 'codekhaosatlan2',
			'limit' => 1,
			'server' => -1,
			'expire' => null,
			'item' => array (
				'item/test/lebaokhaosatlan2loai3',
			),
			'type' => 1,
			'value' => null,
		),
		'CQ2004' => array (
			'key' => 'codetanthumactu1',
			'limit' => 1,
			'server' => 3,
			'expire' => null,
			'item' => array (
				'item/test/lebaotanthumactu1',
			),
			'type' => 1,
			'value' => null,
		),
		'CQ2005' => array (
			'key' => 'sinhnhatchienquoclan4',
			'limit' => 1,
			'server' => -1,
			'expire' => null,
			'item' => array (
				'item/test/lebaotrian4namchienquoc',
			),
			'type' => 1,
			'value' => null,
		),
		'CQ2006' => array (
			'key' => 'codemungnammoi2017',
			'limit' => 1,
			'server' => -1,
			'expire' => null,
			'item' => array (
				'item/test/lebaonammoi2017',
			),
			'type' => 1,
			'value' => null,
		),
		'CQ2007' => array (
			'key' => 'codemungnammoi2017_2',
			'limit' => 1,
			'server' => -1,
			'expire' => null,
			'item' => array (
				'item/test/lebaonammoi2017_2',
			),
			'type' => 1,
			'value' => null,
		),
		'CQ2008' => array (
			'key' => 'codemungthanhlapgroup',
			'limit' => 1,
			'server' => -1,
			'expire' => null,
			'item' => array (
				'item/test/lebaomungthanhlapgroup',
			),
			'type' => 1,
			'value' => null,
		),
		'CQ2009' => array (
			'key' => 'codehungvuong',
			'limit' => 1,
			'server' => -1,
			'expire' => null,
			'item' => array (
				'item/test/lebaohungvuong',
			),
			'type' => 1,
			'value' => null,
		),
		'CQ2010' => array (
			'key' => 'codefanpage7000like',
			'limit' => 1,
			'server' => -1,
			'expire' => null,
			'item' => array (
				'item/test/lebaofanpage7000like',
			),
			'type' => 1,
			'value' => null,
		),
		'CQ2011' => array (
			'key' => 'codekitichbongda',
			'limit' => 1,
			'server' => -1,
			'expire' => null,
			'item' => array (
				'item/test/lebaokitichbongda',
			),
			'type' => 1,
			'value' => null,
		),
		'CQ2012' => array (
			'key' => 'codemuahesoidong2018',
			'limit' => 1,
			'server' => -1,
			'expire' => null,
			'item' => array (
				'item/test/lebaomuahesoidong2018',
			),
			'type' => 1,
			'value' => null,
		),
		'CQ2013' => array (
			'key' => 'codesinhnhat2018',
			'limit' => 1,
			'server' => -1,
			'expire' => null,
			'item' => array (
				'item/test/lebaosinhnhatchienquoc2018',
			),
			'type' => 1,
			'value' => null,
		),
		'CQ2014' => array (
			'key' => 'codebongda2018',
			'limit' => 1,
			'server' => -1,
			'expire' => '20181222235959',
			'item' => array (
				'item/test/lebaobongdaffcup2018',
			),
			'type' => 2,
			'value' => 'AFFVIETNAMVODICH'
		),
	);

	$luckywheelseason = 13;

	$luckywheelsettings = array (
		'1' => array (
			'start_time' => '2019-12-12 10:00:00',
			'end_time' => '2019-12-31 24:00:00',
			'end_time2' => '2020-01-05 24:00:00',
			'items' => array (
				array (
					'image' => '/images/luckywheel/3107.png',
					'degree' => 330,
					'name' => 'Túi Hoàng Kim 8 sao cấp 100',
					'desc' => 'Sau khi mở có thể nhận được trang bị ngẫu nhiên Hoàng Kim 8 sao cấp 100.',
					'file' => '/item/test/tuihoangkim8saocap100',
					'amount' => 1,
					'time' => 0,
					'rate' => 2999
				),
				array (
					'image' => '/images/luckywheel/7107.png',
					'degree' => 300,
					'name' => 'Túi Đoạn Thạch',
					'desc' => 'Sau khi sử dụng có thể nhận được một Đoạn Thạch ngẫu nhiên.',
					'file' => '/item/test/tuidoanthach',
					'amount' => 1,
					'time' => 0,
					'rate' => 15000
					),
				array (
					'image' => '/images/luckywheel/3107.png',
					'degree' => 270,
					'name' => 'Túi Pháp Bảo x5',
					'desc' => 'Túi thần kỳ, có thể mở ra rất nhiều loại Pháp Bảo và vật phẩm đáng giá.',
					'file' => '/item/test/shenqifabaodai',
					'amount' => 5,
					'time' => 0,
					'rate' => 15000
				),
				array (
					'image' => '/images/luckywheel/7106.png',
					'degree' => 240,
					'name' => 'Túi Linh Thạch ngẫu nhiên (1~8)',
					'desc' => 'Sau khi sử dụng có thể nhận được ngẫu nhiên một Linh Thạch cấp 1~8',
					'file' => '/item/test/tuilinhthach1-8',
					'amount' => 1,
					'time' => 0,
					'rate' => 6000
				),
				array (
					'image' => '/images/luckywheel/tuchandon.png',
					'degree' => 210,
					'name' => '5.000.000 Kinh Nghiệm',
					'desc' => 'Nhận được 5.000.000 điểm Kinh nghiệm.',
					'file' => 'exp',
					'amount' => 5000000,
					'time' => 0,
					'rate' => 25000
				),
				array (
					'image' => '/images/luckywheel/7106.png',
					'degree' => 180,
					'name' => 'Túi Bảo Thạch Cao Cấp (Cấp 1)',
					'desc' => 'Sau khi sử dụng có thể nhận được một Bảo Thạch cấp 1 ngẫu nhiên.',
					'file' => '/item/test/tuibaothachcaocap1',
					'amount' => 1,
					'time' => 0,
					'rate' => 4000
				),
				array (
					'image' => '/images/luckywheel/4273.png',
					'degree' => 150,
					'name' => 'Cao Cấp Đoạn Thạch đặc biệt',
					'desc' => 'Chỉ có thể luyện trang bị 8 sao, giúp trang bị đạt trực tiếp 9 sao.',
					'file' => '/item/sell/6032',
					'amount' => 1,
					'time' => 0,
					'rate' => 1
				),
				array (
					'image' => '/images/luckywheel/tuchandon.png',
					'degree' => 120,
					'name' => '15.000.000 Kinh Nghiệm',
					'desc' => 'Nhận được 15.000.000 điểm Kinh nghiệm.',
					'file' => 'exp',
					'amount' => 15000000,
					'time' => 0,
					'rate' => 20000
				),
				array (
					'image' => '/images/luckywheel/7126.png',
					'degree' => 90,
					'name' => 'Túi Huyết Sắc',
					'desc' => 'Trang bị Huyết Sắc cấp 105',
					'file' => '/item/test/tuihuyetsac',
					'amount' => 1,
					'time' => 0,
					'rate' => 5000
				),
				array (
					'image' => '/images/luckywheel/4051.png',
					'degree' => 60,
					'name' => 'Hỏa Hồng Chi Thạch x5',
					'desc' => 'Viên đá màu đỏ kì lạ, phát ra khí nóng thất thường. Nghe nói Thất Quốc Tổng Quản biết cách sử dụng vật phẩm này.\n Có thể sử dụng để khôi phục thọ mệnh cho Huyễn Thú Hỏa Sư',
					'file' => '/item/test/hoahongchithach',
					'amount' => 5,
					'time' => 0,
					'rate' => 2500
				),
				array (
					'image' => '/images/luckywheel/9167.png',
					'degree' => 30,
					'name' => 'Cửu Chuyển Hỗn Thiên Đơn',
					'desc' => 'Đan Dược thần kì, đã được tích lũy sẵn 1 lượng tiềm năng.',
					'file' => '/item/test/cuuchuyenhonthiendon',
					'amount' => 1,
					'time' => 0,
					'rate' => 2000
				),
				array (
					'image' => '/images/luckywheel/4051.png',
					'degree' => 360,
					'name' => 'Hắc Hỏa Chi Thạch x5',
					'desc' => 'Viên đá màu đỏ sẫm kì lạ, phát ra khí nóng thất thường. Nghe nói Thất Quốc Tổng Quản biết cách sử dụng vật phẩm này.\n Có thể sử dụng để khôi phục thọ mệnh cho Huyễn Thú Hắc Hỏa Sư',
					'file' => '/item/test/hachoachithach',
					'amount' => 5,
					'time' => 0,
					'rate' => 2500
				),
			),
		),
	);

	$luckywheel2settings = array (
		'1' => array (
			'start_time' => '2018-01-01 00:00:00',
			'end_time' => '2018-12-01 24:00:00',
			'items' => array (
				array (
					'image' => '/images/luckywheel/7124.png',
					'degree' => 330,
					'name' => 'Túi Bảo Đồ',
					'desc' => 'Mở túi có thể nhận ngẫu nhiên 1 góc Tàng Bảo Đồ',
					'file' => '/item/test/tuibaodo',
					'amount' => 1,
					'time' => 0,
					'rate' => 15000
				),
				array (
					'image' => '/images/luckywheel/7124.png',
					'degree' => 300,
					'name' => 'Túi Ngân Lượng',
					'desc' => 'Mở túi có thể nhận ngẫu nhiên 10 đến 50 vạn lượng',
					'file' => '/item/test/tuinganluong',
					'amount' => 1,
					'time' => 0,
					'rate' => 15000
				),
				array (
					'image' => '/images/luckywheel/7124.png',
					'degree' => 270,
					'name' => 'Túi Dược Phẩm (Lớn)',
					'desc' => 'Mở túi có thể nhận ngẫu nhiên 10 bình dược phẩm lớn',
					'file' => '/item/test/tuiduocphamlon',
					'amount' => 1,
					'time' => 0,
					'rate' => 15000
				),
				array (
					'image' => '/images/luckywheel/7124.png',
					'degree' => 240,
					'name' => 'Túi Bí Kíp',
					'desc' => 'Mở túi có thể nhận ngẫu nhiên 1 bí kíp',
					'file' => '/item/test/tuibikip',
					'amount' => 1,
					'time' => 0,
					'rate' => 15000
				),
				array (
					'image' => '/images/luckywheel/tuilinhthach.png',
					'degree' => 210,
					'name' => 'Túi Linh Thạch ngẫu nhiên (1~8)',
					'desc' => 'Sau khi sử dụng có thể nhận được ngẫu nhiên một Linh Thạch cấp 1~8',
					'file' => '/item/test/tuilinhthach1-8',
					'amount' => 1,
					'time' => 0,
					'rate' => 10000
				),
				array (
					'image' => '/images/luckywheel/tuidoanthach.png',
					'degree' => 180,
					'name' => 'Túi Đoạn Thạch',
					'desc' => 'Sau khi sử dụng có thể nhận được một Đoạn Thạch ngẫu nhiên.',
					'file' => '/item/test/tuidoanthach',
					'amount' => 1,
					'time' => 0,
					'rate' => 10000
				),
				array (
					'image' => '/images/luckywheel/7124.png',
					'degree' => 150,
					'name' => 'Túi Thú Cưỡi',
					'desc' => 'Túi Thú Cưỡi Sau khi sử dụng có thể nhận được một Trứng hoặc Lệnh thú cưỡi ngẫu nhiên.',
					'file' => '/item/test/tuithucuoi_7',
					'amount' => 1,
					'time' => 0,
					'rate' => 5000
				),
				array (
					'image' => '/images/luckywheel/4952.png',
					'degree' => 120,
					'name' => 'Kỳ Lân Ngọc Bội',
					'desc' => 'Trên ngọc bội có khắc thần thú trong truyền thuyết, tiềm ẩn ma lực vô cùng lớn.<br/> Khả năng Tín Vật Môn Phái ở cấp 1 tăng lên cấp 2.<br/> Người chơi phải đợi đạt đến cấp 45 mới có thể sử dụng ngọc bội.',
					'file' => '/item/01/0007',
					'amount' => 1,
					'time' => 0,
					'rate' => 1995
				),
				array (
					'image' => '/images/luckywheel/4051.png',
					'degree' => 90,
					'name' => 'Hỏa Hồng Chi Thạch',
					'desc' => 'Viên đá màu đỏ kì lạ, phát ra khí nóng thất thường. Nghe nói Thất Quốc Tổng Quản biết cách sử dụng vật phẩm này.<br/> Có thể sử dụng để khôi phục thọ mệnh cho Huyễn Thú Hỏa Sư',
					'file' => '/item/test/hoahongchithach',
					'amount' => 200,
					'time' => 0,
					'rate' => 5
				),
				array (
					'image' => '/images/luckywheel/8984.png',
					'degree' => 60,
					'name' => 'Phụng Hoàng Chi Hỏa',
					'desc' => 'Vật phẩm rất cần thiết trong việc hợp thành Phụng Hoàng Ngọc Bội.',
					'file' => '/item/test/phunghoangchihoa',
					'amount' => 1,
					'time' => 0,
					'rate' => 3000
				),
				array (
					'image' => '/images/luckywheel/ketdanthach.png',
					'degree' => 30,
					'name' => 'Kết Đan Thạch (Nhỏ)',
					'desc' => 'Sau khi sử dụng trong vòng 1 giờ giết quái tu luyện có thể đạt được 8 lần Kinh nghiệm ngoài định mức.<br/> Tuy nhiên, sau khi hết thời gian tu luyện sẽ phải chịu thiệt hại trong 12 giờ, kinh nghiệm đạt được còn 10%.<br/> <font color=red>Trong thời gian đang phải chịu thiệt hại của Kết Đan Thạch sẽ không thể tiếp tục sử dụng.<br/> Thời gian trừng phạt của Kết Đan Thạch vẫn sẽ giảm đi khi không online.</font>',
					'file' => '/item/test/ketdanthachnho',
					'amount' => 1,
					'time' => 0,
					'rate' => 5000
				),
				array (
					'image' => '/images/luckywheel/7124.png',
					'degree' => 360,
					'name' => 'Túi Công Thức Thần Bí',
					'desc' => 'Mở túi có thể nhận ngẫu nhiên 1 công thức may mặc hoàng kim',
					'file' => '/item/test/tuicongthucthanbi',
					'amount' => 1,
					'time' => 0,
					'rate' => 5000
				)
			)
		),
	);
?>
