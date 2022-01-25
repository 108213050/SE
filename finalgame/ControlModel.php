<?php
require_once("dbconfig.php");
$playresult;
function addnum($num) {
	global $db;
	$sql = "insert into game (num) values (?)";
	$stmt = mysqli_prepare($db, $sql); //prepare sql statement
	mysqli_stmt_bind_param($stmt, "i", $num); //bind parameters with variables
	mysqli_stmt_execute($stmt);  //執行SQL
	return true;
}
function GameID(){
	// SELECT `gameID` FROM `game` GROUP BY gameID DESC LIMIT 1
	global $db;
	// SELECT * FROM `user` WHERE `id` = 1
	// 最後開的遊戲
	$sql = "select `gameID` from `game` group by `gameID` desc limit 1";
	$stmt = mysqli_prepare($db, $sql); //prepare sql statement
	// mysqli_stmt_bind_param($stmt); //bind parameters with variables
	mysqli_stmt_execute($stmt);  //執行SQL
	$result = mysqli_stmt_get_result($stmt); 
	$gameID;
	while (	$rs = mysqli_fetch_assoc($result)) {
		$gameID = $rs['gameID'];
	}
	// echo $gameID;
	return $gameID;
}
// 新增下注
function addstake($number,$money){
	global $db;
	$gid = GameID();
	$uid = $_SESSION['id'];
	$sql = "insert into stake (gameID,userID,num,stake) values (?,?,?,?)";
	$stmt = mysqli_prepare($db, $sql); //prepare sql statement
	mysqli_stmt_bind_param($stmt, "iiii", $gid,$uid,$number,$money); //bind parameters with variables
	mysqli_stmt_execute($stmt);  //執行SQL
	// echo $gid;
	return true;
}

function PlayerId($username){
	global $db;
	// SELECT * FROM `user` WHERE `id` = 1
	$sql = "select `id` from `user` where `username` = ?";
	$stmt = mysqli_prepare($db, $sql); //prepare sql statement
	mysqli_stmt_bind_param($stmt, "i", $num); //bind parameters with variables
	mysqli_stmt_execute($stmt);  //執行SQL
	$result = mysqli_stmt_get_result($stmt); 
	while (	$rs = mysqli_fetch_assoc($result)) {
		$tArr=array();
		$tArr['id']=$rs['id'];
		$retArr[] = $tArr;
	}
	return $retArr;
}
function checkPacket(){
	global $db;
	// SELECT money FROM `user` WHERE `id` = 1
	// SELECT `money` FROM `user` WHERE id = 1
	$id = $_SESSION['id'];
	$sql = "select money from user where id = ?";
	$stmt = mysqli_prepare($db, $sql); //prepare sql statement
	mysqli_stmt_bind_param($stmt, "i", $id); //bind parameters with variables
	mysqli_stmt_execute($stmt);  //執行SQL
	$result = mysqli_stmt_get_result($stmt); 
	$retArr=array();
	while (	$rs = mysqli_fetch_assoc($result)) {
		$packetmoney = $rs['money'];
	}
	return $packetmoney;

}
function  loadList(){
	global $db;
	$uid = $_SESSION['id'];
	$gid = GameID();
	// SELECT * FROM `stake` WHERE userID = 2 and gameID = 122
	$sql = "select * from stake where userID =? and gameID = ?;";
	$stmt = mysqli_prepare($db, $sql );
	mysqli_stmt_bind_param($stmt,"ii",$uid,$gid); //bind parameters with variables
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt); 

	$retArr=array();
	
	while (	$rs = mysqli_fetch_assoc($result)) {
		$retArr['gameID']=$rs['gameID'];
		$retArr['userID']=$rs['userID'];
		$retArr['num']=$rs['num'];
		$retArr['stake']=$rs['stake'];
	}
	return $retArr;
}
// 更新皮包
function UPmoney($id,$packetmoney)
{
	global $db;
	$uid = $id;
	$sql = "update user set money=? where id=?;";
	$stmt = mysqli_prepare($db, $sql); //prepare sql statement
	mysqli_stmt_bind_param($stmt, "ii", $packetmoney,$uid); //bind parameters with variables
	mysqli_stmt_execute($stmt);  //執行SQL
	return true;
}

