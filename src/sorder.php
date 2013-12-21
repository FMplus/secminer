<?php 
	session_start();
	include ('goods_info_class.php');
	include_once ('user_info_class.php');
	include_once ('order_info_class.php');
	include_once("comment.php");
	include_once("op_order.php");
	include_once("reply.php");
	
	$constant = array("零","一","二","三","四","五","六","七","八","九","十","错误");
	$O_STATE_CNZH = 	array(	'initial'=>'等待接单',
								'waiting'=>'等待发货',
								'delivering'=>'配送中',
								'completed'=>'完成',
								'canceled'=>'取消'
								);
	$O_ACTION_CNZH = 	array(	'cancel'=>'取消订单',
								'ready'=>'确认接单',
								'send'=>'确认发货',
								'confrim'=>'确认收货'
								);		
	$order_id = $_GET['oid'];
	$order_db = new order_db;
	$order_db -> open();
	$o_info = $order_db -> fetch_order_info($order_id);
	$cid = $order_db -> fetch_commentid_asoid($order_id);
	
	$list    = null;
	$id = $_SESSION['id'];
	if($_SESSION['role'] == 'owner'){
		$list = get_seller_action_list($o_info -> state);
	}else if($_SESSION['role'] == 'buyer'){
		$list = get_buyer_action_list($o_info -> state);
	}else{
		die("Wrong!");
	}
	$order_db -> close();
	
	$reply_db = new reply_info_db;
	$reply_db -> open();
	if(isset($_GET['is_pushed'])){
			$reply = new reply_info($id = null,
						$time = null,
						$content = $_GET['content'],
						$cid     = $cid
						);
			$reply_db -> reply_comment_ascid($reply -> cid,$reply -> content);
			unset($_GET['is_pushed']);	
    }
	$reply_info = $reply_db -> fetch_reply_ascid($cid);
	$reply_db -> close();
	
	$cdb = new comment_info_db;
	$cdb -> open();
	$cm = $cdb -> fetch_comment_asoid($order_id);
	$cdb -> close();

?>

<html>

<script language = "javascript">
    function judgenull(a)
    {
        if (a.content.value== "")
        {
            alert("回复信息不能为空");
            a.content.focus();
            return(false);
        }
    }
</script>

<head>
<meta charset="UTF-8"/>
<link rel="stylesheet" type="text/css" href="css/sellerinfocenter.css" >
</head>

<body>
<div id = "showorder">
	<fieldset>
	<legend>订单信息</legend>
		<p>订单编号：<?= $o_info -> id ?></p>
		<p>商品编号：<a href = "goodsinfo.php?id=<?= $o_info -> gid?>" target = "_blank"><?= $o_info -> gid?></a></p>
		<p>买家编号：<a href = "neederinfocenter.php?nid=<?= $o_info -> bid?>" target = "_blank"><?= $o_info -> bid?></a></p>
		<p>数量：<?= $o_info -> number ?></p>
		<p>总价：<?= $o_info -> totalcost ?></p>
		<p>订单状态：<?= $O_STATE_CNZH[$o_info -> state] ?></p>
		<?php if(count($list)>0){?>
		<form action = "order_do.php?from='sorder.php?oid=<?= $order_id?>'" method = "post">
			<input type = "hidden" name = "oid" value = "<?= $order_id?>"/>
			<select name="action">
				<?php foreach($list as $option){?>
					<option value="<?=$option?>" ><?= $O_ACTION_CNZH[$option] ?></option>
				<?php }?>
			</select><br/><br/>
			<input type = "submit"/>
		</form>
		<?php null;}?>
	</fieldset>
</div>
<br/>

<div id = "pushwindow1"><br/>

	<?php if ($cm == null){echo("暂时没有评论！");}else{?>
		<h4>订单评论：</h4>
		<form>
			<fieldset>
				<legend>时间：<?= $cm -> time ?></legend>
				<h5><?= $cm -> content ?><br/></h5>
			</fieldset>
			<br/>
		</form>
		
	<?php if($reply_info == null){?>
		<form method = "get" action = "sorder.php">
		<div id = "needmsg2">
				<textarea type = "text" name = "content"  rows = "3" cols = "35%" style=" font-size:19px; color:#AAF" ></textarea>
		</div>
		<div id = "output2">
			<input type = "submit" name = "submit" value = "回复" style = "background:#add8e6;color:#ffffff;font-size:1em;font-family:Georgia,serif;font-weight:bold;width:100%;height:72;" onclick = "return judgenull(this.form);"/>
			<input type = "hidden" name = "is_pushed"/>
			<input type = "hidden" name = "oid" value = "<?= $order_id?>"/>
	<?php }else{?>
	<h4>回复：</h4>
			<fieldset>
				<legend>回复时间：<?= $reply_info -> time ?></legend>
				<h5><?= $reply_info -> content ?><br/></h5>
			</fieldset>
	<?php }?>
	
<?php }?>

</div>

</body>

</html>
