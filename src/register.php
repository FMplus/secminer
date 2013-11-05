<?php
    //session_start();
    include_once("config.php");
    include_once("user_info_class.php");
    if (isset($_GET['action']) && $_GET['action'] == 'submitted') {
        $uid = $_GET['uid'];
        $psw = $_GET['psw'];
        $db_test = new db;
        if(!$db_test -> open(SM_HOST,SM_DB,SM_UID,SM_PSW)){
            die("Wrong : the database can't be open!");
        }
        
        $sql = "insert into user_info(`uid`,`psw`)
                values('$uid','$psw')";

        if(!$db_test -> query($sql)){
            die("Wrong : Can't insert into the database!");
        }

        $db_test -> close();
    }
?>