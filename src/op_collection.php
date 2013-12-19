<?php
    include_once("db_class.php");
    include_once("goods_info_class.php");
    
    /*
     * This function is kept for historical reason(We use it in collection.php for a trick).
     */
    function get_goods_info($goods_id){
        $goods_info_db = new goods_info_db;
        $goods_info_db -> open();
        $res = $goods_info_db -> fetch_goods_info_asid($goods_id);
        $goods_info_db -> close();
        return $res;
    }

    function count_collection($id){
        $sql = "select count(*) as count
                from `goods_focus`
                where {$id} = `bid`";
        
        $dbn = new db;
        $dbn -> open(SM_HOST,SM_DB,SM_UID,SM_PSW);
        $result = $dbn -> query($sql);
        $row = mysql_fetch_array($result);
        $res = $row['count'];
        $dbn -> close();
        return $res;
    }

    function get_collection($id,$begin_no,$number){
        $res = array();
        $dbn = new db;
        $dbn -> open(SM_HOST,SM_DB,SM_UID,SM_PSW);
        $sql = "select id 
                from `goods_focus` 
                where $id = `bid`
                limit {$begin_no},{$number}";
    
        $result = $dbn -> query($sql);
        while($row = mysql_fetch_array($result)){
            array_push($res,$row['id']);
        }
        $dbn -> close();
        return $res;
    }

    function add_collection($uid,$gid,$dbn){
        $sql = "insert into goods_focus
			(`id`,`bid`)value($gid,$uid)";
        return $dbn -> query($sql);
    }
    
    function cancel_collection($uid,$gid,$dbn){
        $sql = "delete
                from goods_focus
                where {$uid}=`bid` and {$gid}=`id`";
        return $dbn -> query($sql);
    }
    
    function is_collected($uid,$gid,$dbn){
        $sql = "select count(*) as Existed
                from goods_focus
                where {$uid}=`bid` and {$gid}=`id`";
        
        
        $result = $dbn -> query($sql);
        if(!$result){
            die("Wrong with the database connection!");
        }
        $row = mysql_fetch_array($result);
        return ($row['Existed'] > 0);
    }
