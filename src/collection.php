<?php
    session_start();
    include_once("config.php");
    include_once("db_class.php");
	include_once("goods_info_class.php");

    function get_collection($id){
        $res = array();
        $dbn = new db;
        $dbn -> open(SM_HOST,SM_DB,SM_UID,SM_PSW);
        $sql = "select id 
            from `goods_focus` 
            where $id = `bid`";
    
        $result = $dbn -> query($sql);
        while($row = mysql_fetch_array($result)){
            array_push($res,$row['id']);
        }
        $dbn -> close();
        return $res;
    }

    function get_goods_info($goods_id){
        $goods_info_db = new goods_info_db;
        $goods_info_db -> open();
        $res = $goods_info_db -> fetch_goods_info_asid($goods_id);
        $goods_info_db -> close();
        return $res;
    }

    $goods_info_list    =   array();

    if(isset($_SESSION['id'])){
        $id                 = $_SESSION['id'];
        $goods_id_list      = get_collection($id);
        foreach($goods_id_list as $goods_id){
            array_push($goods_info_list,get_goods_info($goods_id));
        }
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
        </tr>
    <?php null;}?>
    </table>
</body>
</html>
