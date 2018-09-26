<?php

namespace Allegro\Http;

/**
 * Holds parameters.
 *
 * @author Albert Shen <albertshen1206@gmail.com>
 */
class ParameterBag
{
    /**
     * Parameter storage.
     */
    protected $parameters;

    /**
     * @param array $parameters An array of parameters
     */
    public function __construct(array $parameters = array())
    {
        $this->parameters = $parameters;
    }

	public function get($key){
		if(isset($this->parameters[$key])) {
			return $this->parameters[$key];
		} else {
			return NULL;
		}
	}
}