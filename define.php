<?php
$user='tiancc';
$pass='tiancc2';
$starttime = microtime(true);

define('__ROOT__','/www/wwwroot');
require __ROOT__.'/vendor/autoload.php';
include_once(__ROOT__.'/phplib1.0/StrTool.php');

$hmlist=array(
        "hm1"=>array(
                "desc"=>"HomeServer1",
                "ip"=>"192.168.1.21",
                "mac"=>"",
                "status"=>0,
                "os"=>"ubuntu"
        ),
/*        "hm2"=>array(
            "desc"=>"HomeServer2",
            "ip"=>"192.168.1.22",
            "mac"=>"",
            "status"=>0
        ), */       
        "hm3"=>array(
            "desc"=>"HomeServer3",
            "ip"=>"192.168.1.23",
            "mac"=>"",
            "status"=>0,
            "os"=>"win10"
        ),
/*        "wk1"=>array(
            "desc"=>"Work电脑",
            "ip"=>"192.168.1.82",
            "mac"=>"",
            "status"=>0
        ),          
        "pi"=>array(
            "desc"=>"树莓派",
            "ip"=>"192.168.1.96",
            "mac"=>"",
            "status"=>0,
            "os"=>"pi"
        )     */          
);

function checklogin()
{
    global $user;
    global $pass;
    if(!isset($_COOKIE['sign'])||!isset($_COOKIE['tx']))
    {
        return false;
    }
    $sign=$_COOKIE['sign'];
    $tx=$_COOKIE['tx'];
    if(md5($user.'|'.$pass.'|'.$tx)!=$sign)
    {
        return false;
    }
    return true;
}





?>