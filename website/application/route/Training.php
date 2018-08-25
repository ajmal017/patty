<?php

Map::path('training/history', function() {

    $data                       = array();
    $data['history_list']       = ModelTrainingHistoryM::new()->getList();

    $this->load->html('template/head', array('page' => 'training=>history'));
    $this->load->html('page/training/history', $data);
    $this->load->html('template/foot');
});
