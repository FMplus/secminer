<?php
	session_start();
	include_once 'config.php';
	include_once 'db_class.php';
    include_once("op_collection.php");
    header("Content-type:text/html;charset=UTF-8");
    

    if(isset($_SESSION['id'])
        &&isset($_GET['id'])
        &&isset($_GET['do'])){
        $uid = $_SESSION['id'];
        $gid = $_GET['id'];
        $is_add  = $_GET['do'];
        $dbn = new db;
        $dbn -> open(SM_HOST,SM_DB,SM_UID,SM_PSW);
        $fromurl = 'goodsinfo.php?id='.$gid;
        if(isset($_GET['from'])){
            $fromurl = $_GET['from'];
        }
        if($is_add){
            if(!is_collected($uid,$gid,$dbn)){
                add_collection($uid,$gid,$dbn);
                echo("<a href = ".$fromurl.">已关注成功，点击返回</a>");
            }else{
                echo("<a href = ".$fromurl.">已关注，请勿重复，点击返回</a>");
            }
        }else{
            if(is_collected($uid,$gid,$dbn)){
                cancel_collection($uid,$gid,$dbn);
                echo "<a href = ".$fromurl.">取消关注成功，点击返回</a>";
            }else{
                echo("<a href = ".$fromurl.">非法操作，点击返回</a>");
            }
        }

        $dbn -> close();
	}else{
        die("<a href = ".$fromurl.">非法操作，点击返回</a>");
    }
?>
