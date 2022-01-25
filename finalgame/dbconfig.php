<?php
session_start();
/*
連線資料庫用的副程式
*/
$host = 'localhost'; //執行DB Server 的主機
$user = 'root'; //登入DB用的DB 帳號
$pass = ''; //登入DB用的DB 密碼
$dbName = 'gambling'; //使用的資料庫名稱
/* $db 即為未來執行SQL指令所使用的物件 */
$db = mysqli_connect($host, $user, $pass, $dbName) or die('Error with MySQL connection'); //跟MyMSQL連線並登入

mysqli_query($db,"SET NAMES utf8"); //設定編碼為 unicode utf8，減少亂碼的可能

// function checkAccessRole($reqRole) {
// 	if (isset($_SESSION['role']) and $_SESSION['role'] == $reqRole) {
// 		return true;
// 	} else {
// 		return false;
// 	}
// }

// function checkAccess($reqLevel) {
// 	if (isset($_SESSION['role']) and $_SESSION['role'] >= $reqLevel) {
// 		return True;
// 	} else {
// 		return False;
// 	}
// }
// // 檢查冠票數
// function checknum($num){
// 	// 如果沒有投過票
// 	if(isset($_SESSION['role']) and $num<1){
// 		return True;
// 	} else {
// 		// 如果有投過票
// 		return False;
// 	}

// }
?>