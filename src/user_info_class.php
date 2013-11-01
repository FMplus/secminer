<?php
    include_once("config.php");

    class user_info{
        $name;
        $id;
        $profile;
        $address;
        $gender;
        $location;
        
        function __construct($name,$id,$profile,$address,$gender,$location){
            $this -> name    = $name;
            $this -> id      = $id;
            $this -> profile = $profile;
            $this -> address = $address;
            $this -> gender  = $gender;
            $this -> location= $location;
        }
        
       function __toString() { 
            return "OAuthConsumer[key=$this->key,secret=$this->secret]"; 
        } 
    }
        
    class user_info_db{
        $db_con;

        function __construct(){
            $db_con = null;
        }
        
        function open(){
            if($db_con){
                mysql_close($db_con);
                $db_con = null;
            }

            $db_con = mysql_connect(SM_HOST,SM_UID,SM_PSW);

            if(!$db_con){
                return $db_con;
            }
            mysql_select_db(SM_DB, $con);
            return $db_con;
        }
        
        function close(){
            if($db_con){
                mysql_close($db_con);
                $db_con = null;
            }
        }
        
        function search_uid($uid){
            if(!$db_con)
                return null;
            $sql = "select count(*) as Existed from user_info
                    where ('$uid' = `uid`)";
            $result = mysql_query($sql,$db_con);
            if (!$result)
            {
                die('Error: ' . mysql_error());
            }
            $row = mysql_fetch_array($result);
            $IsExisted = $row['Existed'];
            return ($IsExisted > 0);
        }
        
        function check_uid_psw_ok($uid,$psw){
            if(!$db_con)
                return null;
            $sql = "select count(*) as Existed from user_info
                    where ('$uid' = `uid` and '$psw' = `psw`)";
            $result = mysql_query($sql,$db_con);
            if (!$result)
            {
                die('Error: ' . mysql_error());
            }
            $row = mysql_fetch_array($result);
            $IsExisted = $row['Existed'];
            return ($IsExisted > 0);
        }
        
        function fetch_user_info($uid,$psw){
            if(!$db_con)
                return null;
            if(check_uid_psw_ok($uid,$psw)){
                $uinfo = new user_info();
                return $uinfo;
            }
            return null;
        }
    }
    
?>