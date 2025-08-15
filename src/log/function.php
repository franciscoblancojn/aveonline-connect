<?php

function AVCONNECT_getLog()
{
    $log = get_option(AVCONNECT_KEY."log");
    if($log === false || $log == null || $log == ""){
        $log = "[]";
    }
    $log = json_decode($log,true);
    return $log ;
}
function AVCONNECT_addLog(...$newLog)
{
    $log = AVCONNECT_getLog();
    $log[] = $newLog;
    $log = array_slice($log, -1 * AVCONNECT_N_MAX_LOG,AVCONNECT_N_MAX_LOG); 
    update_option(AVCONNECT_KEY."log",json_encode($log));
}