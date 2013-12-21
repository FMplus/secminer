<?php
    include_once("config.php");
    function upload_profile($file,$newname){
       if ((($file["type"] == "image/gif")
            || ($file["type"] == "image/jpeg")
            || ($file["type"] == "image/pjpeg"))
            && ($file["size"] < 2*1024*1024))
          {
              if ($file["error"] > 0)
                {
                    return 0;
                }
              else
                {
                    move_uploaded_file($file["tmp_name"],PATH_PROFILE."$newname");
                    return 1;
                }
          }
        else
          {
            return 0;
          }
    }
