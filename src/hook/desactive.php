<?php
function AVCONNECT_desactive_plugin()
{
    $apis = CWWYA_get_option("apis");
    for ($i = 0; $i < count(AVCONNECT_LIST_CONNECT); $i++) {
        $connect = AVCONNECT_LIST_CONNECT[$i];
        $active = NULL;
        $key = null;
        foreach ($apis as $index => $e) {
            if ($e['name'] == "AVCONNECT_" . $connect) {
                $key = $index;
                // break;
            }
        }
        if ($key  !== null) {
            if ($apis[$key]['active'] != $active) {
                $apis[$key]['active'] = $active;
                CWWYA_alertDisconnect($apis[$key]);
            }
        }
    }
    CWWYA_set_option("apis", $apis);
}
