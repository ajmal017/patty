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
    public $status              = 1;

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

    public function get($select = ' idx,name ') {

        $query	= "SELECT ";
        $query .=   $select." ";
		$query .= "FROM ";
        $query .=   "`company` ";
		$query .= "WHERE ";
        if ($this->idx != null){ $query .=	"`idx`=? AND "; }
        if ($this->name != null && $this->code != null && ($this->name == $this->code)){ $query .=	"  ( `name`=? || `code`=? ) AND "; }
		$query .=	"`status`=? ";

        $fmt = "";
        if ($this->idx != null){ $fmt .= "i"; }
        if ($this->name != null && $this->code != null && ($this->name == $this->code)){
            $fmt .= "s";
            $fmt .= "s";
        }
        $fmt .= "i";

        $params = array($fmt);
        if ($this->idx != null){ $params[] = &$this->idx; }
        if ($this->name != null && $this->code != null && ($this->name == $this->code)){
            $params[] = &$this->name;
            $params[] = &$this->code;
        }
        $params[] = &$this->status;

        return CompanyM::new($this->postman->returnDataObject($query, $params));
    }

    public function getDailyUpdateWaitingCount() {

        $query	= "SELECT ";
        $query .=   " count(*) as cnt ";
		$query .= "FROM ";
        $query .=   "`company` ";
		$query .= "WHERE ";
        $query .=	"`last_updated`!=? AND ";
        $query .=	"`need_history`=? AND ";
		$query .=	"`status`=? ";

        $last_updated = date('Y-m-d');
        $need_history = 1;

        return ($this->postman->returnDataObject($query, array("sii", &$last_updated, &$need_history, &$this->status)))->cnt;
    }

    public function getNeedHistoryCount() {

        $query	= "SELECT ";
        $query .=   " count(*) as cnt ";
		$query .= "FROM ";
        $query .=   "`company` ";
		$query .= "WHERE ";
        $query .=	"`need_history`=? AND ";
		$query .=	"`status`=? ";

        $need_history = 2;

        return ($this->postman->returnDataObject($query, array("ii", &$need_history, &$this->status)))->cnt;
    }
}
