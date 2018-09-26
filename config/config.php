<?php

return [
<<<<<<< HEAD
	'redis.host' => '127.0.0.1',	
	'mysql.dsn' => 'mysql:host=127.0.0.1;dbname=loccitane_balloon',
	'mysql.user' => 'root',
	'mysql.pass' => '',
	'authorized.router' => [
		'/test/2',
	],
	'watchdog' => [
		'D1M' => ['type' => 'access_token', 'status' => 'all'],
		'SameSame' => ['type' => 'wechat_message', 'status' => 'all'],
		'WeChat' => ['type' => 'custom_service', 'status' => 'all'],
	],
];
=======
	'redis.host' => '10.10.67.105',	
	'database.dsn' => 'mysql:host=127.0.0.1;dbname=coach_msg',
	'database.user' => 'root',
	'database.pass' => '',
	'coach.birthday.tpl' => [
		'prod' => false,
		'tag' => '20180817',
		'file' => [
			'TARGETMPROMOTION_CoachMLC_BIRTHDAY_800_20180817.txt', 
			'TARGETMPROMOTION_CoachMLC_BIRTHDAY_600_20180817.txt',
		],
		'url' => [
			'800' => 'https://mp.weixin.qq.com/s?__biz=MjM5ODE2NzA4MA%3D%3D&mid=525052325&idx=1&sn=40c72307c7a9491f899e7c52ee0fbdef&chksm=3c71dee20b0657f4954846a435ea1a45574fc69a84195b5af8c0cd0612b518734ce80998ed4e#rd',
			'600' => 'https://mp.weixin.qq.com/s?__biz=MjM5ODE2NzA4MA%3D%3D&mid=525052327&idx=1&sn=7076715c795f63c6f282b5f0acf08f82&chksm=3c71dee00b0657f624f12800dfe3098fa91c23344c85de90595854ce40bff1a21cbcc51542b1#rd',
		],
	],
];
>>>>>>> d9084eb8bf6992dcb32700cfad6822295d0e2719
