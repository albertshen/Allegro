<?php

namespace Allegro\Kernel\Router;

class Router
{
	private $callback;
	private $param;

	public function __construct($request = '')
	{
		if($request)
			$this->setCallback($request);
	}

	public function setCallback($request)
	{
		include_once dirname(__FILE__) . "/../config/router.php";
		$server = $request->getServer();
		$current_router = preg_replace('/\?.*/', '', $server['REQUEST_URI']);
		
		if(isset($routers[$current_router])) {
			$callbackConfig = $routers[$current_router];
			$class = $callbackConfig[0].'Controller';
			$callback = [new $class, $callbackConfig[1].'Action'];
			$parameter = isset($callbackConfig[2]) ? $callbackConfig[2] : array();
			$this->callback = $callback;
			$this->param = $parameter;
            return;
		}
		foreach($routers as $router => $callbackConfig) {
			$pattern = '/^' . preg_replace(array('/\//', '/%/'), array('\/', '(.*)'), $router) . '$/';
			if(preg_match($pattern, $current_router, $matches)  && $router != '/') {
				unset($matches[0]);
				$class = $callbackConfig[0].'Controller';
				$callback = [new $class, $callbackConfig[1].'Action'];
				$this->callback = $callback;
				$this->param = $matches;
				return;
			}		
		}
	}

	public function getCallback()
	{
		return $this->callback;
	}

	public function getCallbackParam()
	{
		return $this->param;
	}

	public function generateUrl($router, $query = array(), $absolute = false){
		if($query) {
			$url = $router . '?' .http_build_query($query);
		} else {
			$url = $router;
		}
		if($absolute) {
			if(BASE_URL) {
				$base_url = BASE_URL;
			} else {
				$base_url = 'http://' . $_SERVER['HTTP_HOST'];
			}
			return $url = $base_url  . '/' . $url;
		}
		return $url;
	}
}



