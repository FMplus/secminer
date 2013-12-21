<?php
    session_start();
    include_once("config.php");
    include_once("user_info_class.php");
    include_once("upload_profile.php");
    if(isset($_SESSION['id'])){
        $user_db = new user_info_db();
		$user_db -> open();
        $id     = $_SESSION['id'];
        $uinfo  = $user_db -> fetch_user_info($id);
        $uinfo -> nickname  = $_POST['nickname'];
        $uinfo -> addr      = $_POST['addr'];
        $uinfo -> gender    = $_POST['gender'];
        $uinfo -> grade     = $_POST['grade'];
        $uinfo -> school    = $_POST['school'];
        $uinfo -> phoneno   = $_POST['phoneno'];
        
        if($_FILES['profile']["error"] == 0){//we use the file user uploaded just now
            if(!upload_profile($_FILES['profile'],$id )){
                echo "<a href = 'signup.html'>头像文件错误，请重新输入</a>";
                $user_db -> close();
                exit(0);
            }else
			{
				$uinfo -> profile = PATH_PROFILE."$id";
			}
        }
		$user_db -> update_user_info($id,$uinfo);
        $user_db -> close();
    }
?>
