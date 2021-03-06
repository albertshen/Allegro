<?php

namespace Allegro\Controller;

use Allegro\Http\Request;
use Allegro\Http\Response;
use Allegro\ServiceContainer\ContainerBuilder;

class Controller
{

	protected $response;

	protected $request;

	protected $container;

	public function __construct(ContainerBuilder $container, Request $request) 
	{
		$this->container = $container;
		$this->request = $request;
		$this->response = new Response();
	}

	public function get($id)
	{
		return $this->container->get($id);
	}

	public function getParameter($name)
	{
		return $this->container->getParameter($name);
	}

    public function getRequest()
    {
		return $this->request;
    }

    public function statusPrint($status, $msg = '') 
    {
        $data = array();
        if($status || $status == 0)
            $data['status'] = $status;
        if($msg)
            $data['msg'] = $msg;
        header("Content-type: application/json");
       	return  $this->response->setContent(json_encode($data));
    }

    public function dataPrint($data) 
    {
        header("Content-type: application/json");
       	return  $this->response->setContent(json_encode($data));
    }

	public function redirect($uri) 
	{
		$this->response->redirect($uri);
	}

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

	public function render($tpl_name, $params = array())
	{
		return $this->get('template')->render($tpl_name, $params);
		// $template = new Theme();
		// $data = $template->theme($tpl_name, $params);
		// return $this->response->setContent($data);
		// $response->send();
	}

}