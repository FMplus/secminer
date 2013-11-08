<?php
    include_once("config.php");

    class user_info{
        public $id;
        public $name;
        public $nickname;
        
        public $addr;
        public $gender;
        public $grade;
        
        public $school;
        public $profile;
        public $phoneno;

        function __construct($id,$name,$nickname,
                             $addr,$gender,$grade,
                             $school,$profile,$phoneno){
            $this -> name    = $name;
            $this -> id      = $id;
            $this -> nickname= $nickname;
            
           
            $this -> addr    = $addr;
            $this -> gender  = $gender;
            $this -> grade   = $grade;

            $this -> school  = $school;
            $this -> profile = $profile;
            $this -> phoneno = $phoneno;
        }

        
       function __toString() { 
            return "user_info[ name     => $this -> name,
                               id       => $this -> id,
                               nickname => $this -> nickname,
                               
                               addr     => $this -> addr,
                               gender   => $this -> gender,
                               grade    => $this -> grade,

                               school   => $this -> school,
                               profile  => $this -> profile,
                               phoneno  => $this -> phoneno]"; 
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
            $this -> query('SET NAMES GBK');
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
            $sql = "lock table user_info ";
            return $this -> sm_db -> query($sql);
        }

        function open(){
            return $this -> sm_db -> open(SM_HOST,SM_DB,SM_UID,SM_PSW);
        }
  
        function close(){
            $this -> sm_db -> close();
        }

        function search_uname($name){
            if(!$this -> sm_db -> is_open())
                return null;
            $sql = "select count(*) as Existed from user_info 
                    where ('$name' = `name`)";
            $result = $this -> sm_db -> query($sql);
            if (!$result)
            {
                die('Error: ' . mysql_error());
            }
            $row = mysql_fetch_array($result);
            $IsExisted = $row['Existed'];
            return ($IsExisted > 0);
        }
        
        function check_uname_psw_ok($name,$psw){
            if(!$this -> sm_db -> is_open())
                return null;

            $psw = md5($psw);

            $sql = "select count(*) as Existed from user_info 
                    where ('$name' = `name` and '$psw' = `password`)";
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
            
            $psw = md5($psw);//password decoder!
            
            $sql = "insert into user_info(`name`,`nickname`,`password`,`profile`,`addr`,`gender`,`grade`,`school`,`phoneno`)
                    values ('{$user -> name}','{$user -> nickname}','$psw','{$user -> profile}',
                    '{$user -> addr}','{$user -> gender}','{$user -> grade}','{$user -> school}','{$user -> phoneno}')";

            $result = $this -> sm_db -> query($sql);
            return $result;
        }

        function remove_user($id){
            if(!$this -> sm_db -> is_open())
                return null;
                
            $sql = "delete from user_info 
                    where '$id' = `id`";
                    
            $result = $this -> sm_db -> query($sql);
            return $result;
        }

        function fetch_user_info($uname,$psw){
            if(!$this -> sm_db -> is_open())
                return null;
            if(!$this -> check_uname_psw_ok($uname,$psw)){
                die('Error: uname or password not match!');
            }
            $sql   = "select id,name,nickname,addr,gender,grade,school,profile,phoneno
                      from user_info 
                      where ('$uname' = `name`)";
            
            $result = $this -> sm_db -> query($sql);
            $row = mysql_fetch_array($result);
            
            return new user_info($row['id'],$row['name'],$row['nickname'],$row['addr'],$row['gender'],$row['grade'],$row['school'],$row['profile'],$row['phoneno']);
        }

        function update_user_info($uid,$user){
            if(!$this -> sm_db -> is_open())
                return null;
            $sql = "update user_info 
                    set `name`='{$user -> name}',`nickname`='{$user -> nickname}',`profile` = '{$user -> profile}',`addr`='{$user -> addr}',`gender`='{$user -> gender}',`grade`='{$user -> grade}',`school`='{$user -> school}',`phoneno`='{$user -> phoneno}'
                    where `id` = '$uid'";
            $result = $this -> sm_db -> query($sql);        
            return $result;
        }
    }