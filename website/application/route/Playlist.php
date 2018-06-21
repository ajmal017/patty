<?php

Map::path('playlist', function() {

    $date = (isset($_GET['date'])) ? $_GET['date'] : date('Y-m-d');
    $d = new DateTime($date);
    $d->modify("-1 day");
    $yesterday = $d->format("Y-m-d");
    $d->modify("+2 day");
    $tomrrow = $d->format("Y-m-d");

    $playlist_list = PlaylistM::new()->setDate($date)->getList();

    $data = array();
    $data['date']           = $date;
    $data['yesterday']      = $yesterday;
    $data['tomorrow']       = $tomrrow;
    $data['playlist_list']  = $playlist_list;

    $this->load->html('template/head', array('page' => 'playlist'));
    $this->load->html('page/playlist/index', $data);
    $this->load->html('template/foot');
});
