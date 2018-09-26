<?php

namespace App\Command;

use Allegro\Console\Command;
use Allegro\Console\InputInterface;
use Allegro\Console\OutputInterface;
use App\Coach\TemplateMsgSend;

class ImportBirthdayCommand extends Command
{
    public function configure()
    {
    	$this
        ->setName('tpl:import:birthday')
        ->setDescription('Import birthday.');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getApplication()->getKernel()->getContainer();
        $coachBirthday = $container->getParameter('coach.birthday.tpl');
        $rootPath = $this->getApplication()->getKernel()->getProjectDir();
        $info = [];
        foreach ($coachBirthday['file'] as $birthdayFile) {
            $info['name'] = substr($birthdayFile, 0, 34);
            $info['type'] = substr($birthdayFile, 35, 3);
            $info['tag'] = substr($birthdayFile, 39, 8);
            $plain = file_get_contents($rootPath . '/' . $birthdayFile);
            $plain_array = explode("\n", $plain);
            array_shift($plain_array);
            $this->importDataToDB($plain_array, $info, $output);
        }
    }

    private function importDataToDB($data, $info, $output)
    {
        $container = $this->getApplication()->getKernel()->getContainer();
        $pdo = $container->get('pdo');
        foreach ($data as $openid) {
            $sql = "SELECT 1 FROM `users` WHERE `openid` = :openid AND `type` = :type AND `tag` = :tag";
            $query = $pdo->prepare($sql);    
            $query->execute([
                ':openid' => $openid, 
                ':type' => $info['type'], 
                ':tag' => $info['tag']
            ]);
            if(!$query->fetch(\PDO::FETCH_ASSOC)){
                $sql = "INSERT INTO `users` SET `openid` = :openid, `type` = :type, `tag` = :tag, `name` = :name, `status` = 0, `import_at` = NOW()";
                $query = $pdo->prepare($sql);   
                $res = $query->execute([
                    ':openid' => $openid, 
                    ':type' => $info['type'], 
                    ':tag' => $info['tag'],
                    ':name' => $info['name']
                ]);
                if($res) {
                    $id = $pdo->lastinsertid();
                    $output->writeln("{$id} - {$openid}");
                }      
            }
        } 

    }



}
