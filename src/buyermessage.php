<?php
    session_start();
	header("Content-type:text/html;charset=UTF-8");
    include_once("message.php");
    $uid = $_SESSION['id'];
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

    <form method = "post" action = "buyermessage.php">
        <div id = "pushwindow">
            <div id = "needmsg">
                <textarea type = "text" name = "msg"  rows = "4" cols = "66%" style=" font-size:19px; color:#AAF" value = "请输入要发布的信息" ></textarea>
            </div>
            <div id = "output">
            <input type = "submit" name = "submit" value = "发布" style = "background:#add8e6;color:#ffffff;font-size:1em;font-family:Georgia,serif;font-weight:bold;width:100%;height:96;" onclick = "return judgenull(this.form);"/>
            </div>
        </div>
        <input type = "hidden" name = "is_pushed"/>
    </form>

<div id = "buyermessage">
    <?php foreach($msg_list as $msg){ ?> 
        <form action = "buyermessage.php" method = "post">
            <fieldset>
                    <legend><?= $msg -> time ?>&nbsp&nbsp<input type = "submit" value = "删除" /></legend>
                    <h5 class = "message"><?= $msg -> content ?><br/></h5>
                    <input type = "hidden" name = "del_id" value = "<?=$msg->id?>"/>
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
            <a href = "buyermessage.php?page=<?= $i-1?>"><?=$i?></a>&nbsp
<?php       }
        }?>
        >
<?      }else{
        for($i = 1;$i < $MAX_PAGE_ID;$i++){
                    if($PAGE_ID == $i - 1){?>
            <?=$i?>&nbsp
 <?php      }else{?>
            <a href = "buyermessage.php?page=<?= $i-1?>"><?=$i?></a>&nbsp
<?php       }
        }?>
        ...
        <a href = "buyermessage.php?page=<?= $PAGE_NUM-1?>"><?=$PAGE_NUM?></a>&nbsp
        >
<?php }?>

&nbsp
<input type = "text" name = "pageid" id = "pageid" value = '<?=$PAGE_ID+1?>' align = 'right' size = '3'/>/<?=$PAGE_NUM?>&nbsp
<input type = "button" onclick = "jump('buyermessage.php?page=',pageid.value - 1)" value = "Go"/>

</div>

<script language = "javascript">
    function jump(page,pageid){
        max_page = <?=$PAGE_NUM?>;
        if(pageid < max_page && pageid  >= 0){
            window.location.href=page+pageid;
        }
    }
    function judgenull(a)
    {
        if (a.msg.value== "")
        {
            alert("发布信息不能为空");
            a.msg.focus();
            return(false);
        }
    }
</script>
</html>
