<?php

class ModelTrainingHistoryM extends BusinessModel {

    // public variables
    public $idx                 = null;
    public $startt              = null;
    public $endt                = null;
    public $created_date_time   = null;
    public $status              = 1;

    public $search_name         = null;

    // help to create quick objects
    public static function new( $data = array() ) { return (new ModelTrainingHistoryM())->extend($data); }

    //// ------------------------------ create setter & getters

    public function setIdx( $idx ) { $this->idx = $idx; return $this; }
    public function getIdx() { return $this->idx; }

    public function setStartt($startt) { $this->startt = $startt; return $this; }
    public function getStartt() { return $this->startt; }

    public function setEndt($endt) { $this->endt = $endt; return $this; }
    public function getEndt() { return $this->endt; }

    public function setCreatedDateTime( $created_date_time ) { $this->created_date_time = $created_date_time; return $this; }
    public function getCreatedDateTime($format = 'Y-m-d H:i:s') { $d = new DateTime($this->created_date_time); return $d->format($format); }

    public function setStatus($status) { $this->status = $status; return $this; }
    public function getStatus() { return $this->status; }

    //// ------------------------------ action function

    public function getList( $sortBy = 'idx', $sortDirection = 'desc', $limit = '-1', $offset = '-1', $total_count = false ) {

        $query	= "SELECT ";
        $query .=   ($total_count)?"count(*) as cnt ":"idx,startt,endt,created_date_time ";
		$query .= "FROM ";
        $query .=   "`model_training_history` ";
		$query .= "WHERE ";
		$query .=	"status=? ";
		$query .=	"ORDER BY $sortBy $sortDirection ";
        if (!$total_count) { $query .= (($limit=='-1')&&($offset=='-1'))?'':"limit ? offset ? "; }

		$fmt = "";
        $fmt .= "i";
        if (!$total_count) { $fmt .= (($limit=='-1')&&($offset=='-1'))?'':"ii";  }

		$params = array($fmt);
		$params[] = &$this->status;

		if ( $total_count ) {
            return $this->postman->returnDataObject( $query, $params );
        } else {
            if (($limit!='-1')&&($offset!='-1')) {
                $params[] = &$limit;
                $params[] = &$offset;
            }
            return array_map(function($item) {
                return ModelTrainingHistoryM::new($item);
            }, $this->postman->returnDataList( $query, $params ));
		}
    }
}
