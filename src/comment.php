<?php
include_once("config.php");
include_once("db_class.php");

class comment_info{
	public $id;
	public $time;
	public $content;
	public $oid;
	function __construct($id = null,$time = null, $content = null,$oid = null){
		$this -> id = $id;
		$this -> time = $time;
		
		$this -> content = $content;
		$this -> oid = $oid;
	}
	
	function __toString(){
		return "comment_info[id 		=> $this -> id,
							 time 		=> $this -> time,
							 content 	=> $this -> content,
							 oid		=> $this -> oid
		]";
	}
}

class comment_info_db{
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
	
	function add_comment_asoid($oid,$content){
		if(!$this -> sm_db -> is_open())
                return null;
		
		$sql = "select count(*) as count
				from `comment_info`
				where `oid` = '$oid'";
		$result = $this -> sm_db -> query($sql);
		$row = mysql_fetch_row($result);
		if($row[0] > 0)
			return -1;
		
		$sql = "insert into comment_info(`content`,`oid`)
				values ('$content','$oid')";
		$result = $this -> sm_db -> query($sql);
		
		/*Get comment id*/
		$sql = "SELECT LAST_INSERT_ID()";
		$result = $this -> sm_db -> query($sql);
		$row = mysql_fetch_row($result);
		$id = $row[0];
		return $id;
	}
	
	function change_comment_asid($cid,$content){
		if(!$this -> sm_db -> is_open())
                return null;
		$sql = "update comment_info
				set `content` = '$content'
				where `id` = '$cid'";
		$result = $this -> sm_db -> query($sql);
		return $result;
	}
	
	function delete_comment_asid($cid){
		if(!$this -> sm_db -> is_open())
                return null;
		$sql = "delete from comment_info
				where `id` = '$cid'";
		$result = $this -> sm_db -> query($sql);
		return $result;	
	}
	
	function fetch_comment_asoid($oid){
		 if(!$this -> sm_db -> is_open())
                return null;
		 $sql = "select *
				 from `comment_info`
				 where `oid` = '$oid'";
		 $result = $this -> sm_db -> query($sql);
		 if(!isset($result))
			return null;
		$row = mysql_fetch_object($result);
		if($row != null){
			$comment = new comment_info($id = $row -> id,
										$time = $row -> time,
										$content = $row -> content,
										$oid = $row -> oid);
			return $comment;
		}
		return null;
	}
	
	function fetch_comment_asgid($gid){
		if(!$this -> sm_db -> is_open())
                return null;
		$sql = "select *
				 from comment_info natural join order_goods
				 where `gid` = '$gid'";
		
		 $result = $this -> sm_db -> query($sql);
		 if(!$result){
			return null;
		 }
		$row = mysql_fetch_object($result);
		if($row != null){
			$comment = new comment_info($id = $row -> id,
										$time = $row -> time,
										$content = $row -> content,
										$oid = $row -> oid);
			return $comment;
		}
		return null;
	}
}
?>
