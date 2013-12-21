<?php
include_once("upload_goodsphoto.php");
include_once("goods_info_class.php");

$goods = new goods_info(    
                        $id            = $_POST['id'],
                        $name          = $_POST['name'],
                        $usingdgr      = $_POST['usingdgr'],
                        $originalprice = $_POST['originalprice'],
                        $currentprice  = $_POST['currentprice'],
                        $dscrb         = $_POST['dscrb'],
                        $quantity      = $_POST['quantity'],
                        $state         = $_POST['state']
                      );
					  
$gdb = new goods_info_db();
$gdb -> open();	
$gdb -> update_goods_name($goods -> id, $goods -> name);
$gdb -> update_goods_usingdgr($goods -> id, $goods -> usingdgr);
$gdb -> update_goods_originalprice($goods -> id, $goods -> originalprice);
$gdb -> update_goods_currentprice($goods -> id, $goods -> currentprice);
$gdb -> update_goods_dscrb($goods -> id, $goods -> dscrb);
$gdb -> update_goods_quantity($goods -> id, $goods -> quantity);
$gdb -> update_goods_state($goods -> id, $goods -> state);

if($_FILES['photo']["error"] == 0){
	if(upload_goodsphoto($_FILES['photo'],$goods -> id)){
        $goods -> photo = PATH_GDIMG."$goods -> id";
		$arr = array($goods -> photo);
        $gdb -> add_goods_photo($id,$arr);
    }
}

$gdb -> close();
?>

<html>
<body>
	<a href = "sellergoodsinfo.php" >信息编辑成功，点击返回</a>
</body>
</html>
