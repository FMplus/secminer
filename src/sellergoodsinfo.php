<?php 
	session_start();
	include_once("goods_info_class.php");
    $PAGE_MAX_NUM = 10;
    $PAGE_ID = 0;
    $MAX_PAGE_ID = 7;
    /*Default Page is zero!*/
    if(isset($_GET['page'])){
        $PAGE_ID = (integer)($_GET['page']);
    }

    if(isset($_SESSION['id'])){
        $sid = $_SESSION['id'];
        $goods_db = new goods_info_db; 
        $goods_db -> open();
        $count = $goods_db -> seller_goods_count($sid);
        $begin = $PAGE_ID*$PAGE_MAX_NUM;
        $PAGE_NUM = (integer)($count/$PAGE_MAX_NUM);
        if($count%$PAGE_MAX_NUM > 0){
            $PAGE_NUM++;
        }

        $goods_id_list = $goods_db -> fetch_sellergoods($sid,$begin,$PAGE_MAX_NUM);
        $constant = array("零","一","二","三","四","五","六","七","八","九","十","错误");
        
        $goods_info_list = array();
        foreach($goods_id_list as $goods_id){
            array_push($goods_info_list,$goods_db -> fetch_goods_info_asid($goods_id));
        }
        $goods_db -> close();
    }else{
        die("请登录！");
    }
?>
<html>
<head>
<meta charset="UTF-8"/>
<link rel="stylesheet" type="text/css" href="css/sellerinfocenter.css" >
</head>
<body>
<div id="goodsinfo">
<table width="700px" frame="box" align="center">
<tr>
	<th>商品名</th>
	<th>展示</th>
	<th>数量</th>
	<th>新旧程度</th>
	<th>原价</th>
	<th>现价</th>
	<th>状态</th>
	<th>操作</th>
</tr>
<?php foreach ($goods_info_list as $value){?>
<tr>
	<td><a href = "goodsinfo.php?id=<?=$value->id?>" target="_top"><?= $value -> name ?></a></td>
	<td><a href = "goodsinfo.php?id=<?=$value->id?>" target="_top"><img src="<?= $value -> photo[0] ?>" height="100px" width="100px"></img></a></td>
	<td><?= $value -> quantity?></td>
	<td><?= $constant[(integer)($value -> usingdgr)]?>成新</td>
	<td><?= $value -> originalprice ?></td>
	<td><?= $value -> currentprice ?></td>
	<td><?php if( $value -> state == "onsell" ){?>在售<?php null;}else{?>下架<?php null;}?></td>
	<td><a href = "updategoodsinfo.php?id=<?=$value->id?>">编辑信息</a></td>
</tr>
<?php }?>
</table>
<
<?php if($PAGE_NUM <= $MAX_PAGE_ID){
        for($i = 1;$i <= $PAGE_NUM;$i++){
            if($PAGE_ID == $i - 1){?>
            <?=$i?>&nbsp
 <?php      }else{?>
            <a href = "sellergoodsinfo.php?page=<?= $i-1?>"><?=$i?></a>&nbsp
<?php       }
        }?>
        >
<?      }else{
        for($i = 1;$i < $MAX_PAGE_ID;$i++){
                    if($PAGE_ID == $i - 1){?>
            <?=$i?>&nbsp
 <?php      }else{?>
            <a href = "sellergoodsinfo.php?page=<?= $i-1?>"><?=$i?></a>&nbsp
<?php       }
        }?>
        ...
        <a href = "sellergoodsinfo.php?page=<?= $PAGE_NUM-1?>"><?=$PAGE_NUM?></a>&nbsp
        >
<?php }?>
&nbsp
<input type = "text" name = "pageid" id = "pageid" value = '<?=$PAGE_ID+1?>' align = 'right' size = '3'/>/<?=$PAGE_NUM?>&nbsp
<input type = "button" onclick = "jump('sellergoodsinfo.php?page=',pageid.value - 1)" value = "Go"/>

<script language = "javascript">
    function jump(page,pageid){
        max_page = <?=$PAGE_NUM?>;
        if(pageid < max_page && pageid  >= 0){
            window.location.href=page+pageid;
        }
    }
</script>
</div>
</body>
</html>
