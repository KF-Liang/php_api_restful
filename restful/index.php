<?php
require_once __DIR__.'/../lib/User.php';
require_once __DIR__.'/../lib/Article.php';
$db=require __DIR__.'/../lib/db.php';

class Restful{
	private $_user;
	private $_article;
	private $_requestMethod;//请求方法
	private $_resourceName;//请求资源的名称
	private $_id;//请求资源的id
	private $_allowResources=['users','articles'];//允许请求的资源列表
	private $_allowResourcesMethods = ['GET','POST','PUT','DELETE','OPTIONS'];//允许请求的http方法

	//常用状态码
	private $_statusCodes = [
		200 =>'ok',
		204 =>'No content',
		400 =>'Bad Request',
		401 =>'Unauthorized',
		403 =>'Forbidden',
		404 =>'Not Found',
		405 =>'Method Not Allowed cccc',
		500 =>'Internal Server Error  xxxxx'
	];


	public function __construct(User $_user,article $_article){
		$this->_user = $_user;
		$this->_article=$_article;
	}


	private function _setupResource(){
		$path = $_SERVER['PATH_INFO'];
		$params = explode('/', $path);
		$this->_resourceName = $params[1];
		if(!in_array($this->_resourceName, $this->_allowResources)){
			throw new Exception("请求资源不被允许", 400);
		}

		if(!empty($params[2])){
			$this->_id = $params[2];
		}
	}

	private function _setupRequestMethod(){
		$this->_requestMethod = $_SERVER['REQUEST_METHOD'];
		if(!in_array($this->_requestMethod, $this->_allowResourcesMethods)){
			throw new Exception("请求方法不被允许", 405);
		}
	}



	//请求用户资源	(注册)
	//返回數組
	private function _handleUser(){
			if($this->_requestMethod != 'POST'){
				throw new Exception("请求方法不被允许", 405);
			}

			$body = $this->_getBoyParams();
			
			if(empty($body['username'])){
				throw new Exception("用户名不能为空", 400);
			}	
			if(empty($body['password'])){
				throw new Exception("密码不能为空", 400);
			}
			$data =  $this->_user->register($body['username'],$body['password']);		
			return $data;
	}

	//请求文章资源
	private function _handleArticle(){
		switch (variable) {
			case 'value':
				# code...
				break;
			
			default:
				# code...
				break;
		}
	}

	//获取请求体返回的是数组
	private function _getBoyParams(){
		$raw = file_get_contents('php://input');
		if(empty($raw)){
			throw new Exception("请求参数错误", 400);
		}
		return json_decode($raw,true);
	}


	//////////////////////////////////////////////////////////////////////////////////
	private function _json($array, $code = 0){
			if($code>0 && $code<=13){
				$code =500;
			}
			if ($code >0 && $code !=200 && $code != 204) {
				header("HTTP/1.1 ".$code."  ". $this->_statusCodes[$code]);
			}
			header('Content-Type:application/json;charset=utf-8');
			echo json_encode($array,JSON_UNESCAPED_UNICODE);
			exit();
	}

		public function run(){

			try {	
				$this->_setupRequestMethod();
				$this->_setupResource();
				if($this->_resourceName == 'users'){
					return $this->_json($this->_handleUser());
				}else{
					return $this->_json($this->_handleArticle());
				}	
			} catch (Exception $e) {
				$this->_json(['error'=>$e->getMessage()],$e->getCode());
			}
	}

}

$user = new User($db);
$article = new Article($db);
$restful = new Restful($user,$article);
$restful -> run();