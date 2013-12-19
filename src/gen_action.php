<?php
    session_start();
    include_once("op_order.php");
    include_once("order_info_class.php");
    
    
    $order_db = new order_db;
    $order_db -> open();
    
    $list    = null;
    if(isset($_POST['is_push'])){
       $id = $_SESSION['id'];
       $oid = $_POST['oid'];
       $order_info = $order_db -> fetch_order_info($oid);
       if($_SESSION['role'] == 'owner'){
            $list = get_seller_action_list($order_info -> state);
       }else if($_SESSION['role'] == 'buyer'){
            $list = get_buyer_action_list($order_info -> state);
       }else{
            die("Wrong!");
       }
    }

    $order_db -> close();
?>


<form action = "gen_action.php" method = "post">
    <input type = "text" name = "oid"/>
    <input type = "hidden" name = "is_push"/>
    <input type = "submit"/>
</form>
<?php if(count($list)>0){?>
<form action = "order_do.php?from='sorder.php'" method = "post">
    <input type = "text" name = "oid"/>
    <select name="action">
        <?php foreach($list as $option){?>
            <option value="<?=$option?>" ><?=$option?></option>
        <?php }?>
	</select><br/><br/>
    <input type = "submit"/>
</form>
<?php null;}?>
