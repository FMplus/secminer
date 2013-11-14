<?php
    include_once("config.php");
    include_once("db_class.php");
    include_once("goods_info_class.php");
    
    
    class order_info{
        public $time;
        public $id;
        public $bid;
		
        public $sid;
        public $gid;
        public $price;
		
        public $number;
        public $state;
		public $totalcost;
        
        function __construct($time,$id,$bid,$sid,$gid,$price,$state,$number,$totalcost){
            $this -> time = $time;
            $this -> id   = $id;
            $this -> bid= $bid;
			
            $this -> sid = $sid;
            $this -> gid = $gid;
            $this -> price = $price;
			
			$this -> number = $number;
            $this -> state = $state;
			$this -> totalcost = $totalcost;
        }
		
        function __destrcut(){
            
        }
    }
    
    class order_db{
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
		
        function fetch_order_info($order_id){
			if(!$this -> sm_db -> is_open())
                return null;
			$sql = "select quantity,gid
					from order_goods
					where `oid` = '$order_id'";
	
			$result = $this -> sm_db -> query($sql);
            if (!$result)
            {
               return NULL;
            }
			$row = mysql_fetch_object($result);
			$number = $row -> quantity;
			$gid = $row -> gid;
			
			$sql = "select *
					from order_info
					where `id` = '$order_id'";
			$result = $this -> sm_db -> query($sql);
            if (!$result)
            {
               return NULL;
            }
			$row = mysql_fetch_object($result);
			$price = $row -> totalcost/$number;
			$order = new order_info($row -> time,$order_id,$row -> bid,$row -> sid,$gid,$price,$number,$row -> state,$row -> totalcost);
			return $order;
        }

        function add_order($new_order){
            /*check goods storage*/
			$gid = $new_order -> gid;
			if(!$this -> sm_db -> is_open())
                return null;
			$sql = "select *
					from  goods_info
					where `id` = '$gid'";
			$result = $this -> sm_db -> query($sql);
			$row = mysql_fetch_object($result);
			$quantity = $row -> quantity;
			if($quantity < $new_order -> number)
			{	
				echo "Add order failed!".'<br />';
				echo "Stock < ".$new_order -> number.'<br />';
				return NULL;
			}
            /*add order into order db*/
			$state = $new_order -> state;
			$bid = $new_order -> bid;
			$sid = $new_order -> sid;
			$totalcost = $new_order -> totalcost;
			
			$sql = "insert into order_info(state,bid,sid,totalcost)
					values('$state','$bid','$sid','$totalcost')";
			$result = $this -> sm_db -> query($sql);
            /*get order id*/
			$sql = "select max(id) as maxid
					from order_info";
			$result = $this -> sm_db -> query($sql);
			$row = mysql_fetch_object($result);
			$id = $row -> maxid;
			/*add info into  order_goods*/
			$quantity = $new_order -> number;
			$gid = $new_order -> gid;
			$price = $new_order -> price;
			$sql = "insert into order_goods(gid,oid,quantity,price)
					values('$gid','$id','$quantity','$price')";
			$result = $this -> sm_db -> query($sql);
            /*change storage*/
			$sql = "update goods_info
					set `quantity` = `quantity` - '$quantity' 
					where `id` = '$gid'";
			$result = $this -> sm_db -> query($sql);
            /*return*/
			return $id;
        }

        function fetch_buyer_order_list($uid,$limit){
            if(!$this -> sm_db -> is_open())
                return null;
			$sql = "select id
					from  order_info
					where `bid` = '$uid'";
			$result = $this -> sm_db ->query($sql);
			$idarr = array();
			$int = 0;
			while(($row = mysql_fetch_object($result))&&($int < $limit))
			{
				$int++;
				array_push($idarr,$row -> id);
			}
			return $idarr;
        }

        function fetch_seller_order_list($uid,$limit){
            if(!$this -> sm_db -> is_open())
                return null;
			$sql = "select id
					from  order_info
					where `bid` = '$uid'";
			$result = $this -> sm_db ->query($sql);
			$idarr = array();
			$int = 0;
			while(($row = mysql_fetch_object($result))&&($int < $limit))
			{
				$int++;
				array_push($idarr,$row -> id);
			}
			return $idarr;
        }

        function update_order_state($order_id,$state){
			if(!$this -> sm_db -> is_open())
                return null;
			$sql = "select state
					from order_info
					where `id` = '$order_id'";
			$result = $this -> sm_db -> query($sql);
			$row = mysql_fetch_object($result);
			$state = $row -> state;
			if($state == 'completed')
			{
				echo "The order's state is : ".$state.'<br />';
				echo "The state can not be updated!".'<br />';
			}
			
			$sql = "update order_info
					set `state` = '$state' 
					where `id` = '$order_id'";
			$result = $this -> sm_db -> query($sql);
			return $result;
        }

        function update_order_info($order){
            //just update order info such as the order state or others identified by $order->id
        }

        function cancel_order($order_id){
          	if(!$this -> sm_db -> is_open())
                return null;
			$sql = "select state
					from order_info
					where `id` = '$order_id'";
			$result = $this -> sm_db -> query($sql);
			$row = mysql_fetch_object($result);
			$state = $row -> state;
			if(($state == 'canceled')||($state == 'delivering')||($state == 'completed'))
			{
				echo "The order's state is : ".$state.'<br />';
				echo "Can not cancel the order!".'<br />';
				return 0;
			}
			else
			{
				$this -> update_order_state($order_id,"canceled");
				return 1;
			}
        }
    }