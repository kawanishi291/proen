<?php
//データベースの接続と選択
$hostdbname = 'mysql:host=localhost;dbname=proen;charset=UTF8';
$username = "pi";
$password = "raspberry";
$pdo = new PDO($hostdbname, $username, $password);
$pdo -> setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
?>