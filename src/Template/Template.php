<?php

namespace App\Template;

class Template
{
	private $basePath;

	public function __construct()
	{
		$this->basePath = __DIR__ . '/Templates/';
	}

	function render($template, $data, $level = 'block') 
	{
		ob_start();
		extract($data);
		include_once($this->basePath.$template.'.tpl.php');
		$string = ob_get_contents();
		ob_end_clean();
		return $string;
	}
}