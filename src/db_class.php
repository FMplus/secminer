<?php
include_once("db_config.php");

class db{
        private $db_con;
        private $is_open;

        function __construct(){
			$this -> is_open = false;
            $this -> db_con = null;
        }

        function __destruct(){
            $this -> close();
        }
        
        function open($host,$dbn,$uid,$psw){
            global $DB_REFERENCE_COUNT;
            $this -> close();
            $this -> db_con = mysql_connect($host,$uid,$psw);
            if(!$this -> db_con){
                return null;
            }
            mysql_select_db($dbn, $this -> db_con);
            $this -> is_open = true;
            $this -> query("SET NAMES UTF8");
            $DB_REFERENCE_COUNT++;//We just used database secminer,it is used for reference counter
            return $this -> db_con;
        }

        function is_open(){
            return $this -> is_open;
        }

        function close(){
            global $DB_REFERENCE_COUNT;
            $DB_REFERENCE_COUNT --;
            if($DB_REFERENCE_COUNT == 0&&$this -> is_open){
                mysql_close($this -> db_con);
            }
            $this -> is_open = false;
            $this -> db_con  = null;
        }

        function query($sql){
            if(!$this -> is_open){
                return null;
            }
            return mysql_query($sql,$this ->db_con);
        }
    }
