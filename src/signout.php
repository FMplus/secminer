<?php
    session_start();
    if(isset($_SESSION['id'])){
        unset($_SESSION['id']);
		unset($_SESSION['role']);
        echo "<a href = 'index.php'>成功退出！点击返回主页</a>";
    }else{
        echo "<a href = 'signin.html'>请先登录！</a>";
    }
