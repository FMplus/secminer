<?php
	session_start();
	include_once 'user_info_class.php';
	$user_db = new user_info_db();
	$user_db -> open();
	$user_info = $user_db -> fetch_user_info($_GET['sid']);
	$user_db -> close();

?>

<html>
<head>
<meta charset="UTF-8"/>
<link rel="stylesheet" type="text/css" href="css/sellerinfocenter.css" >
<head>
<body>

<center>
<p>
<a href="index.php" target="_top">
<img src="img/logo1.3.4.bmp" width="280" height="70"/></a>
</p>
</center>

<div id="navi">
  <p><a class="one" href="sellerinfo.php?uid=<?= $user_info -> id?>" target="sellerinfo"><?= $user_info -> name?>的信息</a></p>
  <p><a class="one" href="showsellergoods.php?uid=<?= $user_info -> id?>" target="sellerinfo"><?= $user_info -> name?>的商品</a></p>
  <p><a class="one" href="writemessage.php?uid=<?= $user_info -> id?>" target="sellerinfo">给<?= $user_info -> name?>留言</a></p>
  <p><a class="one" href="index.php" target="_top">返回主页</a></p>
</div>

<div id="screen">
  <iframe src="sellerinfo.php?uid=<?= $user_info -> id?>" width="780" height="1000" name="sellerinfo" frameborder="0" noresize="noresize"></iframe>
</div>  

</body>
</html>
