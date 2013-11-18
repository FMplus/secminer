<?php
    //session_start();
    include_once("config.php");
    include_once("user_info_class.php");
    include_once("upload_profile.php");
	
	function init_user($id){
		$dbn = new db();
		$dbn -> open(SM_HOST,SM_DB,SM_UID,SM_PSW);
		//create seller info
		$sql1 = "insert into buyer_info
				 (`id`,`credit`)values ('$id',0.0)";
		$dbn -> query($sql1);

		//create buyer info
		$sql2 = "insert into seller_info
				(`id`,`credit`)values ('$id',0.0)";
		$dbn -> query($sql2);
		//$dbn -> close();
	}

    $user = new user_info(    
                              $id           = 0,//no effect
                              $name         = $_POST['name'],
                              $nickname     = $_POST['nickname'],
                              $addr         = $_POST['addr'],
                              $gender       = $_POST['gender'],
                              $grade        = $_POST['grade'],
                              $school       = $_POST['school'],
                              $profile      = DEFAULT_PROFILE,
                              $phoneno      = $_POST['phoneno']
                            );

    $user_db = new user_info_db();
    $user_db -> open();
    if(!$user_db -> add_user($user,$_POST['password'])){
        echo "<a href = 'signup.html'>该用户名已存在，请重新输入</a>";
        //$user_db -> close();
        exit(0);
    }
    $user -> id = $user_db -> fetch_user_id($user -> name);
    if($_FILES['profile']["error"] == 0){//we use the file user uploaded just now
        if(upload_profile($_FILES['profile'],$user -> id )){
            $id = $user -> id;
            $user -> profile = PATH_PROFILE."$id" ;
            $user_db -> update_user_info($id,$user);
        }else// something bad happened
        {
            $user_db -> remove_user($user -> id);
            echo "<a href = 'signup.html'>头像文件错误，请重新输入</a>";
            $user_db -> close();
            exit(0);
        }
    }
	init_user($user -> id);
    //$user_db -> close();
    echo "<a href = 'signin.html'>注册成功，请登陆</a>";
    //header("signin.html");
?>
