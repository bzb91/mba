<?php
ob_start();
//session_start();
header('Access-Control-Allow-Origin: *');
include ("config/function.php");
$method=$_REQUEST['method'];
//print_r($method);
//$sessionkey=$request['token'];
/*function error_str($str){


    return '{"message":"'.$str.'"}';
}*/



/*function generateRandomString($length = 20) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}


$browser=$_SERVER['HTTP_USER_AGENT'];
$token=temizle($_REQUEST['token']);
if ($method!=='doLogin'){
    $qlogin=pg_query("select u.* from public.mobile_sessions s inner join ntforms.users u on u.user_id=s.user_id where s.sessionkey='".$token."' and s.browser='".$browser."'");
    if (pg_num_rows($qlogin)==0){;echo error_str("LOGIN_ERROR");die();}else{
        $rsuser=pg_fetch_array($qlogin);
        //  print_r($rsuser);
        $permission=$rsuser['permission'];
        $user_id=$rsuser['user_id'];
    }
}*/
//echo $user_id;

include "jsonservice/services.php";
