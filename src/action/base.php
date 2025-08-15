<?php
function AVCONNECT_action($type, $data)  {
    AVCONNECT_addLog([
        "test"=>1,
        $type, 
        $data
    ]);
}