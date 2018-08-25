<?php

Map::path('cron', function() {

    $data                                       = array();
    $data['company_daily_update_waiting_count'] = CompanyM::new()->getDailyUpdateWaitingCount();
    $data['company_history_count']              = CompanyM::new()->getNeedHistoryCount();
    $data['company_total_count']                = CompanyM::new()->getTotalCompanyCount();
    $data['company_exclude_learn_count']        = CompanyM::new()->getTotalExcludeLearnCount();

    $playlist_svm_wait                          = PlaylistM::new()->setSvmProcessed(PlaylistProcess::WAIT)->getSimpleList('`p`.`rank`', 'asc', '-1', '-1', true);
    $playlist_svm_process                       = PlaylistM::new()->setSvmProcessed(PlaylistProcess::PROCESS)->getSimpleList('`p`.`rank`', 'asc', '-1', '-1', true);
    $playlist_svm_done                          = PlaylistM::new()->setSvmProcessed(PlaylistProcess::DONE)->getSimpleList('`p`.`rank`', 'asc', '-1', '-1', true);
    $data['playlist_svm']                       = [['프로세스', '처리개수'], ['대기', $playlist_svm_wait->cnt], ['처리중', $playlist_svm_process->cnt], ['완료', $playlist_svm_done->cnt]];

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

Map::path('cron/clear_ml', function() {
    PlaylistM::new()->clear();
    ModelResultM::new()->clear();
    $this->load->html('component/redirect', array('msg' => '초기화 되었습니다.', 'url' => '/cron/'));
});
