<?php

class MatrixM extends BusinessModel {

    // public variables
    public $idx                 = null;
    public $start_date          = null;
    public $end_date            = null;
    public $processed           = null;
    public $created_date_time   = null;
    public $status              = null;

    // help to create quick objects
    public static function new( $data = array() ) { return (new MatrixM())->extend($data); }

    //// ------------------------------ create setter & getters

    public function setIdx( $idx ) { $this->idx = $idx; return $this; }
    public function getIdx() { return $this->idx; }

    public function setStartDate($start_date) { $this->start_date = $start_date; return $this; }
    public function getStartDate() { return $this->start_date; }

    public function setEndDate($end_date) { $this->end_date = $end_date; return $this; }
    public function getEndDate() { return $this->end_date; }

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
        $query .=   "`matrix` ";
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
        $query .=   "`matrix` ";
		$query .= "WHERE ";
        $query .=	"`processed`=? AND ";
		$query .=	"`status`=? ";

        $processed  = 2;
        $status     = 1;

        return ($this->postman->returnDataObject($query, array("ii", &$processed, &$status)))->cnt;
    }
}
