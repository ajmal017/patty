<?php

Map::path('playlist/group/manage', function() {

    $data = array();
    $date['playlist_group_list'] = PlaylistGroupM::new()->getList();

    $this->load->html('template/head', array('page' => 'playlist=>group=>manage'));
    $this->load->html('page/playlist_group/manage', $date);
    $this->load->html('template/foot');
});

Map::path('playlist/group/view/{integer}', function($idx) {

    $data = array();
    $date['playlist_group'] = PlaylistGroupM::new()->setIdx($idx)->get();
    $date['playlist_list'] = PlaylistM::new()->setGroupIdx($idx)->getList('`p`.`rank`', 'asc', 40, 0);

    $this->load->html('template/head', array('page' => 'playlist=>group=>view=>'.$idx));
    $this->load->html('page/playlist_group/view', $date);
    $this->load->html('template/foot');
});

Map::path('playlist/group/edit/{integer}', function($idx) {

    $data = array();
    $date['playlist_group'] = PlaylistGroupM::new()->setIdx($idx)->get();

    $this->load->html('template/head', array('page' => 'playlist=>group=>view=>'.$idx));
    $this->load->html('page/playlist_group/edit', $date);
    $this->load->html('template/foot');
});

Map::path('playlist/group/create/', function() {
    $this->load->html('template/head', array('page' => 'playlist=>group=>manage'));
    $this->load->html('page/playlist_group/edit', array('playlist_group' => array()));
    $this->load->html('template/foot');
});

Map::path('POST', 'playlist/group/save/', function() {

    $group = PlaylistGroupM::new()
                ->setIdx($_POST['idx'])
                ->setSortIdx($_POST['sort_idx'])
                ->setName($_POST['name']);

    if ($group->getIdx() == "") {
        $group->create();
    } else {
        $group->update();
    }

    $this->load->html('component/redirect', array('msg' => '저장 되었습니다.', 'url' => '/playlist/group/manage/'));
});

Map::path('playlist/group/delete/{integer}', function($idx) {
    PlaylistGroupM::new()->setIdx($idx)->remove();
    $this->load->html('component/redirect', array('msg' => '삭제 되었습니다', 'url' => '/playlist/group/manage/'));
});
