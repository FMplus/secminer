<?php 
	session_start();
	include 'order_info_class.php';
    $PAGE_MAX_NUM = 10;
    $PAGE_ID = 0;
    $MAX_PAGE_ID = 7;
	$O_STATE_CNZH = 	array(	'initial'=>'等待接单',
							'waiting'=>'等待发货',
							'delivering'=>'配送中',
							'completed'=>'完成',
							'canceled'=>'取消'
							);
    /*Default Page is zero!*/
    if(isset($_GET['page'])){
        $PAGE_ID = (integer)($_GET['page']);
    }
    if(isset($_SESSION['id'])){
        $id = $_SESSION['id'];
        $order_db = new order_db();
        $order_db -> open();
        
        $count = $order_db -> seller_order_count($id);
        $begin = $PAGE_ID*$PAGE_MAX_NUM;
        $PAGE_NUM = (integer)($count/$PAGE_MAX_NUM);
        if($count%$PAGE_MAX_NUM > 0){
            $PAGE_NUM++;
        }
        $order_info_id = $order_db -> fetch_seller_order_list( $id, $begin,$PAGE_MAX_NUM);
        $order_info_list = array();
        foreach($order_info_id as $oid){
            array_push($order_info_list,$order_db -> fetch_order_info($oid));
        }
        $order_db -> close();
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

<div id="sellerinfoorder">

<table width="700px" frame="box" align="center">
<tr>
	<th>订单编号</th>
	<th>商品编号</th>
	<th>买家编号</th>
	<th>数量</th>
	<th>单价</th>
	<th>总计</th>
	<th>时间</th>
	<th>状态</th>
</tr>
<?php foreach ($order_info_list as $value){?>
<tr>
	<td><a href = "sorder.php?oid=<?= $value -> id ?>" ><?= $value -> id ?></a></td>
	<td><a href = "goodsinfo.php?id=<?= $value -> gid ?>" target = "_blank"><?= $value -> gid ?></td>
	<td><a href = "neederinfocenter.php?nid=<?= $value -> bid ?>" target = "_blank"><?= $value -> bid ?></td>
	<td><?= $value -> number ?></td>
	<td><?= $value -> price ?></td>
	<td><?= $value -> totalcost ?></td>
	<td><?= $value -> time ?></td>
	<td><?= $O_STATE_CNZH[$value -> state] ?></td>
</tr>
<?php }?>
</table>
<
<?php if($PAGE_NUM <= $MAX_PAGE_ID){
        for($i = 1;$i <= $PAGE_NUM;$i++){
            if($PAGE_ID == $i - 1){?>
            <?=$i?>&nbsp
 <?php      }else{?>
            <a href = "sellerorderinfo.php?page=<?= $i-1?>"><?=$i?></a>&nbsp
<?php       }
        }?>
        >
<?      }else{
        for($i = 1;$i < $MAX_PAGE_ID;$i++){
                    if($PAGE_ID == $i - 1){?>
            <?=$i?>&nbsp
 <?php      }else{?>
            <a href = "sellerorderinfo.php?page=<?= $i-1?>"><?=$i?></a>&nbsp
<?php       }
        }?>
        ...
        <a href = "sellerorderinfo.php?page=<?= $PAGE_NUM-1?>"><?=$PAGE_NUM?></a>&nbsp
        >
<?php }?>
&nbsp
<input type = "text" name = "pageid" id = "pageid" value = '<?=$PAGE_ID+1?>' align = 'right' size = '3'/>/<?=$PAGE_NUM?>&nbsp
<input type = "button" onclick = "jump('sellerorderinfo.php?page=',pageid.value - 1)" value = "Go"/>
</div>


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
