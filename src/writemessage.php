<?php
	session_start();
	include_once 'user_info_class.php';
	include_once("message1.php");
	
	$PAGE_MAX_NUM = 5;
    $PAGE_ID = 0;
    $MAX_PAGE_ID = 7;
	$PAGE_NUM = 0;
	$count = 1;
	
	if(!isset($_GET['uid']))
	{
		die("页面错误！");
	}
	$lid = $_GET['uid'];
	
	if(!isset($_SESSION['id']))
	{
		die("请登录！");
	}
	$tid = $_SESSION['id'];

	$l_db = new user_info_db();
	$l_db -> open();
	$l_info = $l_db -> fetch_user_info($lid);
	$l_db -> close();
	
	$t_db = new user_info_db();
	$t_db -> open();
	$t_info = $t_db -> fetch_user_info($tid);
	$t_db -> close();
	
	$mdb = new msg_db;
	$mdb -> open();
	if(isset($_GET['page'])){
        $PAGE_ID = (integer)($_GET['page']);
    }
	$count = $mdb -> msg_get_count($lid);
	$begin = $PAGE_ID*$PAGE_MAX_NUM;
	$PAGE_NUM = (integer)($count/$PAGE_MAX_NUM);
	if($count%$PAGE_MAX_NUM > 0){
		$PAGE_NUM++;
	}
	if(isset($_POST['is_pushed'])){
		$mdb -> message_to_listener( $tid, $lid, $_POST['content'] );
        unset($_POST['is_pushed']);
    }
	$l_msg = $mdb -> fetch_msg_aslistener($lid,$begin,$PAGE_MAX_NUM);
	$mdb -> close();

?>

<html>
<script language = "javascript">
    function jump(page,pageid){
        max_page = <?=$PAGE_NUM?>;
        if(pageid < max_page && pageid  >= 0){
            window.location.href=page+pageid;
        }
    }
    function judgenull(a)
    {
        if (a.content.value== "")
        {
            alert("留言信息不能为空");
            a.content.focus();
            return(false);
        }
    }
</script>
<head>
<meta charset="UTF-8"/>
<link rel="stylesheet" type="text/css" href="css/infocenter.css" >  
</head>
<body>
    <form method = "post" action = "writemessage.php?uid=<?=$lid?>">
        <div id = "pushwindow">
            <div id = "needmsg">
                <textarea type = "text" name = "content"  rows = "4" cols = "64%" style=" font-size:19px; color:#AAF" value = "" ></textarea>
            </div>
            <div id = "output">
				<input type = "submit" name = "submit" value = "发送" style = "background:#add8e6;color:#ffffff;font-size:1em;font-family:Georgia,serif;font-weight:bold;width:100%;height:96;" onclick = "return judgenull(this.form);"/>
            </div>
        </div>
        <input type = "hidden" name = "is_pushed"/>
    </form>
	
	<?php $user_db = new user_info_db(); 
		  $user_db -> open(); ?>
    <?php foreach($l_msg as $msg){ ?> 
		<?php $user_info = $user_db -> fetch_user_info($msg -> talker); ?>
        <form>
            <fieldset>
                    <legend><a href = "showsellerinfo.php?sid=<?= $user_info -> id?>" target = "_blank"><?= $user_info -> nickname ?></a>/<?= $msg -> time ?></legend>
                    <h5><?= $msg -> content ?></h5>
            </fieldset>
            <br/>
        </form>
    <?php }?>
	<?php $user_db -> close(); ?>
	
		<
        <?php if( $PAGE_NUM <= $MAX_PAGE_ID ){
                for( $i = 1; $i <= $PAGE_NUM; $i++ ){
                    if($PAGE_ID == $i - 1){?>
                    <?=$i?>&nbsp
         <?php      }else{?>
                    <a href = "writemessage.php?page=<?= $i-1?>&uid=<?=$lid?>"><?=$i?></a>&nbsp
        <?php       }
                }?>
        >
        <?      }else{
                for($i = 1;$i < $MAX_PAGE_ID;$i++){
                            if($PAGE_ID == $i - 1){?>
                    <?=$i?>&nbsp
         <?php      }else{?>
                    <a href = "writemessage.php?page=<?= $i-1?>&uid=<?=$lid?>"><?=$i?></a>&nbsp
        <?php       }
                }?>
        ...
                <a href = "writemessage.php?page=<?= $PAGE_NUM-1?>&uid=<?=$lid?>"><?=$PAGE_NUM?></a>&nbsp
        >
        <?php }?>
        &nbsp
        <input type = "text" name = "pageid" id = "pageid" value = '<?=$PAGE_ID+1?>' align = 'right' size = '3'/>/<?=$PAGE_NUM?>&nbsp
        <input type = "button" onclick = "jump('writemessage.php?page=',pageid.value - 1)" value = "Go"/>

    </div>
</body>
</html>
