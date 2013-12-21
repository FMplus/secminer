<?php 
	session_start();
	$sid = $_SESSION['id'];
	header("Content-type:text/html;charset=UTF-8");
?>
<!--在这里获取卖家的信息(id)-->
<html>
<head>
<meta charset="UTF-8"/>
<link rel="stylesheet" type="text/css" href="css/infogoods.css" >
</head>
<script language = "JavaScript">
	function check_signup(theform)
	{
		if (theform.name.value == "")
		{
			alert("请填写商品名称!");
			theform.name.focus();
			return(false);
		}
		else if (theform.originalprice.value == "")
		{
			alert("请填写商品原价!");
			theform.originalprice.focus();
			return(false);
		}
		else if (theform.currentprice.value == "")
		{
			alert("请填写商品现价!");
			theform.currentprice.focus();
			return(false);
		}
		else if (theform.decrb.value == "")
		{
			alert("请填写商品描述!");
			theform.decrb.focus();
			return(false);
		}
		
		return(true);		
	}
</script>

<body>

<div id="infogoods" method="get">
<form >
  <fieldset>
    <legend>商品注册</legend>
	<br/>
	
	商品名称：<input type="text" name="name" /><br/><br/>
	
	商品展示：<!--img-->
	<input type="file" name="profile" value="上传图片" />
	<br/><br/>
	
	商品数量：
	<input type="text" name="quantity" value="1" size=4/><br/><br/>
	
	新旧程度：
	<select name="usingdgr"> 
	<option value="one" selected="selected">一成新</option>
	<option value="two" >二成新</option>
	<option value="three" >三成新</option>
	<option value="four" >四成新</option>
	<option value="five" >五成新</option>
	<option value="six" >六成新</option>
	<option value="seven" >七成新</option>
	<option value="eight" >八成新</option>
	<option value="nine" >九成新</option>
	<option value="ten" >十成新</option>
	</select><br/><br/>
	
	商品原价：<input type="text" name="originalprice" /><br/><br/>
	商品现价：<input type="text" name="currentprice" /><br/><br/>
	商品描述：<br/>
	<textarea type="text" name="decrb" rows=10 cols=40 ></textarea>
	<br/><br/>
	<input type="hidden" name="sid" value="<?= $sid ?>" ></input>
	</fieldset>
	
	<input type="submit" value="提交" />
</form>
</div>

</body>
</html>
