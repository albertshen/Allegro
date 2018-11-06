<?php

namespace Allegro\Http;

class Request
{
	public $request;

	public $query;

	public $params;

	public $validation;

	private $server;

	private $content;

    public function __construct(array $query = array(), array $request = array(), array $attributes = array(), array $cookies = array(), array $files = array(), array $server = array(), $content = null)
    {
        $this->initialize($query, $request, $attributes, $cookies, $files, $server, $content);
    }

    /**
     * Creates a new request with values from PHP's super globals.
     *
     * @return static
     */
    public static function createFromGlobals()
    {
    	$request =  new static($_GET, $_POST, array(), $_COOKIE, $_FILES, $_SERVER);
    	if ($data = json_decode($request->getContent(), true)) {
    		$request->request = new ParameterBag($data);
        }
        return $request;
    }

    /**
     * Sets the parameters for this request.
     *
     * This method also re-initializes all properties.
     *
     * @param array                $query      The GET parameters
     * @param array                $request    The POST parameters
     * @param array                $attributes The request attributes (parameters parsed from the PATH_INFO, ...)
     * @param array                $cookies    The COOKIE parameters
     * @param array                $files      The FILES parameters
     * @param array                $server     The SERVER parameters
     * @param string|resource|null $content    The raw body data
     */
    public function initialize(array $query = array(), array $request = array(), array $attributes = array(), array $cookies = array(), array $files = array(), array $server = array(), $content = null)
    {
        $this->request = new ParameterBag($request);
        $this->query = new ParameterBag($query);
        $this->server = $server;
    }

    /**
     * Returns the request body content.
     *
     * @param bool $asResource If true, a resource will be returned
     *
     * @return string|resource The request body content or a resource to read the body stream
     *
     * @throws \LogicException
     */
    public function getContent()
    {
		if ($content = file_get_contents('php://input')) {
			return $this->content = $content;
		}
		return $this->content = null;
    }

	public function getRequestUri() {
		return $_SERVER['REQUEST_URI'];
	}

	public function getHttpReferer()
	{
		if (!empty($_SERVER['HTTP_REFERER'])) {
			return $_SERVER['HTTP_REFERER'];
		}
		return null;
	}

	public function getDomain() {
		$domain = $_SERVER['HTTP_HOST'];
		$port = strpos($domain, ':');
		if ( $port !== false ) $domain = substr($domain, 0, $port);
		return $domain;
	}

	public function getUrl($absolute = false){
		if($absolute) {
			return $url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
		}
		return $_SERVER['REQUEST_URI'];
	}

	public function getRouter(){
		return preg_replace('/\?.*/', '', $_SERVER['REQUEST_URI']);
	}

	public function validation($fields) {
		if($this->request) {
			$this->validRules($fields, $_POST);
			$_POST = $this->validation;
		}
		if($this->query) {
			$this->validRules($fields, $_GET);
			$_GET = $this->validation;
		}
	}

	public function validRules($fields, $raw) {
		$data = array();
		foreach($fields as $field => $info) {
			if(!isset($this->params[$field])) {
				$response = new Response;
		        $response->statusPrint('999');
			}
		    $value = trim($raw[$field]);
		    if($info) {
		      if($info[0] == 'notnull' && $value == '') {
		        $code = isset($info[1]) ? $info[1] : '999';
		        $response = new Response;
		        $response->statusPrint($code);
		      }
		      if($info[0] == 'date' && !strtotime($value)){
		        $code = isset($info[1]) ? $info[1] : '999';
		        $response = new Response;
		        $response->statusPrint($code);
		      }
	          if($info[0] == 'cellphone' && !preg_match("/^1\d{10}$/", $value)){
	          	 $code = isset($info[1]) ? $info[1] : '999';
		        $response = new Response;
		        $response->statusPrint($code);
	          }
		    }
		    $data[$field] = $value;
		}
		$this->validation = $data;
	}

	public function generateUrl($router, $query = array(), $absolute = false){
		if($query) {
			$url = $router . '?' .http_build_query($query);
		} else {
			$url = $router;
		}
		if($absolute) {
			$base_url = 'http://' . $_SERVER['HTTP_HOST'];
			return $url = $base_url  . '/' . $url;
		}
		return $url;
	}
}
