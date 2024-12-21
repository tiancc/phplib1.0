<?php
function nocache()
{
    $tx=time().rand(100000,999999);
    setcookie("tx", $tx, 100, "/");
    return $tx;
}
?>