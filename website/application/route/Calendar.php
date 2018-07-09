<?php

Map::path('calendar', function() {

    $rightnow_month = (isset($_GET['month'])) ? $_GET['month'] : date('m');
    $rightnow_year  = (isset($_GET['year'])) ? $_GET['year'] : date('y');

    $date_list  = Calendar::new()->generate($rightnow_month, $rightnow_year);
    $calender   = array();
    $group_list = PlaylistGroupM::new()->getList();
    foreach($date_list as $date) {
        $s_date         = new DateTime($date);
        $c_date         = new DateTime(date('Y-m-d'));
        $day_group_list = array();

        foreach($group_list as $group) {
            $playlist_list = PlaylistM::new()->setType(PlaylistType::CUSTOM)->setDate($date)->setGroupIdx($group->idx)->getList('`p`.`rank`', 'asc', 40, 0);
            foreach($playlist_list as $playlist) {
                $playlist->top_list = ModelResultM::new()->setPlaylistIdx($playlist->idx)->setTrainCompanyIdx($playlist->company_idx)->getList( 'score', 'desc', 15, 0 );
                foreach($playlist->top_list as $top) {
                    $top->company = CompanyM::new()->setIdx($top->getTestCompanyIdx())->get();
                }
            }
            array_push($day_group_list, array(
                'idx'   => $group->idx,
                'name'  => $group->name,
                'list'  => $playlist_list
            ));
        }

        array_push($calender, array(
            'date'          => $date,
            'current_month' => ($s_date->format('m') == $rightnow_month)?true:false,
            'custom_list'   => $day_group_list
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

    // $this->load->html('template/head', array('page' => 'calendar'));
    // $this->load->html('page/calendar/index', $data);
    // $this->load->html('template/foot');
});
