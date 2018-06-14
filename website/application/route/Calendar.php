<?php

Map::path('calendar', function() {

    $data = array();

    $this->load->html('template/head');
    $this->load->html('page/calendar/index', $data);
    $this->load->html('template/foot');
});
