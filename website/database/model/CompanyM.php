<?php

class CompanyM extends BusinessModel {

    // public variables
    public $idx                 = null;
    public $name                = null;
    public $code                = null;
    public $market              = null;
    public $need_history        = null;
    public $last_updated        = null;
    public $created_date_time   = null;
    public $status              = null;

    // help to create quick objects
    public static function new( $data = array() ) { return (new CompanyM())->extend($data); }

    //// ------------------------------ create setter & getters

    public function setIdx( $idx ) { $this->idx = $idx; return $this; }
    public function getIdx() { return $this->idx; }

    public function setName($name) { $this->name = $name; return $this; }
    public function getName() { return $this->name; }

    public function setCode($code) { $this->code = $code; return $this; }
    public function getCode() { return $this->code; }

    public function setMarket($market) { $this->market = $market; return $this; }
    public function getMarket() { return $this->market; }

    public function setNeedHistory($need_history) { $this->need_history = $need_history; return $this; }
    public function getNeedHistory() { return $this->need_history; }

    public function setLastUpdated($last_updated) { $this->last_updated = $last_updated; return $this; }
    public function getLastUpdated() { return $this->last_updated; }

    public function setCreatedDateTime( $created_date_time ) { $this->created_date_time = $created_date_time; return $this; }
    public function getCreatedDateTime($format = 'Y-m-d H:i:s') { $d = new DateTime($this->created_date_time); return $d->format($format); }

    public function setStatus($status) { $this->status = $status; return $this; }
    public function getStatus() { return $this->status; }

    //// ------------------------------ action function

    public function getDailyUpdateWaitingCount() {

        $query	= "SELECT ";
        $query .=   " count(*) as cnt ";
		$query .= "FROM ";
        $query .=   "`company` ";
		$query .= "WHERE ";
        $query .=	"`last_updated`!=? AND ";
		$query .=	"`status`=? ";

        $last_updated = date('Y-m-d');
        $status = 1;

        return ($this->postman->returnDataObject($query, array("si", &$last_updated, &$status)))->cnt;
    }
}
