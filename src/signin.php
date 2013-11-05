<?php
    session_start();
    include_once("config.php");
    include_once("user_info_class.php");
    if(isset($_SESSION['uid'])){
        echo "{$_SESSION['uid']}"." is already signing up!";
        //exit(0);
    }
    if (isset($_GET['action']) && $_GET['action'] == 'submitted') {
        $uid     = $_GET['uid'];
        $psw     = $_GET['psw'];
        $user_db = new user_info_db();
        $user_db -> open();
        if($user_db -> check_uid_psw_ok($uid,$psw)){
            $_SESSION['uid'] = $uid;
            echo "{$uid}"." signed in successfully!";
        }else{
            die("Error : WRONG password or user id not exisred!");
        }
        $user_db -> close();
    }
?>
