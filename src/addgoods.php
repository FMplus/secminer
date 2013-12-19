<?php
session_start();
include_once("config.php");
include_once("goods_info_class.php");
if(!isset($_SESSION['id'])){
	echo "Error!";
	exit(0);
}
function upload_goodsphoto($file,$newname){
 if ((($file["type"] == "image/gif")
            || ($file["type"] == "image/jpeg")
            || ($file["type"] == "image/pjpeg"))
            && ($file["size"] < 2*1024*1024))
          {
              if ($file["error"] > 0)
                {
                    return 0;
                }
              else
                {
                    move_uploaded_file($file["tmp_name"],PATH_GDIMG."$newname");
					return 1;
                }
          }
        else
          {
            return 0;
          }
}	
$goods = new goods_info(    
                        $id            = 0,//no effect
                        $name          = $_POST['name'],
                        $usingdgr      = $_POST['usingdgr'],
                        $originalprice = $_POST['originalprice'],
                        $currentprice  = $_POST['currentprice'],
                        $dscrb         = $_POST['dscrb'],
                        $quantity      = $_POST['quantity'],
                        $state         = "onsell",
						$sid           = $_SESSION['id']
                      );
print_r($goods);
$gdb = new goods_info_db();
$gdb -> open();
if(!$gdb -> add_goods($goods)){
        echo "Add goods failed!".'<br />';
        $gdb -> close();
        exit(0);
}


if($_FILES['photo']["error"] == 0){//we use the file user uploaded just now
	$id = $gdb -> fetch_goodsid();
	if(upload_goodsphoto($_FILES['photo'],$id)){
        $goods -> photo = PATH_GDIMG."$id";
		$arr = array($goods -> photo);
        $gdb -> add_goods_photo($id,$arr);
    }
}
$gdb -> close();

?>
