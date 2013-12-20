<?php
    include_once("db_class.php");
    include_once("config.php");

    function search_goods_id($keyword,$begin_no,$number){
        $sql = "select `id`
                from goods_info
                where `dscrb` like '%{$keyword}%'
                   or `name` like '%{$keyword}%'
                limit {$begin_no},{$number}";
        $sm_db = new db();
        $sm_db -> open(SM_HOST,SM_DB,SM_UID,SM_PSW);
        $result = $sm_db -> query($sql);
        $list = array();
        if(!$result){
            return $list;
        }
        
        while($row = mysql_fetch_array($result)){
                array_push($list,$row['id']);
        }
        $sm_db  -> close();
        return $list; 
    }

    function search_goods_count($keyword){
        $sql = "select count(*) as count
                from goods_info
                where `dscrb` like '%{$keyword}%'
                   or `name` like '%{$keyword}%'";
        $sm_db = new db();
        $sm_db -> open(SM_HOST,SM_DB,SM_UID,SM_PSW);
        $result = $sm_db -> query($sql);
        $list = array();
        if(!$result){
            return $list;
        }
        
        $row = mysql_fetch_array($result);
        $sm_db  -> close();
        return (integer)$row['count']; 
    }


?>
