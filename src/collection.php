<?php
    session_start();
    include_once("config.php");
    include_once("db_class.php");
    include_once("op_collection.php");
    header("Content-type:text/html;charset=UTF-8");

    $goods_info_list    =   array();
    $PAGE_MAX_NUM = 10;
    $PAGE_ID = 0;
    $MAX_PAGE_ID = 7;

    if(isset($_GET['page'])){
        $PAGE_ID = (integer)($_GET['page']);
    }

    if(isset($_SESSION['id'])){
        $id                 = $_SESSION['id'];
        $count              = count_collection($id);
        $begin = $PAGE_ID*$PAGE_MAX_NUM;
        $PAGE_NUM = (integer)($count/$PAGE_MAX_NUM);
        if($count%$PAGE_MAX_NUM > 0){
            $PAGE_NUM++;
        }

        $goods_id_list      = get_collection($id,$begin,$PAGE_MAX_NUM);
       
        foreach($goods_id_list as $goods_id){
            array_push($goods_info_list,get_goods_info($goods_id));
        }
    }else{
        die("请登录！");
    }
?>

<html>
<head>
<meta charset="UTF-8"/>
<style type="text/css">
#collections
  {
    width:500px;
    border-collapse:collapse;
  }

#collections td, #collections th 
  {
  font-size:1em;
  border:1px solid #1090FF;
  padding:3px 7px 2px 7px;
  line-height:25px;
  }

#collections th 
  {
  font-size:1.1em;
  text-align:left;
  padding-top:5px;
  padding-bottom:4px;
  background-color:#ADD8E6;
  color:#ffffff;
  line-height:25px;
  }

#collections td 
  {
  line-height:25px;
  color:#000000;
  background-color:#ffffff;
  }
</style>
</head>
<body>
    <table id = "collections">
    <tr>
        <th>
           商品号     
        </th>
        <th>
           商品名称
        </th>
        <th>
           商品价格   
        </th>
        <th>
           剩余数量
        </th>
        <th>
           操作
        </th>
    </tr>
    <?php foreach($goods_info_list as $goods){?>
        <tr>
            <td>
               <a href = "goodsinfo.php?id=<?=$goods->id?>" target="_top">
                    <?=$goods->id?>
               </a>
            </td>
            <td>
               <a href = "goodsinfo.php?id=<?=$goods->id?>" target="_top">
                <?=$goods->name?>
               </a>
            </td>
            <td>
               <?=$goods->currentprice?>
            </td>
            <td>
               <?=$goods->quantity?>
            </td>
            <td>
               <a href="collection_do.php?from='collection.php'&id=<?=$goods->id?>&do=0">取消关注</a>
            </td>
        </tr>
    <?php null;}?>
    </table>
    <
<?php if($PAGE_NUM <= $MAX_PAGE_ID){
        for($i = 1;$i <= $PAGE_NUM;$i++){
            if($PAGE_ID == $i - 1){?>
            <?=$i?>&nbsp
<?php      }else{?>
            <a href = "collection.php?page=<?= $i-1?>"><?=$i?></a>&nbsp
<?php       }
        }?>
        >
<?      }else{
        for($i = 1;$i < $MAX_PAGE_ID;$i++){
                    if($PAGE_ID == $i - 1){?>
            <?=$i?>&nbsp
 <?php      }else{?>
            <a href = "collection.php?page=<?= $i-1?>"><?=$i?></a>&nbsp
<?php       }
        }?>
        ...
        <a href = "collection.php?page=<?= $PAGE_NUM-1?>"><?=$PAGE_NUM?></a>&nbsp
        >
<?php }?>
&nbsp
<input type = "text" name = "pageid" id = "pageid" value = '<?=$PAGE_ID+1?>' align = 'right' size = '3'/>/<?=$PAGE_NUM?>&nbsp
<input type = "button" onclick = "jump('collection.php?page=',pageid.value - 1)" value = "Go"/>

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
