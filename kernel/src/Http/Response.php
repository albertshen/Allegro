<?php

namespace Allegro\Http;

class Response
{
    private $content;

    public function __construct($content = '') 
    {
        $this->content = $content;
    }

    public function send() {

        $this->sendContent();

        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }

        return $this;
    }

    public function setContent($content)
    {
        $this->content = $content;
        return $this;
    }

    public function getContent()
    {
        return $this->content;
    }

    /**
     * Sends content for the current web response.
     *
     * @return $this
     */
    public function sendContent()
    {
        echo $this->content;

        return $this;
    }

    public function redirect($uri) 
    {
        Header('Location:' . $uri);
        exit;
    }
}