<?php

Map::path('cron', function() {

    $data                                       = array();
    $data['company_daily_update_waiting_count'] = CompanyM::new()->getDailyUpdateWaitingCount();
    $data['company_history_count']              = CompanyM::new()->getNeedHistoryCount();


    $this->load->html('template/head', array('page' => 'cron'));
    $this->load->html('page/cron/index', $data);
    $this->load->html('template/foot');
});

Map::path('cron/download_slowquery', function() {
    $filepath = './../log/slowquery.log';
    header('Content-Type: text/plain; charset=UTF-8');
    header("Content-disposition: attachment; filename='slowquery.log'");
    readfile($filepath);
});
