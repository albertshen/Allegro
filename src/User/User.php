<?php

namespace App\User;

use Allegro\Http\Request;

class User
{
	const ENCRYPT_KEY = '29FB77CB8E94B358';

	const ENCRYPT_IV = '6E4CAB2EAAF32E90';

	const USER_KEY = 'user_prod_v1';

	private $pdo;

	private $storage;

	public function __construct($pdo, $storage)
	{
		$this->pdo = $pdo;

		$this->storage = $storage;
	}

	public function isLogin()
	{
	    if ($this->storage == 'COOKIE') {
	      if(isset($_COOKIE[self::USER_KEY])) {
	        return $this->decodeUser($_COOKIE[self::USER_KEY]);
	      }
	    } 
	    return false;
	}

	public function load($openid = 0){
		if($openid) {
			if($user = $this->findUserByOpenid($openid)) {
				return $user;
			} else {
				return false;
			}
		} else {
			if($user = $this->isLogin()){
				return $this->findUserByUid($user->uid);
			} else {
				return false;
			}
		}
	}

	public function loginByOpenid($openid){
		$user = $this->findUserByOpenid($openid);
		if($user) {
			return $this->loginFinalize($user);
		}
		return false;
	}

	public function register($userinfo){
		$user = $this->createUser($userinfo);
		return $this->loginFinalize($user);
	}

	public function logout()
	{
		$request = new Request();
		setcookie(self::USER_KEY, '', time(), '/', $request->getDomain());
	}

	public function loginFinalize($user) {
	if ($this->storage == 'COOKIE') {
		$request = new Request();
		$user = $this->finalizeDataNormalize($user);
		setcookie(self::USER_KEY, $this->encodeUser($user), time() + 3600 * 24 * 100, '/', $request->getDomain());
	}
	//$_SESSION[self::USER_KEY] = json_encode($user);
	return $user;
	}

	public function finalizeDataNormalize($data)
	{
		$user = new \stdClass();
		$user->uid = $data->uid;
		$user->openid = $data->openid;
		return $user;
	}

	public function userNormailize($userinfo) {
		$user = new \stdClass();
		if(isset($userinfo['openid'])) 
		  $user->openid = $userinfo['openid'];
		if(isset($userinfo['nickname'])) 
		  $user->nickname = $userinfo['nickname'];
		if(isset($userinfo['sex'])) 
		  $user->sex = $userinfo['sex']; 
		if(isset($userinfo['city'])) 
		  $user->city = $userinfo['city']; 
		if(isset($userinfo['province'])) 
		  $user->province = $userinfo['province']; 
		if(isset($userinfo['country'])) 
		  $user->country = $userinfo['country']; 
		if(isset($userinfo['headimgurl'])) 
		  $user->headimgurl = $userinfo['headimgurl']; 
		if(isset($userinfo['unionid'])) 
		  $user->unionid = $userinfo['unionid']; 
		if(isset($userinfo['is_subscribe'])) 
		  $user->subscribe = $userinfo['is_subscribe'] == 'false' ? 0 : 1; 
		return $user;
	}

	public function testUserLogin()
	{
		if (!$this->loginByOpenid('testuser')) {
			$userinfo = [
				'openid' => 'testuser',
				'nickname' => '🌸Alice🌸',
				'headimgurl' => 'http://thirdwx.qlogo.cn/mmopen/ajNVdqHZLLAictB0Gsg1m5nFibxaG0lDZDsMv5H2e0ItodZrUtmCldQz1DdeQrDfTpm9NHmHL58UKOL2Lj57j5tg/132',
			];
			$this->register($userinfo);
		}
	}

	/**
	* Create user in database
	*/
	public function createUser($userinfo) 
	{
		$userinfo = $this->userNormailize($userinfo);
		$sql = "INSERT INTO `user` SET `openid` = :openid, `nickname` = :nickname, `sex` = :sex, `city` = :city, `province` = :province, `headimgurl` = :headimgurl, `country` = :country, `unionid` = :unionid, `subscribe` = :subscribe, `created` = :created, `updated` = :updated";
		$condition[':openid'] = isset($userinfo->openid) ? $userinfo->openid : '';
		$condition[':nickname'] = isset($userinfo->nickname) ? $userinfo->nickname : '';
		$condition[':sex'] = isset($userinfo->sex) ? $userinfo->sex : '';
		$condition[':city'] = isset($userinfo->city) ? $userinfo->city : '';
		$condition[':province'] = isset($userinfo->province) ? $userinfo->province : '';
		$condition[':headimgurl'] = isset($userinfo->headimgurl) ? $userinfo->headimgurl : '';
		$condition[':country'] = isset($userinfo->country) ? $userinfo->country : '';
		$condition[':unionid'] = isset($userinfo->unionid) ? $userinfo->unionid : '';
		$condition[':subscribe'] = isset($userinfo->subscribe) ? $userinfo->subscribe : 0;
		$date = date('Y-m-d H:i:s');
		$condition[':created'] = $date;
		$condition[':updated'] = $date;
		$query = $this->pdo->prepare($sql);   
		$res = $query->execute($condition);
		if($res) {
		  return $this->findUserByUid($this->pdo->lastinsertid());
		}
		return null;
	}

