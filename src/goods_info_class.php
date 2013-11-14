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
	
	public $photo ;
	
	function __construct($id,$name,$usingdgr,
						 $originalprice,$currentprice,
						 $dscrb,$quantity,$state,$sid)
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
                               sid  => $this -> sid]"; 
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
	
	function search_gname($name){
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
		
	function search_gid($id){
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
	
	function remove_goods($id){
            if(!$this -> sm_db -> is_open())
                return null;
            if(!$this -> search_gid($id)){
				return NULL;
			}
			
			$sql = "delete from goods_photo 
                    where '$id' = `id`";	
			$this -> sm_db -> query($sql);
			
			$sql = "delete from goods_tag 
                    where '$id' = `id`";	
			$this -> sm_db -> query($sql);
			
			$sql = "delete from goods_sort
                    where '$id' = `id`";	
			$this -> sm_db -> query($sql);
			
			$sql = "delete from goods_focus 
                    where '$id' = `id`";	
			$this -> sm_db -> query($sql);
			
            $sql = "delete from goods_info 
                    where '$id' = `id`";	
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
			return $goods;
        }
	
	function fetch_goods_info_asid($id){
            if(!$this -> sm_db -> is_open())
                return null;
            if(!$this -> search_gid($id)){
                return null;
            }
            $sql   = "select *
                      from  goods_info
                      where '$id' = `id`";
            
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
			return $goods;
        }
		
	function update_goods_quantity($id,$int){
            if(!$this -> sm_db -> is_open())
                return null;
            $sql = "update goods_info 
					set `quantity` = '$int'  
					where `id` = '$id'";
            $result = $this -> sm_db -> query($sql);
            return $result;
    }
	
	function update_goods_dscrb($id,$dscrb){
            if(!$this -> sm_db -> is_open())
                return null;
            $sql = "update goods_info 
					set `dscrb` = '$dscrb'
					where '$id' = `id`";
			echo $sql;
            $result = $this -> sm_db -> query($sql);
            return $result;
    }
	
	function update_goods_currentprice($id,$price){
            if(!$this -> sm_db -> is_open())
                return null;
            $sql = "update goods_info 
					set `currentprice` = '$price'  
					where `id` = '$id'";
            $result = $this -> sm_db -> query($sql);
            return $result;
    }
	
	function update_goods_originalprice($id,$price){
            if(!$this -> sm_db -> is_open())
                return null;
            $sql = "update goods_info 
					set `originalprice` = '$price'  
					where `id` = '$id'";
            $result = $this -> sm_db -> query($sql);
            return $result;
    }
	
	function update_goods_state($id){
            if(!$this -> sm_db -> is_open())
                return null;
            $sql = "update goods_info 
					set `state` = 'sellout'  
					where `id` = '$id' and `quantity` = '0'";
            $result = $this -> sm_db -> query($sql);
            return $result;
    }
}

?>

