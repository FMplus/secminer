<?php 
	session_start();
	include_once 'user_info_class.php';
	$user_db = new user_info_db();
	$user_db -> open();
	$user_info = $user_db -> fetch_user_info($_SESSION['id']);
	//$gender = 'm';
	//$id = "1110310308";
	//$photo = 'img/Desert.jpg';
	$user_db -> close();
?>
<html>
<head>
<meta charset="UTF-8"/>
<link rel="stylesheet" type="text/css" href="css/infocenter.css" >
</head>
<body>

<div id="buyerinfo">
<form action = "update_user_info.php" method = "post" enctype = "multipart/form-data">
  <fieldset>
    <legend>个人信息</legend>
	<br/>
	用户头像:
	<img src="<?= $user_info -> profile ?>" height = '12%' width = '12%'/>
	<input type="file" name="profile" value="本地上传" />
	<br/><br/>
	
	用户名：
	<?= $user_info -> name ?>
	<br/><br/>
	
    昵称：
	<input type = "text" name = "nickname" value = "<?= $user_info -> nickname ?>" />
	<br/><br/>
	
	性别:
	<input type="radio" name="gender" value="m" <?php if($user_info -> gender == 'm') {?> checked="checked" <?php null;}?>/> 男
	<input type="radio" name="gender" value="f" <?php if($user_info -> gender == 'f') {?> checked="checked" <?php null;}?>/> 女
	<input type="radio" name="gender" value="u" <?php if($user_info -> gender == 'u') {?> checked="checked" <?php null;}?>/> 保密
	<br/><br/>
	年级:
	<input type = "text" name = "grade" value = "<?= $user_info -> grade ?>" />
	<br/><br/>
	
	学校:
	<input type = "text" name = "school" value = "<?=  $user_info -> school ?>" />
	<br/><br/>
	
	公寓地址:
	<input type = "text" name = "addr" value = "<?= $user_info -> addr ?>" />
	<br/><br/>
	
	联系电话：
	<input type = "text" name = "phoneno" value = "<?= $user_info -> phoneno ?>" />
	<br/><br/>
	</fieldset>
<input type = "submit" value = "提交" ></input>
</form>
	
</div>


</body>
</html>
