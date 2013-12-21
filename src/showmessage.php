<?php
	session_start();
	include_once 'user_info_class.php';
	include_once("message1.php");

	if(!isset($_SESSION['id'])){
		die("请登录！");
	}
	$id = $_SESSION['id'];
    $PAGE_MAX_NUM = 5;
    $PAGE_ID = 0;
    $MAX_PAGE_ID = 7;
    $o_kw   =   '';
	$PAGE_NUM = 0;
	$count = 1;

	if(isset($_GET['page'])){
        $PAGE_ID = (integer)($_GET['page']);
    }	
	$mdb = new msg_db;
	$mdb -> open();
	if(isset($_POST['del_id'])){
        $del_id = $_POST['del_id'];
        $mdb -> delete_msg_asid($del_id);
        unset($_POST['del_id']);
    }
	$count = $mdb -> msg_get_count($id);
	$begin = $PAGE_ID*$PAGE_MAX_NUM;
	$PAGE_NUM = (integer)($count/$PAGE_MAX_NUM);
	if($count%$PAGE_MAX_NUM > 0){
		$PAGE_NUM++;
	}

	$l_msg = $mdb -> fetch_msg_aslistener($id,$begin,$PAGE_MAX_NUM);
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
</script>
<head>
<meta charset="UTF-8"/>
<link rel="stylesheet" type="text/css" href="css/infocenter.css" >  
</head>
<body>
	<?php $user_db = new user_info_db(); 
		  $user_db -> open(); ?>
    <?php foreach($l_msg as $msg){ ?> 
		<?php $user_info = $user_db -> fetch_user_info($msg -> talker); ?>
        <form action = "showmessage.php" method = "post" >
            <fieldset>
                    <legend><a href = "showsellerinfo.php?sid=<?= $user_info -> id?>" target = "_top"><?= $user_info -> nickname ?></a>/<?= $msg -> time ?><input type = "submit" value = "删除" /></legend>
                    <h5><?= $msg -> content ?></h5>
					<input type = "hidden" name = "del_id" value = "<?=$msg->id?>"/>
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
                    <a href = "showmessage.php?page=<?= $i-1?>&keyword='<?=$o_kw?>'"><?=$i?></a>&nbsp
        <?php       }
                }?>
        >
        <?      }else{
                for($i = 1;$i < $MAX_PAGE_ID;$i++){
                            if($PAGE_ID == $i - 1){?>
                    <?=$i?>&nbsp
         <?php      }else{?>
                    <a href = "showmessage.php?page=<?= $i-1?>&keyword='<?=$o_kw?>'"><?=$i?></a>&nbsp
        <?php       }
                }?>
        ...
                <a href = "showmessage.php?page=<?= $PAGE_NUM-1?>&keyword='<?=$o_kw?>'"><?=$PAGE_NUM?></a>&nbsp
        >
        <?php }?>
        &nbsp
        <input type = "text" name = "pageid" id = "pageid" value = '<?=$PAGE_ID+1?>' align = 'right' size = '3'/>/<?=$PAGE_NUM?>&nbsp
        <input type = "button" onclick = "jump('showmessage.php?page=',pageid.value - 1)" value = "Go"/>

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
