<?php

class WatchType {
    const DAILY   = 1;
    const ONCE    = 2;
}

class WatchM extends BusinessModel {

    // public variables
    public $idx                 = null;
    public $group_idx           = null;
    public $company_idx         = null;
    public $company_stock_idx   = null;
    public $type                = null;
    public $processed_date      = null;
    public $created_date_time   = null;
    public $status              = 1;

    public $group_name          = null;
    public $company_name        = null;
    public $price               = null;
    public $prev_diff           = null;
    public $percentage          = null;
    public $open                = null;
    public $high                = null;
    public $low                 = null;
    public $volume              = null;

    // help to create quick objects
    public static function new( $data = array() ) { return (new WatchM())->extend($data); }

    //// ------------------------------ create setter & getters

    public function setIdx( $idx ) { $this->idx = $idx; return $this; }
    public function getIdx() { return $this->idx; }

    public function setGroupIdx( $group_idx ) { $this->group_idx = $group_idx; return $this; }
    public function getGroupIdx() { return $this->group_idx; }

    public function setCompanyIdx($company_idx) { $this->company_idx = $company_idx; return $this; }
    public function getCompanyIdx() { return $this->company_idx; }

    public function setCompanyStockIdx($company_stock_idx) { $this->company_stock_idx = $company_stock_idx; return $this; }
    public function getCompanyStockIdx() { return $this->company_stock_idx; }

    public function setType($type) { $this->type = $type; return $this; }
    public function getType() { return $this->type; }

    public function setProcessedDate($processed_date) { $this->processed_date = $processed_date; return $this; }
    public function getProcessedDate() { return $this->processed_date; }

    public function setCreatedDateTime( $created_date_time ) { $this->created_date_time = $created_date_time; return $this; }
    public function getCreatedDateTime($format = 'Y-m-d H:i:s') { $d = new DateTime($this->created_date_time); return $d->format($format); }

    public function setStatus($status) { $this->status = $status; return $this; }
    public function getStatus() { return $this->status; }

    /* ---------------------- */

    public function setGroupName($group_name) { $this->group_name = $group_name; return $this; }
    public function getGroupName() { return $this->group_name; }

    public function setCompanyName($company_name) { $this->company_name = $company_name; return $this; }
    public function getCompanyName() { return $this->company_name; }

    public function setPrice($price) { $this->price = $price; return $this; }
    public function getPrice() { return $this->price; }

    public function setPrevDiff($prev_diff) { $this->prev_diff = $prev_diff; return $this; }
    public function getPrevDiff() { return $this->prev_diff; }

    public function setPercentage($percentage) { $this->percentage = $percentage; return $this; }
    public function getPercentage() { return $this->percentage; }

    public function setOpen($open) { $this->open = $open; return $this; }
    public function getOpen() { return $this->open; }

    public function setHigh($high) { $this->high = $high; return $this; }
    public function getHigh() { return $this->high; }

    public function setLow($low) { $this->low = $low; return $this; }
    public function getLow() { return $this->low; }

    public function setVolume($volume) { $this->volume = $volume; return $this; }
    public function getVolume() { return $this->volume; }

    //// ------------------------------ action function

    public function create() {

        $this->processed_date = '0000-00-00';

        $field  = array( 'group_idx', 'company_idx', 'company_stock_idx', 'type', 'processed_date' );
        $data   = array( $this->group_idx, $this->company_idx, $this->company_stock_idx, $this->type, $this->processed_date );
        $fmt    = 'iiiis';
        return $this->create_omr('watch', $field, $data, $fmt);
    }

    public function getGroupList() {

        $sortBy         = '`pg`.`name`';
        $sortDirection  = 'asc';

        $query	= "SELECT ";
        $query .=   "`w`.`idx`, `pg`.`name` as group_name,`w`.`created_date_time` ";
		$query .= "FROM ";
        $query .=   "`watch` as `w`, ";
        $query .=   "`playlist_group` as `pg` ";
		$query .= "WHERE ";
        $query .=  "`w`.`group_idx`=`pg`.`idx` AND ";
        $query .=  "`w`.`company_idx`=? AND ";
        $query .=  "`w`.`group_idx`!=? AND ";
		$query .=  "`w`.`status`=? ";
		$query .=	"ORDER BY $sortBy $sortDirection ";

        $not_group_idx = 0;

		$params = array("iii");
        $params[] = &$this->company_idx;
		$params[] = &$not_group_idx;
        $params[] = &$this->status;

        return array_map(function($item) {
            return WatchM::new($item);
        }, $this->postman->returnDataList( $query, $params ));
    }

    public function getList( $sortBy = '`w`.`idx`', $sortDirection = 'asc', $limit = '-1', $offset = '-1', $total_count = false ) {

        $query	= "SELECT ";
        $query .=   ($total_count)?"count(*) as cnt ":"`w`.`idx`,`w`.`group_idx`,`w`.`type`,`w`.`processed_date`,`w`.`company_idx`,`c`.`name` as company_name,`cs`.`price`,`cs`.`prev_diff`,`cs`.`percentage`,`cs`.`open`,`cs`.`high`,`cs`.`low`,`cs`.`volume` ";
		$query .= "FROM ";
        $query .=   "`watch` as `w`, ";
        $query .=   "`company` as `c`, ";
        $query .=   "`company_stock` as `cs` ";
		$query .= "WHERE ";
        $query .=  "`w`.`company_idx`=`c`.`idx` AND ";
        $query .=  "`w`.`company_stock_idx`=`cs`.`idx` AND ";
        if ($this->group_idx!=null) { $query .= "`w`.`group_idx`=? AND "; }
        if ($this->company_idx!=null) { $query .= "`w`.`company_idx`=? AND "; }
		$query .=	"`w`.`status`=? ";
		$query .=	"ORDER BY $sortBy $sortDirection ";
        if (!$total_count) { $query .= (($limit=='-1')&&($offset=='-1'))?'':"limit ? offset ? "; }

		$fmt = "";
        if ($this->group_idx!=null) { $fmt .= "i"; }
        if ($this->company_idx!=null) { $fmt .= "i"; }
        $fmt .= "i";
        if (!$total_count) { $fmt .= (($limit=='-1')&&($offset=='-1'))?'':"ii";  }

		$params = array($fmt);
        if ($this->group_idx!=null) { $params[] = &$this->group_idx; }
        if ($this->company_idx!=null) { $params[] = &$this->company_idx; }
		$params[] = &$this->status;

		if ( $total_count ) {
            return $this->postman->returnDataObject( $query, $params );
        } else {
            if (($limit!='-1')&&($offset!='-1')) {
                $params[] = &$limit;
                $params[] = &$offset;
            }
            return array_map(function($item) {
                return WatchM::new($item);
            }, $this->postman->returnDataList( $query, $params ));
		}
    }
}
