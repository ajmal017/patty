<?php

Map::path('calendar', function() {

    $rightnow_month = date('m');
    $rightnow_year  = date('y');

    $date_list  = Calendar::new()->generate($rightnow_month, $rightnow_year);
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

    list($pre_month, $pre_year)     = Calendar::new()->setup($rightnow_month, $rightnow_year)->getPrevDate();
    list($next_month, $next_year)   = Calendar::new()->setup($rightnow_month, $rightnow_year)->getNextDate();

    $data = array();
    $data['calender_list']  = $calender_list;
    $data['pre_month']      = $pre_month;
    $data['pre_year']       = $pre_year;
    $data['next_month']     = $next_month;
    $data['next_year']      = $next_year;
    $data['today_month']    = $rightnow_month;
    $data['today_year']     = $rightnow_year;

    $this->load->html('template/head', array('page' => 'calendar'));
    $this->load->html('page/calendar/index', $data);
    $this->load->html('template/foot');
});
