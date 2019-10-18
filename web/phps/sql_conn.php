<?php
$servername = "125.65.82.84";
$username = "prick";
$password = "prick666";
$database = 'prick';
 
// 创建连接
$con = new mysqli($servername, $username, $password);
 
// 检测连接
if ($con->connect_error) {
    echo 11111111111111111;
    die("连接失败: " . $con->connect_error);
} 

