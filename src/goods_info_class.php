<?php
include_once("config.php");
include_once("db_class.php");

class goods_info{
	public $id;
	public $name;
	public $usingdgr;
	
	public $originalprice;
	public $currentprice;
	public $dscrb;
	
	public $quantity;
	public $state;
	public $sid;
	
	public $photo;
	public $tag;
	function __construct($id = null,$name = null,$usingdgr = null,
						 $originalprice = null,$currentprice = null,
						 $dscrb = null,$quantity = null,$state = null,$sid = null)
	{
		$this -> id = $id;
		$this -> name = $name;	
		$this -> usingdgr = $usingdgr;
		
		$this -> originalprice = $originalprice;
		$this -> currentprice = $currentprice;
		$this -> dscrb = $dscrb;
		
		$this -> quantity = $quantity;
		$this -> state = $state;
		$this -> sid = $sid;
		
		$this -> photo = array();
		$this -> tag = array();
	}
	
	function __toString() { 
			return "goods_info[id       => $this -> id,
							   name     => $this -> name,
                               usingdgr => $this -> usingdgr,
                               
                               origianlprice     => $this -> originalprice,
                               currentprice   => $this -> currentprice,
                               dscrb    => $this -> dscrb,

                               quantity   => $this -> quantity,
                               state  => $this -> state,
                               sid    => $this -> sid,
							   photo => $this -> photo
							   tag => $this -> tag;
							   ]"; 
        }
}

class goods_info_db
{
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
	
	private function search_gname($name){
            if(!$this -> sm_db -> is_open())
                return null;
            $sql = "select count(*) as Existed from goods_info 
                    where ('$name' = `name`)";
            $result = $this -> sm_db -> query($sql);
            if (!$result)
            {
               return NULL;
            }
            $row = mysql_fetch_array($result);
            $IsExisted = $row['Existed'];
            return ($IsExisted > 0);
    }
		
	private function search_gid($id){
            if(!$this -> sm_db -> is_open())
                return null;
            $sql = "select count(*) as Existed from goods_info 
                    where ('$id' = `id`)";

            $result = $this -> sm_db -> query($sql);
            if (!$result)
            {
                return NULL;
            }
            $row = mysql_fetch_array($result);
            $IsExisted = $row['Existed'];
            return ($IsExisted > 0);
    }
	
	function add_goods($goods){
            if(!$this -> sm_db -> is_open())
                return null;
				
			$sid = $goods -> sid;
			$sql = "select name 
					from goods_info 
					where '$sid' = `sid`";
			$result = $this -> sm_db -> query($sql);
			$arr = array();
			while($row = mysql_fetch_object($result))
			{
				array_push($arr,$row -> name);
			}
			$bool = in_array($goods -> name,$arr);
			if($bool)
			{
				echo "Insert failed!<br />".$goods -> name." belong to seller id ".$goods -> sid." has existed.<br /> ";
				return NULL;
			}
            $sql = "insert into goods_info(`name`,`usingdgr`,`originalprice`,`currentprice`,`dscrb`,`quantity`,`state`,`sid`)
                    values ('{$goods -> name}','{$goods -> usingdgr}','{$goods -> originalprice}',
                    '{$goods -> currentprice}','{$goods -> dscrb}','{$goods -> quantity}','{$goods -> state}','{$goods -> sid}')";
            $result = $this -> sm_db -> query($sql);
            return $result;
    }
	
	function remove_goods($gid){
            if(!$this -> sm_db -> is_open())
                return null;
            if(!$this -> search_gid($gid)){
				return NULL;
			}
			
			$sql = "delete from goods_photo 
                    where '$gid' = `id`";	
			$this -> sm_db -> query($sql);
			
			$sql = "delete from goods_tag 
                    where '$gid' = `id`";	
			$this -> sm_db -> query($sql);
			
			$sql = "delete from goods_sort
                    where '$gid' = `id`";	
			$this -> sm_db -> query($sql);
			
			$sql = "delete from goods_focus 
                    where '$gid' = `id`";	
			$this -> sm_db -> query($sql);
			
            $sql = "delete from goods_info 
                    where '$gid' = `id`";	
            $result = $this -> sm_db -> query($sql);
            return $result;
        }
	
