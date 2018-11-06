<?php

namespace App\Command;

use Allegro\Console\Command;
use Allegro\Console\InputInterface;
use Allegro\Console\OutputInterface;

class AppCommand extends Command
{
    public function configure()
    {
    	$this
        // the name of the command (the part after "bin/console")
        ->setName('app:vip:reservation')
        // the short description shown while running "php bin/console list"
        ->setDescription('Creates a new user.');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {

        $container = $this->getApplication()->getKernel()->getContainer();
        // $pdo = $container->get('pdo');
        $user = $container->get('user');
        $reservation = $container->get('reservation');
        
        $plain = file_get_contents('/vagrant/test.csv');
        //csv to array, and save to message queue
        $content_array = explode("\n", $plain);
        foreach ($content_array as $csv) {
          if (!empty($csv) && $array = str_getcsv($csv, ",", '')) {
            $openid = trim($array[0]);
            $item_id = trim($array[1]);

            $account = $user->load($openid);

            //create user
            if (!$account) {
                $userinfo = [];
                $userinfo['openid'] = $openid;
                $account = $user->createUser($userinfo);
            }

            if (!$this->isVipImport($openid)) {
                if ($reservation->hasReserved($account->uid)) {
                    $manual = 0;
                } else {
                    $manual = 1;
                    $reservation->doReserve($account->uid, $item_id);
                    $userReservation = $reservation->getReservationByUid($account->uid);
                    $date = date('m月d日', strtotime($userReservation->date));
                    $startTime = date('H:i', strtotime($userReservation->start));
                    $endTime = date('H:i', strtotime($userReservation->end));
                    $time = "{$date} {$startTime} - {$endTime}"; 
                    $item = [$openid, $time];
                    $data = $this->templateDataAssemble($item);
                    $container->get('d1m.wechat')->sendTemplateMsg($data);
                }
                $this->insertVip($openid, $manual);
                $output->writeln("{$openid} - {$manual}");
            }

          }
        }

	    // outputs a message without adding a "\n" at the end of the line
	    //$output->write('You are about to ');
	    //$output->write('create a user.');
    }

    public function isVipImport($openid)
    {
        $container = $this->getApplication()->getKernel()->getContainer();
        $pdo = $container->get('pdo');
        $sql = "SELECT `id` FROM `vip` WHERE `openid` = :openid";
        $query = $pdo->prepare($sql);    
        $query->execute(array(':openid' => $openid));
        $row = $query->fetch(\PDO::FETCH_ASSOC);
        if($row) {
          return (Object) $row;
        }
        return false;      
    }

    public function insertVip($openid, $manual)
    {
        $container = $this->getApplication()->getKernel()->getContainer();
        $pdo = $container->get('pdo');
        $sql = "INSERT INTO `vip` SET `openid` = :openid, `manual` = :manual";
        $query = $pdo->prepare($sql);   
        $res = $query->execute([':openid' => $openid, ':manual' => $manual]);     
    }

    public function templateDataAssemble($item) {

        $data = [
            'touser' => $item[0],
            'template_id' => 'tcRQUlsn3eKgDYXppw_X_t6OYx0amayCYuAVy8RAgBM',
            'url' => 'http://mp.loccitane.samesamechina.com/user/invitation',
            'data' => [
                'first' => [
                    'value' => '恭喜你成功预约',
                    'color' => '#000000'
                ],
                'keyword1' => [
                    'value' => '欧舒丹普罗旺斯探索之旅',
                    'color' => '#000000'
                ],
                'keyword2' => [
                    'value' => '上海长宁来福士',
                    'color' => '#000000'
                ],
                'keyword3' => [
                    'value' => $item[1],
                    'color' => '#000000'
                ],
                'remark' => [
                    'value' => "请携带邀请函前往",
                    'color' => '#000000'
                ]           
            ]
        ];
        return $data;
    }
}


        // $container = $this->getApplication()->getKernel()->getContainer();
     //    $pdo = $container->get('pdo');
     //    $sql = "SELECT u.openid, r.id, r.uid FROM reservation r LEFT JOIN user u ON r.uid = u.uid WHERE r.id >= 112 AND r.id <= 671";
     //    $query = $pdo->prepare($sql);
     //    $query->execute();
     //    $data = $query->fetchAll(\PDO::FETCH_ASSOC);   
     //    foreach ($data as $value) {
     //       //if($value['uid'] ==  1) {
     //            $user = $container->get('user')->load($value['openid']);
     //            $reservation = $container->get('reservation')->reservationNormalize($user);
     //            $url = 'http://mp.loccitane.samesamechina.com/user/invitation';
     //            $template = "恭喜你成功预约\n%s\n欧舒丹普罗旺斯探索之旅。请携带<a href='%s'>邀请函</a>前往。";
     //            $content = sprintf($template, $reservation->time, $url);
     //            //echo $user->uid . '  '.$reservation->openid.'  '.$content;
     //            $output->writeln([$reservation->openid. ':'.$value['id']]);
     //            //$container->get('d1m.wechat')->serviceTextMessageSend($reservation->openid, $content);
     //        //}

     //    }     

                    // $url = $this->getRequest()->generateUrl('user/invitation', [], true);
                    // $template = "恭喜你成功预约\n%s\n欧舒丹普罗旺斯探索之旅。请携带<a href='%s'>邀请函</a>前往。";
                    // $content = sprintf($template, $reservation->time, $url);
                    // $this->get('d1m.wechat')->serviceTextMessageSend($reservation->openid, $content);