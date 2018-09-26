<?php

$routes = [];
//System
$routes['/test/dsf/saf'] = ['App\Controller\ApiController', 'callback'];
$routes['/test/%/one/%/two'] = ['App\Controller\ApiController', 'callback'];
//System end

//Campaign
$routes['/ajax/post'] = array('CampaignBundle\Api', 'form');
$routes['/'] = array('CampaignBundle\Page', 'index');


return $routes;