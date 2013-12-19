<?php
include_once("config.php");
include_once("db_class.php");
class reply_info{
	public $id;
	public $time;
	public $content;
	public $cid;
	function __construct($id = null,$time = null, $content = null,$cid = null){
		$this -> id = $id;
		$this -> time = $time;
		
		$this -> content = $content;
		$this -> cid = $cid;
	}
	
	function __toString(){
		return "comment_info[id 		=> $this -> id,
							 time 		=> $this -> time,
							 content 	=> $this -> content,
							 cid		=> $this -> cid
		]";
	}
}

class reply_info_db{
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
	
	function reply_comment_ascid($cid,$content){
		if(!$this -> sm_db -> is_open())
                return null;
		$sql = "select count(*) as count
				from `reply_info`
				where `cid` = '$cid'";
		$result = $this -> sm_db -> query($sql);
		$row = mysql_fetch_row($result);
		if($row[0] > 0)
			return -1;
		
		$sql = "insert into reply_info(`content`,`cid`)
				values ('$content','$cid')";
		$result = $this -> sm_db -> query($sql);
		
		/*Get reply id*/
		$sql = "SELECT LAST_INSERT_ID()";
		$result = $this -> sm_db -> query($sql);
		$row = mysql_fetch_row($result);
		$id = $row[0];
		return $id;
	}
	
	function change_reply_asid($id,$content){
		if(!$this -> sm_db -> is_open())
                return null;
		$sql = "update reply_info 
				set `content` = '$content'
				where `id` = '$id'";
		$result = $this -> sm_db -> query($sql);
		return $result;
	}
	
	function delete_reply_asid($id){
		if(!$this -> sm_db -> is_open())
                return null;
		$sql = "delete from reply_info 
				where `id` = '$id'";
		$result = $this -> sm_db -> query($sql);
		return $result;
	}
	
	function fetch_reply_ascid($cid){
		if(!$this -> sm_db -> is_open())
                return null;
		$sql = "select * 
				from  reply_info
				where `cid` = '$cid'";
		$result = $this -> sm_db -> query($sql);
		$row = mysql_fetch_object($result);
		if($row != null){
			$reply = new reply_info($id = $row -> id,
									$time = $row -> time,
									$content = $row -> content,
									$cid = $row -> cid);
			 return $reply;
		 }
		 return null;
	}
}
?>
