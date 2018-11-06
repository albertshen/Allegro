<?php

namespace App\Lib\WeChat;

interface WeChatAPIInterface
{
    public function getWeChatUserInfo();

    public function getAutorizedUrl();
}