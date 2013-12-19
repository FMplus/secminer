<?php
    include_once('config.php');
    include_once('db_class.php');

    function get_seller_action_list($state){
        $SELLER_ACTION_MODEL                 =    array();
        $SELLER_ACTION_MODEL['initial']      =    array('cancel','ready','send');
        $SELLER_ACTION_MODEL['waiting']      =    array('cancel','send');
        $SELLER_ACTION_MODEL['delivering']   =    array();
        $SELLER_ACTION_MODEL['completed']    =    array();
        $SELLER_ACTION_MODEL['canceled']     =    array();

        return $SELLER_ACTION_MODEL[$state];
    }

    function get_buyer_action_list($state){
        $BUYER_ACTION_MODEL                 =    array();
        $BUYER_ACTION_MODEL['initial']      =    array('cancel');
        $BUYER_ACTION_MODEL['waiting']      =    array('cancel');
        $BUYER_ACTION_MODEL['delivering']   =    array('confirm');
        $BUYER_ACTION_MODEL['completed']    =    array();
        $BUYER_ACTION_MODEL['canceled']     =    array();

        return $BUYER_ACTION_MODEL[$state];
    }
    
    function do_order_action($oid,$action,$role){
        $SELLER_ACTION_MODEL                 =    array();
        $SELLER_ACTION_MODEL['initial']      =    array('cancel'=>'canceled','ready'=>'waiting','send'=>'delivering');
        $SELLER_ACTION_MODEL['waiting']      =    array('cancel'=>'canceled','send'=>'delivering');
        $SELLER_ACTION_MODEL['delivering']   =    array();
        $SELLER_ACTION_MODEL['completed']    =    array();
        $SELLER_ACTION_MODEL['canceled']     =    array();
        $BUYER_ACTION_MODEL                  =    array();
        $BUYER_ACTION_MODEL['initial']       =    array('cancel'=>'canceled');
        $BUYER_ACTION_MODEL['waiting']       =    array('cancel'=>'canceled');
        $BUYER_ACTION_MODEL['delivering']    =    array('confirm'=>'completed');
        $BUYER_ACTION_MODEL['completed']     =    array();
        $BUYER_ACTION_MODEL['canceled']      =    array();

        $ACTION_MODEL = array('owner' => $SELLER_ACTION_MODEL,
                              'buyer' => $BUYER_ACTION_MODEL);
        
        $order_db = new order_db;
        $order_db -> open();
        $order_info = $order_db -> fetch_order_info($oid);
        $old_state = $order_info -> state;
        $flag = false;
        if(isset($ACTION_MODEL[$role][$old_state][$action])){
            $order_db -> update_order_state($oid,$ACTION_MODEL[$role][$old_state][$action]);
            $flag = true;
        }else{
            $flag = false;
        }
        $order_db -> close();
        return $flag;
    }
    
    function check_role($uid,$role,$oid){
        $dbn = new db;
        $dbn -> open(SM_HOST,SM_DB,SM_UID,SM_PSW);
        $sql_seller =   "select count(*) as count
                         from  `goods_info`,`order_goods`
                         where  `goods_info`.`id` = `order_goods`.`gid` and `goods_info`.`sid` = {$uid} and `order_goods`.`oid` = {$oid}";

        $sql_buyer =    "select count(*) as count
                         from  `order_info`
                         where  `bid` = {$uid} and `id` = {$oid}";

        $res = false;
        if($role == 'buyer'){
            $result = $dbn -> query($sql_buyer);
           // print_r($sql_buyer);
            $row = mysql_fetch_array($result);
            $res = ($row['count'] > 0);
        }else if($role == 'owner'){
            $result = $dbn -> query($sql_seller);
            $row = mysql_fetch_array($result);
            $res = ($row['count'] > 0);
        }else{
            $res = false;
        }
        $dbn -> close();
        return $res;
    }
