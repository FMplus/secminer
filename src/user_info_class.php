<?php
    include_once("config.php");

    class user_info{
        public $name;
        public $id;
        public $profile;
        public $address;
        public $gender;
        public $location;
        function __construct($name,$id,$profile,$address,$gender,$location){
            $this -> name    = $name;
            $this -> id      = $id;
            $this -> profile = $profile;
            $this -> address = $address;
            $this -> gender  = $gender;
            $this -> location= $location;
        }

        
       function __toString() { 
            return "user_info[name      =   $this->name,
                              id        =   $this->id,
                              profile   =   $this->profile,
                              address   =   $this->address,
                              gender    =   $this->gender,
                              location  =   $this->location]"; 
        }
    }
        
    class db{
        private $db_con;
        
        function __construct(){
            $this -> db_con = null;
        }

        function __destruct(){
            $this -> close();
        }
        
        function open($host,$dbn,$uid,$psw){
            $this -> close();
            $this -> db_con = mysql_connect($host,$uid,$psw);
            if(!$this -> db_con){
                return null;
            }
            mysql_select_db($dbn, $this -> db_con);
            return $this ->db_con;
        }

        function is_open(){
            return $this -> db_con?true:false;
        }

        function close(){
            if($this -> db_con){
                mysql_close($this -> db_con);
                $this -> db_con = null;
            }
        }

        function query($sql){
            if(!$this -> db_con){
                return null;
            }
            return mysql_query($sql,$this ->db_con);
        }
    }
        
    class user_info_db{
        private $sm_db;
        private $lock;

        function __construct(){
            $this -> sm_db = new db();
        }
        
        function __destruct(){
            $this -> sm_db -> close();
        }

        function lock(){
            
        }

        function open(){
            return $this -> sm_db -> open(SM_HOST,SM_DB,SM_UID,SM_PSW);
        }
  
        function close(){
            $this -> sm_db -> close();
        }

        function search_uid($uid){
            if(!$this -> sm_db -> is_open())
                return null;
            $sql = "select count(*) as Existed from user_info 
                    where ('$uid' = `uid`)";
            $result = $this -> sm_db -> query($sql);
            if (!$result)
            {
                die('Error: ' . mysql_error());
            }
            $row = mysql_fetch_array($result);
            $IsExisted = $row['Existed'];
            return ($IsExisted > 0);
        }
        
        function check_uid_psw_ok($uid,$psw){
            if(!$this -> sm_db -> is_open())
                return null;
            $sql = "select count(*) as Existed from user_info 
                    where ('$uid' = `uid` and '$psw' = `psw`)";
            $result = $this -> sm_db -> query($sql);
            if (!$result)
            {
                die('Error: ' . mysql_error());
            }
            $row = mysql_fetch_array($result);
            $IsExisted = $row['Existed'];
            return ($IsExisted > 0);
        }

        function add_user($user,$psw){
            if(!$this -> sm_db -> is_open())
                return null;
            
            $sql = "insert into user_info(`uid`,`name`,`psw`,`profile`,`address`,`gender`,`location`)
                    values ('{$user->id}','{$user->name}','$psw',
                    '{$user->profile}','{$user->address}','{$user->gender}','{$user->location}')";
            $result = $this -> sm_db -> query($sql);
            return $result;
        }

        function remove_user($uid){
            if(!$this -> sm_db -> is_open())
                return null;
                
            $sql = "delete from user_info 
                    where '$uid' = `uid`";
                    
            $result = $this -> sm_db -> query($sql);
            return $result;
        }

        function fetch_user_info($uid,$psw){
            if(!$this -> sm_db -> is_open())
                return null;
            if(!$this -> check_uid_psw_ok($uid,$psw)){
                die('Error: uname or password not match!');
            }
            $sql   = "select name,id,profile,address,gender,location
                      from user_info 
                      where ('$uid' = `uid` and '$psw' = `psw`)";
            
            $result = $this -> sm_db -> query($sql);
            $row = mysql_fetch_array($result);
            
            return new $user_info($row['name'],$row['id'],$row['profile'],$row['address'],$row['gender'],$row['location']);
        }

        function update_user_info($uid,$u_info){
            if(!$this -> sm_db -> is_open())
                return null;
            $sql = "update user_info 
                    set `name` = '{$user->name}',`profile` = '{$user->profile}', 
                        `address` = '{$user->address}',`location` = '{$user->location}',`gender` = '{$user->gender}' 
                    where `uid` = '$uid'";
            $result = $this -> sm_db -> query($sql);        
            return $result;
        }
    }