<?php //include 'order_info_class.php';
	session_start();
	include ('goods_info_class.php');
	include_once ('user_info_class.php');
	$constant = array("零","一","二","三","四","五","六","七","八","九","十","错误");
	$goods_id = $_POST['id'];
	$quantity = $_POST['number'];
	
	$goods_db = new goods_info_db;
	$goods_db -> open();
	$g_info = $goods_db -> fetch_goods_info_asid($goods_id);
	$goods_db -> close();
	$goods_name = $g_info -> name;
	$totalcost = $g_info -> currentprice * $quantity;
	$sellerid = $g_info -> sid;
	
	$user_db = new user_info_db();
	$user_db -> open();
	$buyer_info = $user_db -> fetch_user_info($_SESSION['id']);
	$seller_info = $user_db -> fetch_user_info($sellerid);
	$buyerid = $_SESSION['id'];
	$bphone = $buyer_info -> phoneno;
	$sphone = $seller_info -> phoneno;
	$addr = ($buyer_info -> school) ." ". ($buyer_info -> addr);
	$user_db -> close();
?>

<html>

<head>
<meta charset="UTF-8"/>
<link rel="stylesheet" type="text/css" href="css/sellerinfocenter.css" >
</head>

<body>
	<form width="400px" >
		<fieldset>
		<legend>订单信息</legend>
			<p>商品编号：<?= $goods_id?></p>
			<p>商品名称：<?= $goods_name?></p>
			<p>商品数量：<?= $quantity ?></p>
			<p>卖家编号：<?= $sellerid ?></p>
			<p>卖家联系电话：<?= $sphone?>
			<p>买家编号：<?= $buyerid ?></p>
			<p>买家联系电话：<?= $bphone ?></p>
			<p>送货地址：<?= $addr ?></p>
			<p>总计：<?= $totalcost ?></p>
		</fieldset>
	</form>
	
	<form method="post" action = "addorder.php">
		<input type="hidden" name="goods_id" value="<?= $goods_id?>" />
		<input type="hidden" name="quantity" value="<?= $quantity?>" />
		<input type="submit" value="确认" />
	</form>
	
</body>

</html>
