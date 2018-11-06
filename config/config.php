<?php

return [
	'redis.host' => '127.0.0.1',	
	'mysql.dsn' => 'mysql:host=127.0.0.1;dbname=loccitane_balloon',
	'mysql.user' => 'root',
	'mysql.pass' => '',
	'authorized.router' => [
		'/',
		'/user/invitation',
	],
	'watchdog' => [
		'D1M' => ['access_token' => 'all'],
		'SameSame' => ['wechat_message' => 'all'],
		'WeChat' => ['custom_service' => 'all', 'template_message' => 'all'],
	],
	// 'watchdog' => [
	// 	'D1M' => ['type' => 'access_token', 'status' => 'all'],
	// 	'SameSame' => ['type' => 'wechat_message', 'status' => 'all'],
	// 	'WeChat' => ['type' => 'custom_service', 'status' => 'all'],
	// ],
	'user.storage' => 'COOKIE',
	'entrance.storage' => 'COOKIE',
	'wechat.vendor' => 'internal.wechat',
	'wechat.internal' => [
		'appid' => 'wxdffb071f687633b8',
		'appsecret' => '87c79cbbf325fbf87a78ca8fe079d297',
		'token' => 'sdsf',
		'scope' => 'snsapi_userinfo',
	],
];