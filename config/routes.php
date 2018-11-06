<?php

$routes = [];
//System
$routes['/'] = ['App\Controller\PageController', 'landingPage'];
$routes['/user/invitation'] = ['App\Controller\PageController', 'userInvitation'];
$routes['/user/logout'] = ['App\Controller\PageController', 'userLogout'];
$routes['/api/message'] = ['App\Controller\ApiController', 'message'];

$routes['/check/quota'] = ['App\Controller\PageController', 'checkQuota'];

$routes['/wechat/oauth/callback'] = ['App\Controller\WeChatController', 'oauthCallback'];
$routes['/api/wechat/jssdk'] = ['App\Controller\WeChatController', 'jssdkShortInternal'];
//System end

//Campaign
$routes['/ajax/reservation/info'] = ['App\Controller\ApiController', 'reservationInfo'];
$routes['/ajax/reservation/submit'] = ['App\Controller\ApiController', 'reservationSubmit'];
$routes['/ajax/game/submit'] = ['App\Controller\ApiController', 'gameSubmit'];
$routes['/ajax/reservation/consume'] = ['App\Controller\ApiController', 'reservationConsume'];

return $routes;