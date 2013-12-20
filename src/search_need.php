<?php
include_once("config.php");
include_once("db_class.php");
class buyer_need{
	public $nickname;
	public $time;
	public $profile;
	public $content;
	public $bid;
	function __construct($nickname = null,
						 $time	   = null,
						 $profile  = null,
						 $content  = null,
						 $bid      = null){
			$this -> nickname = $nickname;
			$this -> time     = $time;
			$this -> profile  = $profile;
			$this -> content  = $content;
			$this -> bid      = $bid;
	}	
	
	function toString(){
		return "buyer_need[$nickname => $this -> nickname;
						   $time     => $this -> time;
						   $profile  => $this -> profile;
						   $content  => $this -> content;
						   $bid      => $this -> bid]";
	}
	
	function __destruct(){
	}
}
class need_db{
	private $ndb;
	function __construct(){
        $this -> ndb = new db();
    }

    function __destruct(){
        $this -> close();
    }
	
	
	function open(){
        $this -> ndb -> open(SM_HOST,SM_DB,SM_UID,SM_PSW);
    }

    function close(){
        $this -> ndb -> close();
    }
	function search_need($keyword,$begin_no = 0, $number = 50){
		if(!$this -> ndb -> is_open()){
            return null;
        }
		
		$sql = "select `nickname`,`time`,`profile`,`content`,`user_info`.`id` as bid
				from goods_need,user_info
				where `goods_need`.`bid` =  `user_info`.`id` 
				and `content` like '%$keyword%'
				limit {$begin_no},{$number}";
		$result = $this -> ndb -> query($sql);
		$arr = array();
		while($row = mysql_fetch_object($result)){
			$need_info = new buyer_need($nickname = $row -> nickname,
										$time     = $row -> time,
										$profile  = $row -> profile,
										$content  = $row -> content,
										$bid      = $row -> bid);
			array_push($arr,$need_info);
		}
		return $arr;
	}
}
?>
