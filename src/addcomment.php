<?php
session_start();
include_once("comment.php");
$comment = new comment_info($id = null,
							$time = null,
							$content = $_POST['content'],
							$oid     = $_POST['oid']);
//print_r($comment);
$cdb = new comment_info_db;
$cdb -> open();
$cdb -> add_comment_asoid($comment -> oid,$comment -> content);
$cdb -> close();
echo("评论成功");
?>
