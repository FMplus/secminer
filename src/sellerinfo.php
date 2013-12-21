<?php 
	session_start();
	include_once 'user_info_class.php';
	$user_db = new user_info_db();
	$user_db -> open();
	$user_info = $user_db -> fetch_user_info($_GET['uid']);
	$user_db -> close();
	
?>

<html>
<head>
<meta charset="UTF-8"/>
<link rel="stylesheet" type="text/css" href="css/sellerinfocenter.css" >
</head>
<body>

<div id="sellerinfo">
<form >
  <fieldset>
    <legend><?= $user_info -> name ?> 的信息</legend>
	<br/>
	用户头像:
	<img src="<?= $user_info -> profile ?>" height = '100px' width = '100px'/>
	<br/><br/>
		
    昵称：
	<?= $user_info -> nickname ?>
	<br/><br/>
	
	用户名：
	<?= $user_info -> name ?>
	<br/><br/>
	
	性别:
	   <?php if($user_info -> gender == 'm'){?>男
	   <?php }else if($user_info -> gender == 'f'){?>女
	   <?php }else{?>保密<?php }?>
	<br/><br/>
	年级:
	<?= $user_info -> grade ?>
	<br/><br/>
	
	学校:
	<?= $user_info -> school ?>
	<br/><br/>
	
	公寓地址:
	<?= $user_info -> addr ?>
	<br/><br/>
	
	联系电话：
	<?= $user_info -> phoneno ?>
	<br/><br/>
	</fieldset>

</form>
</div>

</body>
</html>
