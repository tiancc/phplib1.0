<?php
$memkey = 1; // 共享内存的key，注：类型为int
$mem=getmem();
function getmem()
{
    global $memkey;
    try
    {    
        $memsize = 1024*1024*200; // 共享内存的大小，单位byte
        $perm = 0666; // 共享内存访问权限，参考linux的权限   
        $shmid = shm_attach($memkey,$memsize, $perm);
        $mem= shm_get_var( $shmid, 1);
        if(is_array($mem))
        {
            return $mem;
        }
        else{
            return array();
        }
    }
    catch(Exception $err)
    {
        return array();
    }
}

function setmem()
{
    global $memkey;
    global $mem; 
    $shmid = shm_attach($memkey);
    shm_put_var( $shmid, 1, $mem ); 
    //插入一个共享内存变量，key为$var_key，值为"abc"   
    //shm_detach( $shmid ); 
}
?>