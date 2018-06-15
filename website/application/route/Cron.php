<?php

Map::path('cron', function() {

    $data                                       = array();
    $data['company_daily_update_waiting_count'] = CompanyM::new()->getDailyUpdateWaitingCount();
    $data['company_history_count']              = CompanyM::new()->getNeedHistoryCount();


    $this->load->html('template/head', array('page' => 'cron'));
    $this->load->html('page/cron/index', $data);
    $this->load->html('template/foot');
});
