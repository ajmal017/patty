<?php

Map::path('cron', function() {

    $data                                       = array();
    $data['company_daily_update_waiting_count'] = CompanyM::new()->getDailyUpdateWaitingCount();
    $data['matrix_not_processed_count']         = MatrixM::new()->getNotProcessedCount();
    $data['matrix_processed_count']             = MatrixM::new()->getProcessedCount();
    $data['matrix_match_not_processed_count']   = MatrixMatchM::new()->getNotProcessedCount();
    $data['matrix_match_processed_count']       = MatrixMatchM::new()->getProcessedCount();

    $this->load->html('template/head');
    $this->load->html('page/cron/index', $data);
    $this->load->html('template/foot');
});
