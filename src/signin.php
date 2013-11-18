<?php
    session_start();
    include_once("config.php");
    include_once("user_info_class.php");
    if(isset($_SESSION['id'])){
        echo "{$_SESSION['id']}"." is already signing up!";
        exit(0);
    }
    $uname      = $_POST['name'];
    $psw        = $_POST['password'];

    $user_db    = new user_info_db();
    $user_db -> open();
    if($user_db -> check_uname_psw_ok($uname,$psw)){
        $uid = $user_db -> fetch_user_id($uname);
        $_SESSION['id'] = $uid;
		$_SESSION['role'] = $_POST['role'];
        echo "<a href = 'index.php'>密码成功，请稍等</a>";
        //echo "{$_SESSION['id']}"." signed in successfully!";
    }else{
        echo "<a href = 'signin.html'>密码错误，请重新登陆</a>";
    }
    $user_db -> close();
?>

