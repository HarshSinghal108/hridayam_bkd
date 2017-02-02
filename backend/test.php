<?php

print_r($_SERVER);
$split=explode("/",$_SERVER['HTTP_REFERER']);
// str_split(, 3);
if($split[2]=="baniyekidukaan.in"){
  echo "Access Granted!";
}
else {
  echo "Permission_Denied!";
}
 ?>
