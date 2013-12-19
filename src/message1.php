<?php
include_once("config.php");
include_once("db_class.php");

class msg_info{
	public $id;
	public $time;
	public $content;
	
	public $listener;
	public $talker;
	
	function __construct($id 		= null,
						 $time 		= null,
						 $content	= null,
						 $listener	= null,
						 $talker 	= null)
	{
		$this -> id 	    = $id;
		$this -> time 	    = $time;
		$this -> content    = $content;
		$this -> listener	= $listener;
		$this -> talker 	= $talker;
	}
	
	function __toString(){
		return "goodstag[id 	 => $this -> id,
						 time	 => $this -> time,
						 talker  => $this -> talker,
						 listener=> $this -> listener,
						 content => $this -> content
						 ]";
	}
}


class msg_db{
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
	
	function message_to_listener($talker,$listener,$content){
		if(!$this -> sm_db -> is_open())
            return null;
        $sql = "insert into msg_info(`talker`,`listener`,`content`)
				values('$talker','$listener','$content')";
		//print_r($sql);
        $result = $this -> sm_db -> query($sql);
        if (!$result)
        {
            return NULL;
        }
		/*Get message id*/
        $sql = "SELECT LAST_INSERT_ID()";
		$result = $this -> sm_db -> query($sql);
		$row = mysql_fetch_row($result);
		$id = $row[0];
		return $id;
	}
	
	function modify_msg_aid($id,$content){
		if(!$this -> sm_db -> is_open())
            return null;
        $sql = "update msg_info
				set `content` = '$content'
				where `id` = '$id'";
        $result = $this -> sm_db -> query($sql);
        return $result;
	}
	
	function fetch_msg_aslistener($listener,$begin_no,$number){
		if(!$this -> sm_db -> is_open())
            return null;
		$sql = "select *
				from msg_info
				where `listener` = '$listener'
				order by `time` DESC
				limit {$begin_no},{$number}";
		//print_r($sql);
        $result = $this -> sm_db -> query($sql);

		$msgarr = array();
		while($row = mysql_fetch_object($result))
		{
			$msg = new msg_info($id 	= $row -> id,
								$time   = $row -> time,
								$content= $row -> content,
								$listener= $listener,
								$talker	= $row -> talker);
			array_push($msgarr,$msg);
		}
        return $msgarr;
	}
	
	function fetch_msg_astalker($talker,$begin_no,$number){
		if(!$this -> sm_db -> is_open())
            return null;
		$sql = "select *
				from msg_info
				where `talker` = '$talker'
				order by `time` DESC
				limit {$begin_no},{$number}";
        $result = $this -> sm_db -> query($sql);
		
		$msgarr = array();
		while($row = mysql_fetch_object($result))
		{
			$msg = new msg_info($id 	= $row -> id,
								$time   = $row -> time,
								$content    = $row -> content,
								$listener	= $row -> listener,
								$talker = $talker
								);
			array_push($msgarr,$msg);
		}
        return $msgarr;
	}
	
	function msg_get_count($uid){
		$sql = "select count(*) as count
				from msg_info
				where `listener` = {$uid}";
		
		$result = $this -> sm_db -> query($sql);
		$row = mysql_fetch_array($result);
		
		return $row['count'];
	}

	function delete_msg_asid($id){
		if(!$this -> sm_db -> is_open())
            return null;
		$sql = "delete from msg_info 
				where `id` = '$id'";
        $result = $this -> sm_db -> query($sql);
		return $result;
	}
}
?>
