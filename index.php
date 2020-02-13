<?php
require __DIR__.'/lib/User.php';
require __DIR__.'/lib/Article.php';
$db=require __DIR__.'/lib/db.php';

//var_dump($pdo);
$user = new User($db);
//注册
// $a=$user->register('lkf','123');
// print_r($a);
//登录
// $a=$user->login('admin','123');
$article = new Article($db);
//$a=$article->create('title','asdashsgfjhgufyuisguijdgfiuiyidsa','3');
//print_r($article->view(2));

//print_r($article->getList(3));
//

try {
	throw new Exception("Error Processing Request", 1);
	
} catch (Exception $e) {
	var_dump($e);
}


 