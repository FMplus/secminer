<?php 
	session_start();
	include 'order_info_class.php';
	$id = $_SESSION['id'];
	$order_db = new order_db();
	$order_db -> open();
	$order_info_id = $order_db -> fetch_seller_order_list( $id, 100);
	$order_info_list = array();
	foreach($order_info_id as $oid){
		array_push($order_info_list,$order_db -> fetch_order_info($oid));
	}
	$order_db -> close();
?>
<html>

<head>
<meta charset="UTF-8"/>
<link rel="stylesheet" type="text/css" href="css/sellerinfocenter.css" >
</head>

<body> 

<div id="sellerinfoorder">

<table width="600px" frame="box" align="center">
<tr>
	<th>订单编号</th>
	<th>商品编号</th>
	<th>买家编号</th>
	<th>数量</th>
	<th>单价</th>
	<th>总计</th>
	<th>订单时间</th>
</tr>
<?php foreach ($order_info_list as $value){?>
<tr>
	<td><?= $value -> id ?></td>
	<td><?= $value -> gid ?></td>
	<td><?= $value -> bid ?></td>
	<td><?= $value -> number ?></td>
	<td><?= $value -> price ?></td>
	<td><?= $value -> totalcost ?></td>
	<td><?= $value -> time ?></td>
</tr>
<?php }?>
</table>
</div>

</body>
</html>
