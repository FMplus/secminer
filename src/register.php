<?php
    session_start();
    include_once("config.php");
    $con = mysql_connect(SM_HOST,SM_UID,SM_PSW);
    if (!$con)
      {
        die('Could not connect: ' . mysql_error());
      }

    $uid = $_GET['uid'];
    $psw = $_GET['psw'];
    mysql_select_db(SM_DB, $con);
    $sql = "select count(*) as Existed from user_info
            where ('$uid' = `uid`)";
    $result = mysql_query($sql,$con);
    if (!$result)
    {
        die('Error: ' . mysql_error());
    }
    $row = mysql_fetch_array($result);
    $IsExisted = $row['Existed'];
    if($IsExisted > 0)
    {
        die('Error: This user id has already existed!');
    }
    $sql = "INSERT INTO user_info(uid,psw) 
            VALUES ('$uid', '$psw')";
    if(!mysql_query($sql,$con))
    {
        die('Error: ' . mysql_error());
    }
    mysql_close($con);
    $_SESSION['uid']    =   $uid;
    $_SESSION['psw']    =   $psw;
?>