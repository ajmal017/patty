<?php

Map::path('playlist', function() {

    $playlist_list = array_map(function($date) {
        return array(
            'date' => $date,
            'list' => PlaylistM::new()->setDate($date)->getList()
        );
    }, get_date_list(200));

    $data = array();
    $date['playlist_list'] = $playlist_list;

    $this->load->html('template/head');
    $this->load->html('page/playlist/index', $date);
    $this->load->html('template/foot');
});
