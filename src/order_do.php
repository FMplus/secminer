<?php
    session_start();
    include_once('order_info_class.php');
    include_once('op_order.php');
    header("Content-type:text/html;charset=UTF-8");
    $from_url = "index.php";
    if(isset($_GET['from'])){
        $from_url   =   $_GET['from'];
    }
    if(!isset($_SESSION['id'])){
        die("请先登录！");
    }
    if(!isset($_SESSION['role'])){
        die("未知错误！");
    }
    if(!isset($_POST['action'])||!isset($_POST['oid'])){
        die("未知错误！");
    }
    $uid        = $_SESSION['id'];
    $role       = $_SESSION['role'];
    $action     = $_POST['action'];
    $oid        = $_POST['oid'];
    if(!check_role($uid,$role,$oid)){
        die("非法操作！");
    }
    $tips = "修改成功，点击返回";
    if(!do_order_action($oid,$action,$role)){
         $tips = "修改失败，点击返回";
    }
?>
<a href = <?=$from_url?>><?=$tips?></a>
