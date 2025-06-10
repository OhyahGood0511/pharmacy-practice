<?php
$dsn = "mysql:host=localhost;dbname=pharmacy;charset=utf8mb4";
$user = "sales";
$password = "123456";
$link = new PDO($dsn, $user, $password);
date_default_timezone_set("Asia/Taipei");
?>