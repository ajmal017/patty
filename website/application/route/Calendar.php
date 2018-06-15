<?php

Map::path('calendar', function() {

    $date_list  = Calendar::new()->generate(date('m'), date('y'));
    $calender   = array();
    foreach($date_list as $date) {
        $s_date = new DateTime($date);
        $c_date = new DateTime(date('Y-m-d'));

        $top_list = PlaylistM::new()->setDate($date)->getList('`p`.`rank`', 'asc', 10, 0);

        array_push($calender, array(
            'date'          => $date,
            'current_month' => ($s_date->format('m') == $c_date->format('m'))?true:false,
            'top_list'      => $top_list
        ));
    }
    $calender_list = array(
        array_slice($calender,  0, 7),
        array_slice($calender,  7, 7),
        array_slice($calender, 14, 7),
        array_slice($calender, 21, 7),
        array_slice($calender, 28, 7),
    );

    $data = array();
    $data['calender_list'] = $calender_list;

    $this->load->html('template/head', array('page' => 'calendar'));
    $this->load->html('page/calendar/index', $data);
    $this->load->html('template/foot');
});
