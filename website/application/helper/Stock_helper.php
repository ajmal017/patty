<?php

function thinning_stock($stock_list) {
    $r_list = array_reverse($stock_list);
    $s = "";
    $cnt = 0;
    foreach($r_list as $item) {
        if ($cnt++ > 100){
            break;
        }
        if ($s != "") { $s = ", " . $s;}
        $s = $item->getPrice() . '' . $s;
    }
    return $s;
}
