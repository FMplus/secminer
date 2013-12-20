<?php
    session_start();
    include_once("message.php");
    $uid = $_GET['nid'];
    $msg_db = new message_db();
    $msg_db -> open();

    $PAGE_MAX_NUM = 6;
    $PAGE_ID = 0;
    $MAX_PAGE_ID = 7;
    if(isset($_POST['del_id'])){
        $del_id = $_POST['del_id'];
        $msg_db -> delete_message($uid,$del_id);
        unset($_POST['del_id']);
    }
    
    /*Default Page is zero!*/
    if(isset($_GET['page'])){
        $PAGE_ID = (integer)($_GET['page']);
    }

    if(isset($_POST['is_pushed'])){
        $msg = new msg(null,$uid,$_POST['msg']);
        $msg_db -> send_message($msg);
        unset($_POST['is_pushed']);
    }

    $count = $msg_db -> count($uid);
    $begin = $PAGE_ID*$PAGE_MAX_NUM;
    $PAGE_NUM = (integer)($count/$PAGE_MAX_NUM);
    if($count%$PAGE_MAX_NUM > 0){
        $PAGE_NUM++;
    }

    $msg_id_list = $msg_db -> fetch_message_list($uid,$begin,$PAGE_MAX_NUM);
    $msg_list    = array();
    foreach($msg_id_list as $msg_id){
        array_push($msg_list,$msg_db -> fetch_message($msg_id));
    }
    
    $msg_db -> close();
?>

<html>
<head>
<meta charset="UTF-8"/>
<link rel="stylesheet" type="text/css" href="css/infocenter.css" >  
</head>

<div id = "buyermessage">
    <?php foreach($msg_list as $msg){ ?> 
        <form>
            <fieldset>
                    <legend><?= $msg -> time ?></legend>
                    <h5 class = "message"><?= $msg -> content ?><br/></h5>
            </fieldset>
            <br/>
        </form>
    <?php }?>
<
<?php if($PAGE_NUM <= $MAX_PAGE_ID){
        for($i = 1;$i <= $PAGE_NUM;$i++){
            if($PAGE_ID == $i - 1){?>
            <?=$i?>&nbsp
 <?php      }else{?>
            <a href = "showbuyerneed.php?nid=<?= $uid?>&&page=<?= $i-1?>"><?=$i?></a>&nbsp
<?php       }
        }?>
        >
<?      }else{
        for($i = 1;$i < $MAX_PAGE_ID;$i++){
                    if($PAGE_ID == $i - 1){?>
            <?=$i?>&nbsp
 <?php      }else{?>
            <a href = "showbuyerneed.php?nid=<?= $uid?>&&page=<?= $i-1?>"><?=$i?></a>&nbsp
<?php       }
        }?>
        ...
        <a href = "showbuyerneed.php?nid=<?= $uid?>&&page=<?= $PAGE_NUM-1?>"><?=$PAGE_NUM?></a>&nbsp
        >
<?php }?>

&nbsp
<input type = "text" name = "pageid" id = "pageid" value = '<?=$PAGE_ID+1?>' align = 'right' size = '3'/>/<?=$PAGE_NUM?>&nbsp
<input type = "button" onclick = "jump('showbuyerneed.php?nid=<?= $uid?>&&page=',pageid.value - 1)" value = "Go"/>

</div>

<script language = "javascript">
    function jump(page,pageid){
        max_page = <?=$PAGE_NUM?>;
        if(pageid < max_page && pageid  >= 0){
            window.location.href=page+pageid;
        }
    }
</script>
</html>
