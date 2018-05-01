<?php

    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once(dirname(__FILE__).'/database/autoload.php');
    require_once(dirname(__FILE__).'/helper/autoload.php');

    $data                                       = array();
    $data['company_daily_update_waiting_count'] = CompanyM::new()->getDailyUpdateWaitingCount();
    $data['matrix_not_processed_count']         = MatrixM::new()->getNotProcessedCount();
    $data['matrix_processed_count']             = MatrixM::new()->getProcessedCount();

    load_view('template/head');
    load_view('page/status', $data);
    load_view('template/foot');
