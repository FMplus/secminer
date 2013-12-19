<?php
	session_start();
	include_once("search_need.php");
	header("Content-type:text/html;charset=UTF-8");
	$o_kw   =   '';
	if($o_kw   ==  ''){
		$sdb = new  need_db();
		$sdb -> open();
		$need = $sdb -> search_need($o_kw,0,0);
		$sdb -> close();}
	if(isset($_GET['keyword'])){
        $o_kw   =   $_GET['keyword'];
		if($_GET['keyword'] != "")
		{
			$keyword = urldecode($_GET['keyword']);
			$sdb = new  need_db();
			$sdb -> open();
			$need = $sdb -> search_need($keyword,0,50);
			$sdb -> close();
		}
	}
?>
<html>
<head>
<meta charset="UTF-8"/>
<link rel="stylesheet" type="text/css" href="css/sellerinfocenter.css" >  
</head>

    <form method = "get" action = "needsearch.php">
        <div id = "pushwindow">
            <div id = "needmsg">
				<input type = "text" name = "keyword" value = "<?= $o_kw?>" style=" font-size:19px; color:#AAF" size = "55px" />
            </div>
            <div id = "output">
				<input type = "submit" value = "search" style = "background:#add8e6;color:#ffffff;font-size:1em;font-family:Georgia,serif;font-weight:bold;width:100;height:32;"/>
            </div>
        </div>
    </form>

 <?php foreach($need as $need){ ?>
		<div id = "profile" >
			<a href = "neederinfocenter.php?nid=<?= $need -> bid ?>" target="_blank"><img type = "img" src="<?= $need -> profile ?>" height = '100px' width = '100px' /></a>
		</div>
		<div id = "message" >
			<fieldset>
				<legend >&nbsp <a href = "neederinfocenter.php?nid=<?= $need -> bid ?>"  target="_blank"><?= $need -> nickname?></a> &nbsp/&nbsp<?= $need -> time ?>&nbsp </legend>		
				<h5 class = "message"><?= $need -> content ?></h5>
			</fieldset>
			<br/><br/>
		</div>
		
    <?php }?>
</html>
