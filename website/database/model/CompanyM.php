<?php

class CategoryM extends BusinessModel {

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

    //// ------------------------------ action function

    public function getList() {

        $query	= "SELECT ";
        $query .=   " `a`.`idx`, ";
        $query .=   " `m`.`nickname`, ";
        $query .=   " `a`.`title`, ";
        $query .=   " `a`.`views`, ";
        $query .=   " `a`.`release_date_time`, ";
        $query .=   " `a`.`updated_date_time` ";
		$query .= "FROM ";
        $query .=   "`article` as `a`, ";
        $query .=   "`member` as `m` ";
		$query .= "WHERE ";
        $query .=	"`a`.`member_idx`=`m`.`idx` AND ";
        if ($this->member_idx!=null) { $query .= "`a`.`member_idx`=? AND "; }
		$query .=	"`a`.`status`=? ";
		$query .=	"ORDER BY `a`.`idx` desc ";

        $status = 1;

		$fmt = "";
        if ($this->member_idx!=null) {
            $fmt .= "i";
        }

		$params = array($fmt."i");
        if ($this->member_idx!=null) {
            $params[] = &$this->member_idx;
        }
		$params[] = &$status;

        return $this->postman->returnDataList( $query, $params );
    }

    public function get() {

        $query	= "SELECT ";
        $query .=   " `a`.`idx`, ";
        $query .=   " `a`.`title`, ";
        $query .=   " `a`.`content`, ";
        $query .=   " `a`.`release_date_time` ";
		$query .= "FROM ";
        $query .=   "`article` as `a` ";
		$query .= "WHERE ";
        if ($this->idx!=null) { $query .= "`a`.`idx`=? AND "; }
        if ($this->member_idx!=null) { $query .= "`a`.`member_idx`=? AND "; }
		$query .=	"`a`.`status`=? ";

        $status = 1;

		$fmt = "";
        if ($this->idx!=null) { $fmt .= "i"; }
        if ($this->member_idx!=null) { $fmt .= "i"; }

		$params = array($fmt."i");
        if ($this->idx!=null) { $params[] = &$this->idx; }
        if ($this->member_idx!=null) { $params[] = &$this->member_idx; }
		$params[] = &$status;

        return ArticleM::new($this->postman->returnDataObject( $query, $params ));
    }

    public function update() {

        $query	= "UPDATE ";
        $query .=   "`article` ";
        $query .= "SET ";
        $query .=	"`title`=?, ";
        $query .=	"`content`=?, ";
        $query .=	"`release_date_time`=?, ";
        $query .=	"`updated_date_time`=? ";
        $query .= "WHERE ";
        $query .=	"`idx`=? ";

        $updated_date_time = date('Y-m-d H:i:s');

        $this->postman->execute($query, array(
            'ssssi', &$this->title, &$this->content, &$this->release_date_time, &$this->updated_date_time, &$this->idx
        ));
    }
}
