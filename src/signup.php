<?php
    //session_start();
    include_once("config.php");
    include_once("user_info_class.php");
    if (isset($_GET['action']) && $_GET['action'] == 'submitted') {
        $user = new user_info(  $name       = $_GET['name'],
                                $id         = $_GET['uid'],
                                $profile    = "img/default_profile.bmp",
                                $address    = $_GET['addr'],
                                $gender     = $_GET['gender'],
                                $location   = $_GET['loc']
                                );
        $user_db = new user_info_db();
        $user_db -> open();
        $user_db -> add_user($user,$_GET['psw']);
        $user_db -> close();
    }
?>