	function fetch_goods_info_asname($name){
            if(!$this -> sm_db -> is_open())
                return null;
            if(!$this -> search_gname($name)){
                return null;
            }
            $sql   = "select *
                      from  goods_info
                      where '$name' = `name`";
            
            $result = $this -> sm_db -> query($sql);
			if($result == null)
			{
				return null;
			}
			$row = mysql_fetch_object($result);
			$goods = new goods_info($row -> id,$row ->name,$row ->usingdgr,
						 $row ->originalprice,$row ->currentprice,
						 $row ->dscrb,$row ->quantity,$row ->state,$row ->sid);
			$id = $goods -> id;
			$sql   = "select photo
                      from  goods_photo
                      where '$id' = `id`";
            $result = $this -> sm_db -> query($sql);
			if($result == null)
			{
				return null;
			}
			while($row = mysql_fetch_object($result))
			{
				array_push($goods -> photo,$row -> photo);
			}
			
			$sql   = "select tag
                      from  goods_tag
                      where '$id' = `id`";
            $result = $this -> sm_db -> query($sql);
			if($result == null)
			{
				return null;
			}
			while($row = mysql_fetch_object($result))
			{
				array_push($goods -> tag,$row -> tag);
			}
			return $goods;
        }
	
	function fetch_goods_info_asid($gid){
            if(!$this -> sm_db -> is_open())
                return null;
            if(!$this -> search_gid($gid)){
                return null;
            }
            $sql   = "select *
                      from  goods_info
                      where '$gid' = `id`";
            
            $result = $this -> sm_db -> query($sql);
			if($result == null)
			{
				return null;
			}
			$row = mysql_fetch_object($result);
			
			$goods = new goods_info($row -> id,$row ->name,$row ->usingdgr,
						 $row ->originalprice,$row ->currentprice,
						 $row ->dscrb,$row ->quantity,$row ->state,$row ->sid);
			
			
			$sql   = "select photo
                      from  goods_photo
                      where '$gid' = `id`";
            $result = $this -> sm_db -> query($sql);
			if($result == null)
			{
				return null;
			}
			while($row = mysql_fetch_object($result))
			{
				array_push($goods -> photo,$row -> photo);
			}
			
			$sql   = "select tag
                      from  goods_tag
                      where '$gid' = `id`";
            $result = $this -> sm_db -> query($sql);
			if($result == null)
			{
				return null;
			}
			while($row = mysql_fetch_object($result))
			{
				array_push($goods -> tag,$row -> tag);
			}
			return $goods;
        }
		
	function  fetch_goodsid(){
		if(!$this -> sm_db -> is_open())
            return null;
        $sql = "SELECT LAST_INSERT_ID()";
        $result = $this -> sm_db -> query($sql);
		$row = mysql_fetch_row($result);
		$id = $row[0];
        return $id;
	}
	
	function update_goods_quantity($gid,$int){
            if(!$this -> sm_db -> is_open())
                return null;
            $sql = "update goods_info 
					set `quantity` = '$int'  
					where `id` = '$gid'";
            $result = $this -> sm_db -> query($sql);
            return $result;
    }
	
	function update_goods_dscrb($gid,$dscrb){
            if(!$this -> sm_db -> is_open())
                return null;
            $sql = "update goods_info 
					set `dscrb` = '$dscrb'
					where '$gid' = `id`";
            $result = $this -> sm_db -> query($sql);
            return $result;
    }
	
	function update_goods_currentprice($gid,$price){
            if(!$this -> sm_db -> is_open())
                return null;
            $sql = "update goods_info 
					set `currentprice` = '$price'  
					where `id` = '$gid'";
            $result = $this -> sm_db -> query($sql);
            return $result;
    }
	
	function update_goods_name($gid,$name){
			if(!$this -> sm_db -> is_open())
				return null;
			$sql = "update goods_info
					set `name` = '$name'
					where `id` = '$gid'";
			$result = $this -> sm_db -> query($sql);
            return $result;		
	}
	
