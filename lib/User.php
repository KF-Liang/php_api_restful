<?php
require_once __DIR__.'/ErrorCode.php';
class User{

	private $_db;

	public function __construct($_db){
		$this->_db=$_db;
	}

	//用户登陆
	public function login($username,$password){
		if(empty($username)){
			throw new Exception("用户名不能为空", ErroCode::USERNAME_CANNOT_EMPTY);
		}
		if(empty($password)){
			throw new Exception("密码不能为空", ErroCode::PASSWORD_CANNOT_EMPTY);
		}
		$sql= 'select * from `user` where `username` = :username AND `password`=:password';
		$stmt = $this->_db->prepare($sql);
		$password=$this->_md5($password);
		$stmt->bindParam(':username',$username);
		$stmt->bindParam('password',$password);
		$stmt->execute();
		$user = $stmt->fetch(PDO::FETCH_ASSOC);
		if(empty($user)){
			throw new Exception("用戶名或密码错误", ErroCode::USERNAME_OR_PASWORD_INVALID);
		}
			unset($user['password']);
			return $user;
	}



	//用户注册
	public function register($username,$password){ 
		if(empty($username)){
			throw new Exception("用户名不能为空", ErroCode::USERNAME_CANNOT_EMPTY);
			
		}
		if($this->_isUserNameExists($username)){
			 throw new Exception("用户名存在", ErroCode::USERNAME_EXISTS);
		}
		if(empty($password)){
			throw new Exception("密码不能为空", ErroCode::PASSWORD_CANNOT_EMPTY);
		}
		//写入数据
		$sql ='INSERT INTO `user`(username,`password`,`createdAt`) VALUES (:username,:password,:createdAt)';
		$createdAt=time();
		$password=$this->_md5($password);
		$stmt=$this->_db->prepare($sql);
		$stmt->bindParam(':username',$username);
		$stmt->bindParam(':password',$password);
		$stmt->bindParam(':createdAt',$createdAt);

		if(!$stmt->execute()){
			throw new Exception("注册失败", ErroCode::REGISTER_FAIL);
		}

		return [
			'userId'=>$this->_db->lastInsertId(),
			'username'=>$username,
			'createdAt' =>$createdAt
			//不能返回密碼
		];

	}
	//md5
	private function _md5($string,$key='immoc'){
		return md5($string.$key);
	}

	//用户名认证
	private function _isUserNameExists($username){
		$sql = 'select * from `user` where username=:username';
		//预处理
		$stmt = $this->_db->prepare($sql);
		//绑定
		$stmt->bindParam(':username',$username);
		$stmt->execute();
		$result = $stmt->fetch(PDO::FETCH_ASSOC);
		return !empty($result);


	}
}