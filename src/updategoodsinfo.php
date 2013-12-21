<?php 
    //session_start();
    include_once("goods_info_class.php");
    $goods_id = $_GET['id'];
    $goods_db = new goods_info_db; 
    $constant = array("零","一","二","三","四","五","六","七","八","九","十","错误");
    $opt = array("0.0","1.0","2.0","3.0","4.0","5.0","6.0","7.0","8.0","9.0","9.9");
    $goods_db -> open();
    $g_info = $goods_db -> fetch_goods_info_asid($goods_id);
    $goods_db -> close();
    
?>
<html>
<head>
<meta charset="UTF-8"/>
<link rel="stylesheet" type="text/css" href="css/sellerinfocenter.css" >
</head>
<script language = "JavaScript">
	function check_update(theform)
	{
		if (theform.name.value == "")
		{
			alert("请填写商品名称!");
			theform.name.focus();
			return(false);
		}
		else if (theform.originalprice.value == "")
		{
			alert("请填写商品原价!");
			theform.originalprice.focus();
			return(false);
		}
		else if (theform.currentprice.value == "")
		{
			alert("请填写商品现价!");
			theform.currentprice.focus();
			return(false);
		}
		else if (theform.decrb.value == "")
		{
			alert("请填写商品描述!");
			theform.decrb.focus();
			return(false);
		}
		else if (theform.quantity.value == "")
		{
			alert("请填写商品库存!");
			theform.quantity.focus();
			return(false);
		}
		
		return(true);		
	}
</script>
<body>
<div id="updategoodsinfo">
<form method="post" action="update_goodsinfo.php" enctype = "multipart/form-data">
    <fieldset>
        <legend>商品信息编辑</legend>
        商品展示<br/><br/>
        <img src="<?= $g_info -> photo[0] ?>" height = '300px' width = '300px'/>
        <input type="file" name="photo" value="上传图片" />
        <br/><br/>
        商品编号：
        <?= $goods_id ?>
        <input type = "hidden" name = "id" value = "<?= $goods_id ?>"></input>
        <br/><br/>
        商品名：
        <input type = "text" name = "name" value = "<?= $g_info ->name ?>" size = ""/>
        <br/><br/>
        商品状态：
        <select name="state">
        <option value = "onsell" <?php if( $g_info ->state == "onsell"){?> selected="selected" <?php null;}?> >在售</option>
        <option value = "sellout" <?php if( $g_info ->state == "sellout"){?> selected="selected" <?php null;}?> >下架</option>
        </select><br/><br/>
        库存：
        <input type = "text" name = "quantity" value = "<?= $g_info ->quantity ?>" />
        <br/><br/>
        商品新旧程度：
        <select name="usingdgr">
        <?php for ( $i = 1; $i <= 10; $i++ ){?>
        <option value=<?= $opt[$i]?> <?php if(((integer)($g_info -> usingdgr))==($i)){?> selected="selected" <?php null;}?> ><?= $constant[$i] ?>成新</option>
        <?php null;}?>
        </select>
        <br/><br/>
        商品原价：
        <input type = "text" name = "originalprice" value = "<?= $g_info ->originalprice ?>" />
        <br/><br/>
        商品现价：
        <input type = "text" name = "currentprice" value = "<?= $g_info ->currentprice ?>" />
        <br/><br/>
        商品描述：<br/>
        <textarea type = "text" name = "dscrb" rows=10 cols=40 ><?= $g_info ->dscrb ?></textarea>
        <br/><br/>
    </fieldset>
        <input type = "submit" value = "提交" onclick="return check_update(this.form);"/>
</form>    
</body>
</html>
