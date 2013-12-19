<?php
	session_start();
	include_once("config.php");
	include_once("order_info_class.php");
	$order = new order_info();
	$gid = $_POST['goods_id'];
	$order -> bid         = $_SESSION['id'];
	$order -> number      = $_POST['quantity'];
	$order -> gid         = $_POST['goods_id'];
	$gdb = new goods_info_db();
	$gdb -> open();
	$order -> price = $gdb -> fetch_goods_price($order -> gid);
	$gdb -> close();
	$odb = new order_db();
	$odb -> open();
	$order -> totalcost = $order -> price * $order -> number;
	$order -> state = "initial";
	$oid = $odb -> add_order($order);
	$odb -> close();
	
	echo "<a href = 'goodsinfo.php?id=$gid'>下单成功，点击返回</a>";
?>
