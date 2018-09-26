<?php

namespace App\Coach;

class TemplateMsgSender
{

    public function __construct($pdo, $config) 
    {
        $this->_pdo = $pdo;
        $this->config = $config;
        $this->table = $config['prod'] ? 'users' : 'users_test';
    }

    public function getOpenidList()
    {
        $sql = "SELECT id, openid, type, status FROM {$this->table} 
                WHERE status = :status AND tag = :tag
                ";
        $query = $this->_pdo->prepare($sql);
        $query->execute([':status' => '0', ':tag' => $this->config['tag']]);
        $data = $query->fetchAll(\PDO::FETCH_ASSOC);
        if($data) {
            return $data;
        }
        return [];
    }

    public function updateSendStatus($id)
    {
        $sql = "UPDATE {$this->table} SET status = 1, send_at = NOW() WHERE `id` = :id";
        $query = $this->_pdo->prepare($sql);    
        return $query->execute([':id' => $id]);      
    }

    public function sendBirthday($user) {
    	$type = $user['type'];
    	$month = date('n');
    	$lastDay = date('n月d日', strtotime("last day of this month"));
	    $data = array(
	        'touser' => $user['openid'],
	        'template_id' => 'ozuGBtnLptVFXUYaNHAaiZQXz45cykdOTOyy7mEFXKk',
	        'url' => $this->config['url'][$type],
	        'topcolor' => '#000000',
	        'data' => array(
	            'first' => array(
	                'value' => "请查收你的{$month}月生日礼。",
	                'color' => '#000000'
	            ),
	            'keyword1' => array(
	                'value' => "COACH {$type}元生日礼券",
	                'color' => '#000000'
	            ),
	            'keyword2' => array(
	                'value' => $lastDay,
	                'color' => '#000000'
	            ),
	        )
	    );
	    return $this->sendTplMessage($data);
    }

	public function sendTplMessage($data) {
	    $api_url = "http://coach.samesamechina.com/v2/wx/template/send?access_token=zcBpBLWyAFy6xs3e7HeMPL9zWrd7Xy";
	    $rs = $this->postData($api_url, $data);
	    return json_decode($rs);
	}

	public function postData($api_url, $data) {
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $api_url );
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_HEADER, 0 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, json_encode($data) );
		$return = curl_exec ( $ch );
		return $return;
		curl_close ($ch);
	}

}