	function update_goods_usingdgr($gid,$usingdgr){
			if(!$this -> sm_db -> is_open())
				return null;
			$sql = "update goods_info
					set `usingdgr` = '$usingdgr'
					where `id` = '$gid'";
			$result = $this -> sm_db -> query($sql);
            return $result;		
	}
	
	function update_goods_originalprice($gid,$price){
            if(!$this -> sm_db -> is_open())
                return null;
            $sql = "update goods_info 
					set `originalprice` = '$price'  
					where `id` = '$gid'";
            $result = $this -> sm_db -> query($sql);
            return $result;
    }
	
	function update_goods_state($gid,$state){
            if(!$this -> sm_db -> is_open())
                return null;
            $sql = "update goods_info 
					set `state` = '$state'  
					where `id` = '$gid'";
            $result = $this -> sm_db -> query($sql);
            return $result;
    }
	
	function update_goods_photo($gid,$ophoto,$nphoto){
			if(!$this -> sm_db -> is_open())
                return null;
			$sql = "update goods_photo set 
				   `photo` = '$nphoto'
					where `id` = '$gid' and `photo` = '$ophoto'";
			$result = $this -> sm_db -> query($sql);
            return $result;
	}
	
	function add_goods_photo($gid,$parr){
			if(!$this -> sm_db -> is_open())
                return null;
			for($x = 0; $x < count($parr); $x++){
				$photo = $parr[$x];
				$sql = "insert into goods_photo(id,photo) 
					   values('$gid','$photo')";
				$result = $this -> sm_db -> query($sql);
            }
	}
	
	function delete_goods_photo($gid,$photo){
			if(!$this -> sm_db -> is_open())
                return null;
			$sql = "delete from goods_photo 
					where `id` = '$gid' and `photo` = '$photo'";
			$result = $this -> sm_db -> query($sql);
			return $result;
	}
	
	function add_goods_tag($gid,$tarr){
			if(!$this -> sm_db -> is_open())
                return null;
			for($x = 0; $x < count($tarr); $x++){
				$tag = $tarr[$x];
				$sql = "insert into goods_tag(id,tag) 
					   values('$gid','$tag')";
				$result = $this -> sm_db -> query($sql);
            }
	}
	
	function delete_goods_tag($gid,$tag){
			if(!$this -> sm_db -> is_open())
                return null;
			$sql = "delete from goods_tag 
					where `id` = '$gid' and `tag` = '$tag'";
			$result = $this -> sm_db -> query($sql);
			return $result;
	}

	function fetch_goods_price($gid){
			if(!$this -> sm_db -> is_open())
                return null;
			$sql = "select currentprice
					from goods_info
					where `id` = '$gid'";
			$result = $this -> sm_db -> query($sql);
			$row = mysql_fetch_object($result);
			return $row -> currentprice;
		}
			
	function fetch_goods_sid($gid){
		if(!$this -> sm_db -> is_open())
            return null;
		$sql = "select sid
				from goods_info
				where `id` = '$gid'";
		$result = $this -> sm_db -> query($sql);
		$row = mysql_fetch_object($result);
		return $row -> sid;
	}
	
	function fetch_allgoods(){
		if(!$this -> sm_db -> is_open())
            return null;
		$sql = "select id
				from  goods_info;";
		$result = $this -> sm_db -> query($sql);
		$arr = array();
		while($row = mysql_fetch_object($result)){
			array_push($arr,$row -> id);
		}
		return $arr;
	}
	
	function fetch_sellergoods($sid,$begin_no,$number){
		if(!$this -> sm_db -> is_open())
            return null;
		$sql = "select id
				from  goods_info
				where `sid` = '$sid'
                order by `sid`
                limit {$begin_no},{$number}";
		$result = $this -> sm_db -> query($sql);
		$arr = array();
		while($row = mysql_fetch_object($result)){
			array_push($arr,$row -> id);
		}
		return $arr;
	}

    function seller_goods_count($sid){
        $sql = "select count(*) as count
                from  `goods_info`
                where `sid` = '$sid'";
                
        $result = $this -> sm_db -> query($sql);
        if(!$result){
            die("Wrong with the database connection!");
        }
        $row = mysql_fetch_array($result);

        return $row['count'];
    }
}
?>

