<?php
require('ControlModel.php');

if (isset($_REQUEST['act'])) {
	$act=$_REQUEST['act'];
} else $act='';

switch ($act) {
	// 新增下注
	case "addstake":
		$number=$_POST['number'];
		$money=$_POST['money'];
		// $note=$_POST['note'];
		if ($number>0 && $money >0) {
			addstake($number,$money);
			// echo addstake($number,$money);
		}
		// echo "OK";
		echo "OK";
		break;
	// 莊家新增猜獎數字
	case "addgamenum":
		$num=$_POST['num'];
		// $note=$_POST['note'];
		if ($num) {
			addnum($num,$note);
		}
		echo "OK";
		break;

	// 檢查玩家自己的錢包是否夠錢
	case "checkmoney":
		// 檢查是否夠多錢
		// $id=(int)$_REQUEST['id'];
		$money=(int)$_REQUEST['money'];
		if ($money >0) {
			$packetMoney = checkPacket();
			// 如果下注金額小於錢包的錢
			if($money <= $packetMoney){
				echo "OK";
			}else{
				// 
				echo "NO";
			}
			// echo "OK";
		}
		break;
	case "loadList":
		$list = loadList();
		echo json_encode($list);
		// echo $list;
		// echo '{"GOOD":"OK"}';
		break;
	case "OpenLottery":
		// 收到開獎的要求
		// 要比對
		// 莊家要賠猜中數字的玩家壓注金額5倍，猜錯的沒收。
		// 開獎後更新所有玩家的錢包金額
		open();
		// GetPlayerList();
		echo "OK";
		break;
	case "UpdatePacket":
		// 更新錢包的錢
		$role=(int)$_REQUEST['role'];
		$uid = $_SESSION['id'];
		// 莊家
		if($role == 0 ){
			$Packet = Getpacket($uid,"host");
			UPmoney($uid,$Packet);
			echo Getpacket($uid,"host");
		}else if($role == 1 ){ //玩家
			// 取得錢包
			$Packet = Getpacket($uid,"player");
			// 更新錢包
			UPmoney($uid,$Packet);
			// 回傳錢包
			echo Getpacket($uid,"player");
		}
		break;
	case "RankList":
		$list= RankList();
		echo json_encode($list);
		break;
	// 檢查玩家遊戲輸贏
	case "Playresult":
		$result = getresult();
		echo $result;
		break;
	default:
}
?>

