<?php
    //session_start();
    include_once("config.php");
    include_once("user_info_class.php");

    $user = new user_info(    
                              $id           = 0,//no effect
                              $name         = $_POST['name'],
                              $nickname     = $_POST['nickname'],
                              $addr         = $_POST['addr'],
                              $gender       = $_POST['gender'],
                              $grade        = $_POST['grade'],
                              $school       = $_POST['school'],
                              $profile      = "img/profile.jpg",
                              $phoneno      = $_POST['phoneno']
                            );
    
    $user_db = new user_info_db();
    $user_db -> open();
    $user_db -> add_user($user,$_POST['password']);
    $user_db -> close();

    header("signin.html");
?>