// 取得玩家下注列表
function GetPlayerList(){
	global $db;
	$gid = GameID();
	// SELECT * FROM `stake` WHERE userID = 2 and gameID = 122
	$sql = "select * from `stake` where gameID = ?;";
	$stmt = mysqli_prepare($db, $sql );
	mysqli_stmt_bind_param($stmt,"i",$gid); //bind parameters with variables
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt); 
	$retArr=array();
	while (	$rs = mysqli_fetch_assoc($result)) {
		$arr=array();
		$arr['gameID']=$rs['gameID'];
		$arr['userID']=$rs['userID'];
		$arr['num']=$rs['num'];
		$arr['stake']=$rs['stake'];
		$retArr[] = $arr;
	}
	return $retArr;
}
function RankList(){
	global $db;
	$sql = "select * from `user` where `role`=1 order by money desc;";
	$stmt = mysqli_prepare($db, $sql );
	// mysqli_stmt_bind_param($stmt); //bind parameters with variables
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt); 
	$retArr=array();
	while (	$rs = mysqli_fetch_assoc($result)) {
		$arr=array();
		$arr['username']=$rs['username'];
		$arr['money']=$rs['money'];
		$retArr[] = $arr;
	}
	return $retArr;
}
// 取得中獎數字
function gamenum()
{
	global $db;
	$gameID = GameID();
	// SELECT * FROM `stake` WHERE userID = 2 and gameID = 122
	$sql = "select num from game where gameID = ?;";
	$stmt = mysqli_prepare($db, $sql);
	mysqli_stmt_bind_param($stmt,"i",$gameID); //bind parameters with variables
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt); 
	// $retArr=array();
	$gamenum;
	while (	$rs = mysqli_fetch_assoc($result)) {
		$gamenum = $rs['num'];
	}
	return $gamenum;
}
function Getpacket($id,$role)
{
	global $db;
	$rolenum;
	// 假設$role == host ,player 
	if($role == "host"){
		$rolenum = 0;
		$uid = $_SESSION['id'];
	}else if($role == "player"){
		$rolenum = 1;
		$uid = $id;
	}
	// SELECT * FROM `stake` WHERE userID = 2 and gameID = 122
	$sql = "select money from user where role = ? and id = ?;";
	$stmt = mysqli_prepare($db, $sql);
	mysqli_stmt_bind_param($stmt,"ii",$rolenum,$uid); //bind parameters with variables
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt); 
	$packet;
	while (	$rs = mysqli_fetch_assoc($result)) {
		$packet = $rs['money'];
	}
	return $packet;
}
function getresult()
{
	// 檢查猜數字是否與此
	global $db;
	$uid = $_SESSION['id'];
	$gid = GameID();
	// 此場遊戲的數字
	$Num = gamenum();
	// SELECT * FROM `stake` WHERE userID = 2 and gameID = 122
	$sql = "select num from `stake` where gameID = ? and userID = ?;";
	$stmt = mysqli_prepare($db, $sql );
	mysqli_stmt_bind_param($stmt,"ii",$gid,$uid); //bind parameters with variables
	mysqli_stmt_execute($stmt);
	$result = mysqli_stmt_get_result($stmt); 
	while (	$rs = mysqli_fetch_assoc($result)) {
		$guessnum=$rs['num'];
	}
	if($Num == $guessnum){
		return true;
	}else {
		return false;
	}
	
}
// 開獎
function open()
{
	$hostID = $_SESSION['id'];
	// 取得此遊戲玩家列表
	$playerList = GetPlayerList();
	// 取得這場遊戲的數字
	$Num = gamenum();
	// 取得莊家錢包
	$HostPacket = Getpacket($hostID,"host");
	
	// 逐一比對
	for ($i = 0;$i< count($playerList);$i++){
		// num == palyer[i]的number 
		
		// 取得此遊戲中有下注玩家的錢包
		$Playerpacket = Getpacket($playerList[$i]['userID'],"player");
		if ($Num == $playerList[$i]['num']){
			// 代表莊家要賠錢
			// 莊家錢包扣 palyer[i]的money*5
			$Playerpacket += 5*$playerList[$i]['stake'];
			$HostPacket -= 5*$playerList[$i]['stake'];
		}else {
			// num != palyer[i]的number
			// 莊家錢包加 palyer[i]的money*5
			$Playerpacket -= $playerList[$i]['stake'];
			$HostPacket += $playerList[$i]['stake'];
		}
		UPmoney($playerList[$i]['userID'],$Playerpacket);
	}
	UPmoney($hostID,$HostPacket);
	// 更新
	return true;
	
}

?>

