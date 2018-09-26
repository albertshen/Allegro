<?php

namespace App\Command;

use Allegro\Console\Command;
use Allegro\Console\InputInterface;
use Allegro\Console\OutputInterface;
use App\Coach\TemplateMsgSend;

class AppCommand extends Command
{
    public function configure()
    {
    	$this
        ->setName('tpl:send:birthday')
        ->setDescription('Creates a new user.');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        //oKCDxjvvyfIqg0lKXNJrc0szWzSg albert
        //oKCDxjprjzRyM-0i-Egomz47kL1k 直姐

    	$container = $this->getApplication()->getKernel()->getContainer();
    	$sender = $container->get('tplmsg.sender');
        $userList = $sender->getOpenidList();
        foreach($userList as $user) {
            $re = $sender->sendBirthday($user);
            if($re->code == 200 && $re->data->errcode == 0) {
                $sender->updateSendStatus($user['id']);
                $output->writeln("{$re->code} - {$user['openid']} - {$user['type']}");
            }
        }

    }
}