	/**
	* Update user in database
	*/
	public function updateUser($userinfo) 
	{
		$userinfo = $this->userNormailize($userinfo);
		$sql = "UPDATE `user` SET `nickname` = :nickname, `sex` = :sex, `city` = :city, `province` = :province, `headimgurl` = :headimgurl, `country` = :country, `unionid` = :unionid, `subscribe` = :subscribe, `updated` = :updated WHERE `openid` = :openid";
		$condition[':openid'] = isset($userinfo->openid) ? $userinfo->openid : '';
		$condition[':nickname'] = isset($userinfo->nickname) ? $userinfo->nickname : '';
		$condition[':sex'] = isset($userinfo->sex) ? $userinfo->sex : '';
		$condition[':city'] = isset($userinfo->city) ? $userinfo->city : '';
		$condition[':province'] = isset($userinfo->province) ? $userinfo->province : '';
		$condition[':headimgurl'] = isset($userinfo->headimgurl) ? $userinfo->headimgurl : '';
		$condition[':country'] = isset($userinfo->country) ? $userinfo->country : '';
		$condition[':unionid'] = isset($userinfo->unionid) ? $userinfo->unionid : '';
		$condition[':subscribe'] = isset($userinfo->subscribe) ? $userinfo->subscribe : 0;
		$date = date('Y-m-d H:i:s');
		$condition[':updated'] = $date;
		$query = $this->pdo->prepare($sql);   
		$query->execute($condition);
	}


	/**
	* Find user in database
	*/
	public function findUserByOpenid($openid)
	{
		$sql = "SELECT `uid`, `openid`, `nickname`, `sex`, `city`, `province`, `headimgurl`, `country` FROM `user` WHERE `openid` = :openid";
		$query = $this->pdo->prepare($sql);    
		$query->execute(array(':openid' => $openid));
		$row = $query->fetch(\PDO::FETCH_ASSOC);
		if($row) {
		  return  (Object) $row;
		}
		return NULL;
	}

	/**
	* Create user in database
	*/
	public function findUserByUid($uid)
	{
		$sql = "SELECT `uid`, `openid`, `nickname`, `sex`, `city`, `province`, `headimgurl`, `country` FROM `user` WHERE `uid` = :uid";
		$query = $this->pdo->prepare($sql);    
		$query->execute(array(':uid' => $uid));
		$row = $query->fetch(\PDO::FETCH_ASSOC);
		if($row) {
		  return (Object) $row;
		}
		return NULL;
	}

	public function encodeUser($data)
	{
		$data = base64_encode($this->aes128_cbc_encrypt(self::ENCRYPT_KEY, json_encode($data), self::ENCRYPT_IV));
		return $data;
	}

	public function decodeUser($string)
	{
		$string = base64_decode($string, TRUE);
		$data = $this->aes128_cbc_decrypt(self::ENCRYPT_KEY, $string, self::ENCRYPT_IV);
		$user = json_decode($data);
		return $user;
	}

	public function aes128_cbc_encrypt($key, $data, $iv)
	{
		if(16 !== strlen($key)) $key = hash('MD5', $key, true);
		if(16 !== strlen($iv)) $iv = hash('MD5', $iv, true);
		$padding = 16 - (strlen($data) % 16);
		$data .= str_repeat(chr($padding), $padding);
		return mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, $iv);
	}

	public function aes128_cbc_decrypt($key, $data, $iv)
	{
		if(16 !== strlen($key)) $key = hash('MD5', $key, true);
		if(16 !== strlen($iv)) $iv = hash('MD5', $iv, true);
		$data = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $key, $data, MCRYPT_MODE_CBC, $iv);
		$padding = ord($data[strlen($data) - 1]);
		return substr($data, 0, -$padding);
	}

}