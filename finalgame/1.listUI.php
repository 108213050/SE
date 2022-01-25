<?php
require("dbconfig.php");
if ( ! (checkAccess(1))) {
	header("Location: 0.loginUI.php");
}
// if ( ! (checkAccess('user') or checkAccess('admin'))) {
// 	header("Location: 0.loginUI.php");
// }
?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>無標題文件</title>
</head>

<body>

<p><?php echo "hello ! 使用者: ",$_SESSION['userID'];?>   	<a href='1.insertUI.php'>Add</a></p>
<a href='0.loginUI.php'>logout</a>
<hr />
<a href='1.listUI.php'>全部</a>
<a href='0.talk.php'>閒聊</a>
<a href='0.mood.php'>心情</a>
<a href='0.Gossip.php'>八卦</a>

<table width="200" border="1">
  <tr>
    <td>id</td>
    <td>title</td>
    <td>message</td>
    <td>name</td>
    <td>讚</td>
	<td>留言類別</td>
    <!-- <td>噓</td> -->
    <!-- <td>讚-噓</td> -->
	<td>-</td>
  </tr>
<?php

$sql = "select * from guestbook order by id desc;";
$stmt = mysqli_prepare($db, $sql );
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt); 

$count = 0;
while (	$rs = mysqli_fetch_assoc($result)) {
	//一則留言
	$count=0;
	$id = $rs['id'];
	// 依照留言的回覆訊息數，改變顯示的顏色
	//依照留言id 找到response中
	$sql_color = "select * from response order by id desc";
	$stmt_color = mysqli_prepare($db, $sql_color );
	mysqli_stmt_execute($stmt_color);
	$result_color = mysqli_stmt_get_result($stmt_color);
	//當這個mid與id相同時
	while (	$rs_color = mysqli_fetch_assoc($result_color)) {
		if ($rs_color['mid'] == $id){
			$count+=1;
		}
		
	}
	if($count <=5){
		echo "<tr><td>" , $rs['id'] ,
		"</td><td><a href = '3.viewPost.php?id=$id'>" , $rs['title'],"</a>",
		"</td><td>" , $rs['msg'], 
		"</td><td>", $rs['name'], "</td>",
		"</td><td>", $rs['likes'], "</td>",
		// "</td><td>", $rs['dislike'], "</td>",
		"</td><td>", $rs['type'], "</td>";
		// "<td><a href='2.like.php?id=", $rs['id'], "&t=1'>Like</a> ",
		// "<a href='2.like.php?id=", $rs['id'], "&t=-1'>Dislike</a> ";
		if(checknum($rs['likes'])){
			echo "<td><a href='2.like.php?id=", $rs['id'], "&t=1'>Like</a> ";
		}else{
			echo "<td>",
			"<a href='2.like.php?id=", $rs['id'], "&t=-1'>取消按讚</a>";
		}
		if(checkAccess(5)){
			echo "<a href='2.delete.php?id=", $rs['id'], "'>Delete</a> ",	
		"<a href='1.editUI.php?id=", $rs['id'], "'>Edit</a>";
		}
		echo "</td></tr>";
	}elseif($count >5 && $count <=10) {
		echo "<tr  style=\"color:blue\"><td>" , $rs['id'] ,
		"</td><td><a href = '3.viewPost.php?id=$id'>" , $rs['title'],"</a>",
		"</td><td>" , $rs['msg'], 
		"</td><td>", $rs['name'], "</td>",
		"</td><td>", $rs['likes'], "</td>",
		"</td><td>", $rs['type'], "</td>";
		if(checknum($rs['likes'])){
			echo "<td><a href='2.like.php?id=", $rs['id'], "&t=1'>Like</a> ";
		}else{
			echo "<td>",
			"<a href='2.like.php?id=", $rs['id'], "&t=-1'>取消按讚</a>";
		}
		// "<td><a href='2.like.php?id=", $rs['id'], "&t=1'>Like</a> ",
		// echo "<a href='2.like.php?id=", $rs['id'], "&t=-1'>Dislike</a> ";
		if(checkAccess(5)){
			echo "<a href='2.delete.php?id=", $rs['id'], "'>Delete</a> ",	
		"<a href='1.editUI.php?id=", $rs['id'], "'>Edit</a>";
		}
		echo "</td></tr>";
	} else {
		echo "<tr style = \" color:red \"><td>" , $rs['id'] ,
		"</td><td><a href = '3.viewPost.php?id=$id'>" , $rs['title'],"</a>",
		"</td><td>" , $rs['msg'], 
		"</td><td>", $rs['name'], "</td>",
		"</td><td>", $rs['likes'], "</td>",
		"</td><td>", $rs['type'], "</td>";
		// "<td><a href='2.like.php?id=", $rs['id'], "&t=1'>Like</a> ",
		// "<a href='2.like.php?id=", $rs['id'], "&t=-1'>Dislike</a> ";
		if(checknum($rs['likes'])){
			echo "<td><a href='2.like.php?id=", $rs['id'], "&t=1'>Like</a> ";
		}else{
			echo "<td>",
			"<a href='2.like.php?id=", $rs['id'], "&t=-1'>取消按讚</a>";
		}
		if(checkAccess(5)){
			echo "<a href='2.delete.php?id=", $rs['id'], "'>Delete</a> ",	
		"<a href='1.editUI.php?id=", $rs['id'], "'>Edit</a>";
		}
		echo "</td></tr>";
		
	}
	
}




?>
</table>
</body>
</html>
