<?php
$dbms='mysql';     //数据库类型
$host='localhost'; //数据库主机名
$dbName='db_api';    //使用的数据库
$user='root';      //数据库连接用户名
$pass='';          //对应的密码
$dsn="$dbms:host=$host;dbname=$dbName";
$pdo = new PDO($dsn,$user,$pass);
$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES,false);

//dump($pdo);
return $pdo;
?>