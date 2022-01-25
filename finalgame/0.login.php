<?php
require("dbconfig.php");
	// http head
	// session_start(); //啟用session 功能, 必須在php程式還沒輸出任何訊息之前啟用
	$username = $_POST["username"];
	$password = $_POST["password"];
	
	$sql = "select username,role,id from user where password=?;";
	// $sql = "select  from user where password=PASSWORD(?);";
	$stmt = mysqli_prepare($db, $sql );
	mysqli_stmt_bind_param($stmt, "s", $password);
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt); 
	if ($rs = mysqli_fetch_assoc($result)) {
		// 去判別玩家還是莊家
		// 分別導入不同的路徑
		if ($rs['username'] == $username) {
			$_SESSION["username"] = $username; //宣告session 變數並指定值
			//$_SESSION["role"] = $rs['role']; //宣告session 變數並指定值
			$_SESSION["role"] = $rs['role']; //宣告session 變數並指定值
			$_SESSION["id"] = $rs['id'];
			// host:0 player:1
			if ($_SESSION["role"] == 0){
				header("Location: host.php");
			}elseif($_SESSION["role"] == 1){
				header("Location: player.php");
			}
		} else {
			$_SESSION["username"] = '';
			$_SESSION["role"] = '';
			header("Location: 0.loginUI.php");
		}
	}else{
		header("Location: 0.loginUI.php");
	}
	
	
	
	
?>
