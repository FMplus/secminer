<?php
    include_once("search.php");
    include_once("goods_info_class.php");
    header("Content-type:text/html;charset=UTF-8");
    $list = array();

    $constant = array("零","一","二","三","四","五","六","七","八","九","十","错误");

    $PAGE_MAX_NUM = 10;
    $PAGE_ID = 0;
    $MAX_PAGE_ID = 7;
    $o_kw   =   '';
	$PAGE_NUM = 0;
	$count = 1;

    if(isset($_GET['page'])){
        $PAGE_ID = (integer)($_GET['page']);
    }

    if(isset($_GET['keyword'])){
        $o_kw   =   $_GET['keyword'];
        if($_GET['keyword'] != ""){
            $keyword = urldecode($_GET['keyword']);
            $count = search_goods_count($keyword);
            $begin = $PAGE_ID*$PAGE_MAX_NUM;
            $PAGE_NUM = (integer)($count/$PAGE_MAX_NUM);
            if($count%$PAGE_MAX_NUM > 0){
                $PAGE_NUM++;
            }
            $goods_id_list = search_goods_id($keyword,$begin,$PAGE_MAX_NUM);
            $goods_db = new goods_info_db();
            $goods_db -> open();
            foreach($goods_id_list as $id){
                array_push($list,$goods_db -> fetch_goods_info_asid($id));
            }
            $goods_db -> close();
        }
    }
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

<form action = "sd.php" method = "get">
	<div id = "pushwindow" >
		<div id = "needmsg">
			<input type = "text" name = "keyword" value = "<?=$o_kw?>" style=" font-size:19px; color:#AAF" size = "61px" />
		</div>
		<div id = "output">
			<input type = "submit" value = "search" style = "background:#add8e6;color:#ffffff;font-size:1em;font-family:Georgia,serif;font-weight:bold;width:100;height:32;"/>
		</div>
	</div>
</form>
	<?php if ($count == null || $count == 0){?>
			<h2>对不起，没有找到相关商品</h2>
	<?php }else{?>
    <div id="goodsinfo">
        <table id = "gshow">
            <?php 
                $i = 0;
                while($i < count($list)){?>
                <tr >
                    <?php for($j = 0;$j < 3&&$i < count($list);$j++,$i++){$value = $list[$i];?>
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
        <
        <?php if( $PAGE_NUM <= $MAX_PAGE_ID ){
                for( $i = 1; $i <= $PAGE_NUM; $i++ ){
                    if($PAGE_ID == $i - 1){?>
                    <?=$i?>&nbsp
         <?php      }else{?>
                    <a href = "sd.php?page=<?= $i-1?>&keyword='<?=$o_kw?>'"><?=$i?></a>&nbsp
        <?php       }
                }?>
        >
        <?      }else{
                for($i = 1;$i < $MAX_PAGE_ID;$i++){
                            if($PAGE_ID == $i - 1){?>
                    <?=$i?>&nbsp
         <?php      }else{?>
                    <a href = "sd.php?page=<?= $i-1?>&keyword='<?=$o_kw?>'"><?=$i?></a>&nbsp
        <?php       }
                }?>
        ...
                <a href = "sd.php?page=<?= $PAGE_NUM-1?>&keyword='<?=$o_kw?>'"><?=$PAGE_NUM?></a>&nbsp
        >
        <?php }?>
        &nbsp
        <input type = "text" name = "pageid" id = "pageid" value = '<?=$PAGE_ID+1?>' align = 'right' size = '3'/>/<?=$PAGE_NUM?>&nbsp
        <input type = "button" onclick = "jump('sd.php?keyword='<?=$o_kw?>'&page=',pageid.value - 1)" value = "Go"/>
	<?php } ?>
    </div>
    <script language = "javascript">
    function jump(page,pageid){
        max_page = <?=$PAGE_NUM?>;
        if(pageid < max_page && pageid  >= 0){
            window.location.href=page+pageid;
        }
    }
</script>
</body>
</html>
