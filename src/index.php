<?php
	session_start();
	include_once('user_info_class.php');
	$user_info = null;
	if(isset($_SESSION['id'])){
		$user_db = new user_info_db();
		$user_db -> open();
		$user_info = $user_db -> fetch_user_info($_SESSION['id']);
		$user_db -> close();
	}
?>
<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" type="text/css" href="css/index.css" >
</head>

<body>

<div id="statesign">
<ul>
<?php if(isset($_SESSION['id'])){?>
<li><a class="two" href="<?= ($_SESSION['role']=='owner'?'seller':'')?>infocenter.html" /><?= ($user_info -> nickname)?>&nbsp;</a></li>
<li><a class="two" href="signout.php"/>退出</a></li>
<?php null;}else{?>
<li><a class="two" href="signin.html" />登陆&nbsp;</a></li>
<li><a class="two" href="signup.html" />注册&nbsp;</a></li>
<?php null;}?>
</ul>
</div>

<div id="container">

<div id="header">
<p>
<img src="img/logo1.3.6.bmp" width="400" height="100" />
</p>
</div>


<div id="navi_bar">
<ul>
<li><a class="one" href="allgoods.php" target="index">商品</a></li>
<li><a class="one" href="buyerneed.php" target="index">需求信息</a></li>
<li><a class="one" href="sd.php" target="index">搜索商品</a></li>
</ul>
</div>

<div id="content">
<iframe src="allgoods.php" width="1000" height="1000" name="index" frameborder="0" noresize="noresize"></iframe>
</div>


</div>
<div id ="footer">
<p>Copyright Secminer.com.cn</p>
</div>
</body>
</html>
