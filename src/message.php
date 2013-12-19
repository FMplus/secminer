<?php
    include_once("db_class.php");
    include_once("config.php");

    class msg{
        public $id;
        public $bid;
        public $content;
        public $time;
        
        function __construct($id = null,$bid = null,$content = null,$time = null){
            $this -> id         = $id;
            $this -> bid        = $bid;
            $this -> content    = $content;
            $this -> time       = $time;
        }
        
        function __toString(){
            return "msg[id      => '{$this -> id}',
                        bid     => '{$this -> bid}',
                        content => '{$this -> content}',
                        time    => '{$this -> time}'
                        ]";
        }
    }

    class message_db{
        private $sm_db;

        function __construct(){
            $this -> sm_db = new db();
        }

        function __destruct(){
            $this -> close();
        }

        function open(){
            $this -> sm_db -> open(SM_HOST,SM_DB,SM_UID,SM_PSW);
        }

        function close(){
            $this -> sm_db -> close();
        }

        function send_message($msg){
            if(!$this -> sm_db -> is_open()){
                return null;
            }
   
            $sql = "insert into goods_need(`bid`,`content`)
                    values('{$msg -> bid}','{$msg -> content}')";
 
            return $this -> sm_db -> query($sql);
        }

        function delete_message($bid,$id){
            if(!$this -> sm_db -> is_open()){
                return null;
            }
            $sql = "delete 
                    from `goods_need`
                    where '{$bid}' = `bid` and '{$id}' = `id`";

            return $this -> sm_db -> query($sql);
        }

        function fetch_message($id){
            if(!$this -> sm_db -> is_open()){
                return null;
            }
            $sql = "select `id`,`bid`,`content`,`time`
                    from goods_need
                    where '{$id}' = `id`";
                    
            $result = $this -> sm_db -> query($sql);
            
            if(!$result){
                return null;
            }
            $row = mysql_fetch_array($result);
            if(!isset($row)){
                return null;
            }
            
            return new msg($id      = $row['id'],
                           $bid     = $row['bid'],
                           $content = $row['content'],
                           $time    = $row['time']
                           );
        }

        function fetch_message_list($bid,$begin_no = 0,$number = 50){
            if(!$this -> sm_db -> is_open()){
                return null;
            }
            $sql = "select `id`
                    from goods_need
                    where '{$bid}' = `bid`
                    order by `time` desc
                    limit {$begin_no},{$number}";
            $result = $this -> sm_db -> query($sql);
            if(!$result){
                return null;
            }
            $row    =   null;
            $list   =   array(); 
            while($row = mysql_fetch_array($result)){
                array_push($list,$row['id']);
            }
            return $list;
        }
    
        function count($bid){
            $sql = "select count(*) as nr
                    from goods_need
                    where '{$bid}' = `bid`";
            $result = $this -> sm_db -> query($sql);
            if(!$result){
                return null;
            }
            $row = mysql_fetch_array($result);
            if(!isset($row)){
                return null;
            }
            return $row['nr'];
        }
		
		function count_all(){
            $sql = "select count(*) as nr
                    from goods_need";
            $result = $this -> sm_db -> query($sql);
            if(!$result){
                return null;
            }
            $row = mysql_fetch_array($result);
            if(!isset($row)){
                return null;
            }
            return $row['nr'];
        }
		
		function fetch_all_message_list($begin_no = 0,$number = 50){
            if(!$this -> sm_db -> is_open()){
                return null;
            }
            $sql = "select `id`
                    from goods_need
                    order by `time` desc
                    limit {$begin_no},{$number}";
            $result = $this -> sm_db -> query($sql);
            if(!$result){
                return null;
            }
            $row    =   null;
            $list   =   array(); 
            while($row = mysql_fetch_array($result)){
                array_push($list,$row['id']);
            }
            return $list;
        }
    }
