<?php
	session_start();
	include_once 'user_info_class.php';
	$user_db = new user_info_db();
	$user_db -> open();
	$user_info = $user_db -> fetch_user_info($_GET['nid']);
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
  <p><a class="one" href="neederinfo.php?nid=<?= $user_info -> id?>" target="neederinfo"><?= $user_info -> name?>的信息</a></p>
  <p><a class="one" href="showbuyerneed.php?nid=<?= $user_info -> id?>" target="neederinfo"><?= $user_info -> name?>的需求</a></p>
  <p><a class="one" href="writemessage.php?uid=<?= $user_info -> id?>" target="neederinfo">给<?= $user_info -> name?>留言</a></p>
  <p><a class="one" href="index.php" target="_top">返回主页</a></p>
</div>

<div id="screen">
  <iframe src="neederinfo.php?nid=<?= $user_info -> id?>" width="780" height="1000" name="neederinfo" frameborder="0" noresize="noresize"></iframe>
</div>  

</body>
</html>
