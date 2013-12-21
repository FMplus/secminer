<?php 
	session_start();
	include_once("goods_info_class.php");
	include_once("user_info_class.php");
    include_once("op_collection.php");
	$goods_id = $_GET['id'];
	$not_c = 0;
	$uid = null;
    if(isset($_SESSION['id'])){
        $uid = $_SESSION['id'];
        $dbn = new db;
        $dbn -> open(SM_HOST,SM_DB,SM_UID,SM_PSW);
        $not_c = !is_collected($uid,$goods_id,$dbn);
        $dbn -> close();
    }
	
	$goods_db = new goods_info_db; 
	$goods_db -> open();
	$constant = array("零","一","二","三","四","五","六","七","八","九","十","错误");
	$g_info = $goods_db -> fetch_goods_info_asid($goods_id);
	$goods_db -> close();
	
	$sid = $g_info -> sid;
	$user_db = new user_info_db;
	$user_db -> open();
	$user_info = $user_db -> fetch_user_info($sid);
	$user_db -> close();
?>
<html>
<head>
<meta charset="UTF-8"/>
<link rel="stylesheet" type="text/css" href="css/sellerinfocenter.css" >
</head>
<script language = "JavaScript">
	function plus(a)
	{
		if(a.value < <?= $g_info -> quantity?>)
			a.value++;
	}
	function minus(a)
	{
		if(a.value > 1)
			a.value--;
	}
	function inventory(a)
	{
		if(<?=$g_info ->quantity?> <= 0){
			alert("现在缺货!");
			return false;
		}else if(a.value > <?=$g_info ->quantity?> || a.value <= 0){
			alert("请输入正确数量!");
			return false;
		}
		else 
			return true;
	}
</script>
<body>

<center>
<p>
<a href="index.php" target="_top">
<img src="img/logo1.3.4.bmp" width="280" height="70"/></a>
</p>
</center>

<form>
	
	<div id="goodsshow">
		商品展示<br/><br/>
		<img src="<?= $g_info -> photo[0] ?>" height = '300px' width = '300px'/>
		<br/><br/>
	</div>
	
	<div id="information"><br/><br/><br/><br/><br/>
		商品编号：
		<?= $goods_id ?>
		<br/><br/>
		商品名：
		<?= $g_info ->name ?>
		<br/><br/>
        <?php if ($g_info -> state == "onsell"){?>
		库存：
		<?= $g_info ->quantity ?>
		<br/><br/>
		商品新旧程度：
		<?= $constant[(integer)($g_info -> usingdgr)] ?>成新<br/>
		<p>商品原价：<del><?= $g_info ->originalprice ?></del></p>
		商品现价：
		<?= $g_info ->currentprice ?>
		<br/><br/>
        <?php null;}else{?><h1>已下架</h1><?php null;}?>
	</div>
</form>	
    <?php if ($g_info -> state == "onsell"){?>
	<div id="setorder" >
	<form method="post" action ="sellerorder.php">
	<input type="hidden" name="id" value = "<?= $goods_id ?>" />
	<input type = "text" name = "number" size="6px" value = "1"/>
	<input type = "button" id = "plus_" onclick = "plus(this.form.number)" value = "+"/>
	<input type = "button" id = "minus_"  onclick = "minus(this.form.number)" value = "-"/>
	<input type="submit" value="下订单" onclick = "return inventory(this.form.number);"></input>
	</form>
	<form method="get" action ="collection_do.php">
		<input type="hidden" name="id" value="<?= $goods_id ?>"/>
        <input type="hidden" name="do" value='<?= $not_c?>'/>
		<input type = "submit" value='<?=$not_c?"关注":"取消关注"?>'/>
	</form>
	</div>
    <?php null;}?>
	
	<div id = "seller">
	此商品由 <?php if ($uid == $sid){?><a href = "infocenter.html" target = "_blank"><?= $user_info -> name ?></a>
	<?php }else{?>
	<a href = "showsellerinfo.php?sid=<?= $user_info -> id?>" target = "_blank"><?= $user_info -> name ?></a>
	<?php }?>提供<br/><br/>
	</div>
	
	<div id="decribe" >
	商品描述：
	<?= $g_info ->dscrb ?>
	<br/><br/>
	</div>
	
</body>
</html>
