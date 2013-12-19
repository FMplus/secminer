<?php
    session_start();
    include_once("message.php");
	include_once("user_info_class.php");
    $msg_db = new message_db();
    $msg_db -> open();
	
    $PAGE_MAX_NUM = 6;
    $PAGE_ID = 0;
    $MAX_PAGE_ID = 7;

    /*Default Page is zero!*/
    if(isset($_GET['page'])){
        $PAGE_ID = (integer)($_GET['page']);
    }

    $count = $msg_db -> count_all();
    $begin = $PAGE_ID*$PAGE_MAX_NUM;
    $PAGE_NUM = (integer)($count/$PAGE_MAX_NUM);
    if($count%$PAGE_MAX_NUM > 0){
        $PAGE_NUM++;
    }

    $msg_all_id_list = $msg_db -> fetch_all_message_list($begin,$PAGE_MAX_NUM);
    $msg_list    = array();
    foreach($msg_all_id_list as $msg_id){
        array_push($msg_list,$msg_db -> fetch_message($msg_id));
    }
    $msg_db -> close();
?>

<html>
<head>
<meta charset="UTF-8"/>
<link rel="stylesheet" type="text/css" href="css/index.css" >  
</head>
	<?php if ($count == null || $count == 0)
		  {
			  echo("没有需求！");
		  }else{
	?>
	<?php $user_db = new user_info_db(); 
		  $user_db -> open(); ?>
    <?php foreach($msg_list as $msg){ ?>
		<?php $user_info = $user_db -> fetch_user_info($msg -> bid); ?>
		<div id = "photo" >
			<a href = "neederinfocenter.php?nid=<?= $user_info -> id ?>" target="_blank"><img type = "img" src="<?= $user_info -> profile ?>" height = '100px' width = '100px' /></a>
		</div>
		
		<div id = "message" >
			<fieldset>
				<legend >&nbsp <a href = "neederinfocenter.php?nid=<?= $user_info -> id ?>"  target="_blank"><?= $user_info -> nickname?></a> &nbsp/&nbsp<?= $msg -> time ?>&nbsp </legend>		
				<h5 class = "message"><?= $msg -> content ?></h5>
			</fieldset>
			<br/>
		</div>
		
    <?php }?>
	<?php $user_db -> close(); ?>
	
	<div id = "page">   
		<
		<?php if($PAGE_NUM <= $MAX_PAGE_ID){
				for($i = 1;$i <= $PAGE_NUM;$i++){
					if($PAGE_ID == $i - 1){?>
					<?=$i?>&nbsp
		 <?php      }else{?>
					<a href = "buyerneed.php?page=<?= $i-1?>"><?=$i?></a>&nbsp
		<?php       }
				}?>
				>
		<?      }else{
				for($i = 1;$i < $MAX_PAGE_ID;$i++){
							if($PAGE_ID == $i - 1){?>
					<?=$i?>&nbsp
		 <?php      }else{?>
					<a href = "buyerneed.php?page=<?= $i-1?>"><?=$i?></a>&nbsp
		<?php       }
				}?>
				...
				<a href = "buyerneed.php?page=<?= $PAGE_NUM-1?>"><?=$PAGE_NUM?></a>&nbsp
				>
		<?php }?>

		&nbsp
		<input type = "text" name = "pageid" id = "pageid" value = '<?=$PAGE_ID+1?>' align = 'right' size = '3'/>/<?=$PAGE_NUM?>&nbsp
		<input type = "button" onclick = "jump('buyerneed.php?page=',pageid.value - 1)" value = "Go"/>
	<?php } ?>
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
