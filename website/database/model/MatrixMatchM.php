<?php

class MatrixMatchM extends BusinessModel {

    // public variables
    public $idx                 = null;
    public $matrix_idx          = null;
    public $company_idx         = null;
    public $processed           = null;
    public $created_date_time   = null;
    public $status              = null;

    // help to create quick objects
    public static function new( $data = array() ) { return (new MatrixMatchM())->extend($data); }

    //// ------------------------------ create setter & getters

    public function setIdx( $idx ) { $this->idx = $idx; return $this; }
    public function getIdx() { return $this->idx; }

    public function setMatrixIdx($matrix_idx) { $this->matrix_idx = $matrix_idx; return $this; }
    public function getMatrixIdx() { return $this->matrix_idx; }

    public function setCompanyIdx($company_idx) { $this->company_idx = $company_idx; return $this; }
    public function getCompanyIdx() { return $this->company_idx; }

    public function setProcessed($processed) { $this->processed = $processed; return $this; }
    public function getProcessed() { return $this->processed; }

    public function setCreatedDateTime( $created_date_time ) { $this->created_date_time = $created_date_time; return $this; }
    public function getCreatedDateTime($format = 'Y-m-d H:i:s') { $d = new DateTime($this->created_date_time); return $d->format($format); }

    public function setStatus($status) { $this->status = $status; return $this; }
    public function getStatus() { return $this->status; }

    //// ------------------------------ action function

    public function getNotProcessedCount() {

        $query	= "SELECT ";
        $query .=   " count(*) as cnt ";
		$query .= "FROM ";
        $query .=   "`matrix_match` ";
		$query .= "WHERE ";
        $query .=	"`processed`=? AND ";
		$query .=	"`status`=? ";

        $processed  = 1;
        $status     = 1;

        return ($this->postman->returnDataObject($query, array("ii", &$processed, &$status)))->cnt;
    }

    public function getProcessedCount() {

        $query	= "SELECT ";
        $query .=   " count(*) as cnt ";
		$query .= "FROM ";
        $query .=   "`matrix_match` ";
		$query .= "WHERE ";
        $query .=	"`processed`=? AND ";
		$query .=	"`status`=? ";

        $processed  = 2;
        $status     = 1;

        return ($this->postman->returnDataObject($query, array("ii", &$processed, &$status)))->cnt;
    }
}
