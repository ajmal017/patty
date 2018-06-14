<?php

function get_date_list($dates = 15) {
    $list = array();
    for($i = 0; $i < $dates; $i++) {
        array_push($list, date('Y-m-d',strtotime("-$i days")));
    }
    return $list;
}
