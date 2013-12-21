<?php
    include_once("config.php");
    include_once("db_class.php");
    include_once("goods_info_class.php");
    
    class order_info{
        public $time;
        public $id;
        public $bid;
		
        public $gid;
        public $price;
        public $number;
		
        public $state;
		public $totalcost;
		
        function __construct($time = 0,$id = 0,$bid = 0,$gid = 0,$price = 0,$state = 0,
							$number = 0,$totalcost = 0){
            $this -> time = $time;
            $this -> id   = $id;
            $this -> bid= $bid;
			
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
			
			$sql = "select price
					from order_goods
					where `oid` = '$order_id' and `gid` = '$gid'";
			$result = $this -> sm_db -> query($sql);
            if (!$result)
            {
               return NULL;
            }
			$row = mysql_fetch_object($result);
			$price = $row -> price;
			
			$sql = "select *
					from order_info
					where `id` = '$order_id'";
			$result = $this -> sm_db -> query($sql);
			$row = mysql_fetch_object($result);
			//$price = $row -> totalcost/$number;
			$order = new order_info($time = $row -> time,
									$id = $order_id,
									$bid = $row -> bid,
									$gid = $gid,
									$price = $price,
									$state = $row -> state,
									$number = $number,	
									$totalcost = $row -> totalcost);
			return $order;
        }

        function add_order($new_order){
            /*check goods storage*/
			$gid = $new_order -> gid;
			if(!$this -> sm_db -> is_open())
               return null;
			 
			$sql = "select quantity
					from  goods_info 
					where `id` = $gid";

			$result = $this -> sm_db -> query($sql);
			$row = mysql_fetch_row($result);
			$quantity = $row[0];
			if($quantity < ($new_order -> number))
			{	
				echo "Add order failed!".'<br />';
				echo "Stock < ".($new_order -> number).'<br />';
				return NULL;
			}
			
			/*If bid = sid, at this time the system should not allow to add order.
			First step : get sid as gid.
			Final step : compare sid , bid*/
			$gid = $new_order -> gid;
			$sql = "select `sid`
					from `goods_info`
					where `id` = '$gid'";
			$result = $this -> sm_db -> query($sql);
			$row = mysql_fetch_object($result);
			$sid = $row -> sid;
			if($sid == $new_order -> bid){
				echo "不允许自己给自己下订单!".'<br/>';
				return -1;
			}
            /*add order into order db*/
			$state = $new_order -> state;
			$bid = $new_order -> bid;
			$totalcost = $new_order -> totalcost;
			
			$sql = "insert into order_info(state,bid,totalcost)
					values('$state','$bid','$totalcost')";
			$result = $this -> sm_db -> query($sql);
            /*get order id*/
			$sql = "SELECT LAST_INSERT_ID()";
			$result = $this -> sm_db -> query($sql);
			$row = mysql_fetch_row($result);
			$id = $row[0];
			/*add info into  order_goods*/
			$quantity = $new_order -> number;
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

        function fetch_buyer_order_list($uid,$begin_no,$number){
            if(!$this -> sm_db -> is_open())
                return null;
			$sql = "select id
					from  order_info
					where `bid` = '$uid'
                    order by time desc
                    limit {$begin_no},{$number}";
			$result = $this -> sm_db ->query($sql);
			$idarr = array();
			while(($row = mysql_fetch_object($result)))
			{
				array_push($idarr,$row -> id);
			}
			return $idarr;
        }

        function fetch_seller_order_list($uid,$begin_no,$number){
            if(!$this -> sm_db -> is_open())
                return null;
			$sql = "select oid 
					from goods_info,order_goods
					where `goods_info`.`id` = `order_goods`.`gid` and `goods_info`.`sid` = '$uid'
                    order by oid desc
                    limit {$begin_no},{$number}";

			$result = $this -> sm_db -> query($sql);
			$idarr = array();
			while($row = mysql_fetch_object($result))
			{
				array_push($idarr,$row -> oid);
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
			$oldstate = $row -> state;
			if($oldstate == 'completed')
			{
				echo "The order's state is : ".$oldstate.'<br />';
				echo "The state can not be updated!".'<br />';
			}
				
			if($state == 'canceled'){
				$sql = "select `quantity`,`gid`
						from `order_goods`
						where `oid` = '$order_id'";
				$result = $this -> sm_db -> query($sql);
				$row = mysql_fetch_object($result);
				$quantity = $row -> quantity;
				$gid = $row -> gid;
				$sql = "update `goods_info`
						set `quantity` = `quantity` + '$quantity'
						where `id` = '$gid'";
				$result = $this -> sm_db -> query($sql);
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
			echo $sql;
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

        function buyer_order_count($uid){
            $sql = "select count(*) as count
                    from `order_info`
					where `bid` = {$uid}";
                    
            $result = $this -> sm_db -> query($sql);
            $row = mysql_fetch_array($result);
            return $row['count'];
        }

        function seller_order_count($uid){
            $sql = "select count(oid) as count
					from goods_info,order_goods
					where `goods_info`.`id` = `order_goods`.`gid` and `goods_info`.`sid` = '$uid'";

            $result = $this -> sm_db -> query($sql);
            $row = mysql_fetch_array($result);
            return $row['count'];
        }
        
		function fetch_sellerid_byoid($oid)
		{
			if(!$this -> sm_db -> is_open())
                return null;
			 $sql = "select `sid` 
					from goods_info,order_goods
					where `goods_info`.`id` = `order_goods`.`gid`
					and `order_goods`.`oid` = '$oid'";
			 $result = $this -> sm_db -> query($sql);
			 $row = mysql_fetch_object($result);
			 return $row -> sid;
		}

		function fetch_commentid_asoid($oid){
			 if(!$this -> sm_db -> is_open())
                return null;
			 $sql = "select `comment_info`.`id`  as `id`
					from order_info,comment_info
					where `order_info`.`id` = `comment_info`.`oid`
					and `order_info`.`id` = '$oid'";
			 $result = $this -> sm_db -> query($sql);
			 $row = mysql_fetch_object($result);
			 if($row != null){
				return $row -> id;
			 }
			 return null;
		}
    }
