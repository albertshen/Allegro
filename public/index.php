<?php

use App\Kernel;
use Allegro\Http\Request;
// use Symfony\Component\HttpFoundation\Request;

require __DIR__.'/../vendor/autoload.php';

$env = 'dev';
$debug = 0;

$kernel = new Kernel($env, $debug);
$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();
//$kernel->terminate($request, $response);
