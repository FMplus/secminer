<?php
	session_start();
	include_once 'config.php';
	include_once 'db_class.php';
	$bid = $_SESSION['id'];
	$gid = $_GET['id'];
	$sql = "insert into goods_focus
			(`id`,`bid`)value($gid,$bid)";
			
	$dbn = new db();
	$dbn -> open(SM_HOST,SM_DB,SM_UID,SM_PSW);
	$dbn -> query($sql);
	$dbn -> close();
	
	echo "<a href = 'goodsinfo.php?id=$gid'>关注成功，点击返回</a>";
?>
