<?php 
	session_start();
	include_once("goods_info_class.php");
	//$sid = $_SESSION['id'];
	$goods_db = new goods_info_db; 
	$goods_db -> open();
	$goods_id_list = $goods_db -> fetch_allgoods();
	$constant = array("零","一","二","三","四","五","六","七","八","九","十","错误");
	$goods_info_list = array();
	foreach($goods_id_list as $goods_id){
		array_push($goods_info_list,$goods_db -> fetch_goods_info_asid($goods_id));
	}
	$goods_db -> close();
	
?>
<html>
<head>
<meta charset="UTF-8"/>
<link rel="stylesheet" type="text/css" href="css/sellerinfocenter.css" >
<style type="text/css">
div#gimg {background-color:#ffffff;width:50%;height:102px;float:left;}
div#info {width:45%;height:100px;float:right;}
table#gshow{width:650px;}
tr#row{width:33%;}
</style>
</head>
<body>
<div id="goodsinfo">

<table id = "gshow" >

<?php 
		$i = 0;
		while($i < count($goods_info_list) && $i < 30){?>
		<tr >
			<?php for($j = 0;$j < 3&&$i < count($goods_info_list);$j++,$i++){$value = $goods_info_list[$i];?>
			<td id = "row">
				<div id = "gimg">
				<a href="goodsinfo.php?id=<?= $value -> id ?>" target="_top"><img src="<?= $value -> photo[0] ?>" height="100px" width="100px"></img></a>
				</div>
				<div id = "info"><br/>
				<a href="goodsinfo.php?id=<?= $value -> id ?>"  target="_top"><?= $value -> name ?></a><br/>
				<?= $constant[(integer)($value -> usingdgr)] ?>成新<br/>
				价格：<?= $value -> currentprice ?><br/>
				</div>
			</td>
			<?php ;}?>
		</tr>
<?php }?>
</table>

</body>
</html>
