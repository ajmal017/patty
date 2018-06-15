<?php

Map::path('company/view/{integer}', function($idx) {

    $search = (isset($_GET['search'])) ? $_GET['search'] : null;
    // $idx = (isset($_GET['idx'])) ? $_GET['idx'] : null;
    // $idx = ($idx == null && $search == null) ? "-1" : $idx;

    $company = CompanyM::new()->setIdx($idx)->setName($search)->setCode($search)->get();

    $data                   = array();
    $data['company']        = $company;
    $data['stock_list']     = ($company->getIdx()!=null)?CompanyStockM::new()->setCompanyIdx($company->getIdx())->getList():array();
    $data['detail']         = $data['stock_list'][count($data['stock_list'])-1];
    $data['ohlc_list']      = CompanyStockM::convertToCandleStick($data['stock_list']);
    $data['top_playlist']   = ($company->getIdx()!=null)?PlaylistM::new()->setCompanyIdx($company->getIdx())->setType(PlaylistType::TOP)->getList():array();

    $this->load->html('template/head');
    $this->load->html('page/company/view', $data);
    $this->load->html('template/foot');
});

Map::path('company/search', function() {
    $this->load->html('template/head');
    $this->load->html('page/company/search');
    $this->load->html('template/foot');
});

Map::path('POST', 'company/search', function() {

    $search = (isset($_POST['search'])) ? $_POST['search'] : null;
    $company_list = CompanyM::new()->setSearchName($search)->getList();

    $data = array();
    $data['search'] = $search;
    $data['company_list'] = $company_list;

    $this->load->html('template/head');
    $this->load->html('page/company/search', $data);
    $this->load->html('template/foot');
});

Map::path('company/comparesvm/{integer}/{integer}', function($playlist_idx,$company_idx) {

    $company            = CompanyM::new()->setIdx($company_idx)->get();
    $model_result_list  = ModelResultM::new()->setPlaylistIdx($playlist_idx)->setTrainCompanyIdx($company_idx)->getList();

    $graph_list = array();
    $target_company_list = array();
    foreach($model_result_list as $model_result) {
        $stock_list = CompanyStockM::new()->setCompanyIdx($model_result->getTestCompanyIdx())->getList();
        $ohlc_list = CompanyStockM::convertToCandleStick($stock_list);
        array_push($graph_list, $ohlc_list);
        array_push($target_company_list, array(
            'company'   => CompanyM::new()->setIdx($model_result->getTestCompanyIdx())->get(),
            'detail'    => $stock_list[count($stock_list) - 1],
            'model'     => $model_result,
            'stock_list'=> $stock_list,
            'ohlc_list' => CompanyStockM::convertToCandleStick($stock_list)
        ));
    }

    $data = array();
    $data['company']            = $company;
    $data['stock_list']         = CompanyStockM::new()->setCompanyIdx($company->getIdx())->getList();
    $data['ohlc_list']          = CompanyStockM::convertToCandleStick($data['stock_list']);
    $data['model_result_list']  = $model_result_list;
    $data['target_company_list']= $target_company_list;

    $this->load->html('template/head');
    $this->load->html('page/company/comparesvm', $data);
    $this->load->html('template/foot');
});
