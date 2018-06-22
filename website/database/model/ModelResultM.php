<?php

class ModelResultM extends BusinessModel {

    // public variables
    public $idx                 = null;
    public $playlist_idx        = null;
    public $train_company_idx   = null;
    public $test_company_idx    = null;
    public $type                = null;
    public $f1                  = null;
    public $recall              = null;
    public $accuracy            = null;
    public $precise             = null;
    public $score               = null;
    public $duration            = null;
    public $created_date_time   = null;
    public $status              = 1;

    // help to create quick objects
    public static function new( $data = array() ) { return (new ModelResultM())->extend($data); }

    //// ------------------------------ create setter & getters

    public function setIdx( $idx ) { $this->idx = $idx; return $this; }
    public function getIdx() { return $this->idx; }

    public function setPlaylistIdx($playlist_idx) { $this->playlist_idx = $playlist_idx; return $this; }
    public function getPlaylistIdx() { return $this->playlist_idx; }

    public function setTrainCompanyIdx($train_company_idx) { $this->train_company_idx = $train_company_idx; return $this; }
    public function getTrainCompanyIdx() { return $this->train_company_idx; }

    public function setTestCompanyIdx($test_company_idx) { $this->test_company_idx = $test_company_idx; return $this; }
    public function getTestCompanyIdx() { return $this->test_company_idx; }

    public function setType($type) { $this->type = $type; return $this; }
    public function getType() { return $this->type; }

    public function setF1($f1) { $this->f1 = $f1; return $this; }
    public function getF1() { return $this->f1; }

    public function setRecall($recall) { $this->recall = $recall; return $this; }
    public function getRecall() { return $this->recall; }

    public function setAccuracy($accuracy) { $this->accuracy = $accuracy; return $this; }
    public function getAccuracy() { return $this->accuracy; }

    public function setPrecise($precise) { $this->precise = $precise; return $this; }
    public function getPrecise() { return $this->precise; }

    public function setScore($score) { $this->score = $score; return $this; }
    public function getScore() { return $this->score; }

    public function setDuration($duration) { $this->duration = $duration; return $this; }
    public function getDuration() { return $this->duration; }

    public function setCreatedDateTime( $created_date_time ) { $this->created_date_time = $created_date_time; return $this; }
    public function getCreatedDateTime($format = 'Y-m-d H:i:s') { $d = new DateTime($this->created_date_time); return $d->format($format); }

    public function setStatus($status) { $this->status = $status; return $this; }
    public function getStatus() { return $this->status; }

    //// ------------------------------ action function

    public function getList( $sortBy = '`score`', $sortDirection = 'desc', $limit = '-1', $offset = '-1', $total_count = false ) {

        $query	= "SELECT ";
        $query .=   ($total_count)?"count(*) as cnt ":"idx,playlist_idx,train_company_idx,test_company_idx,type,f1,recall,accuracy,precise,score,duration ";
		$query .= "FROM ";
        $query .=   "`model_result` ";
		$query .= "WHERE ";
        if ($this->playlist_idx!=null) { $query .= "`playlist_idx`=? AND "; }
        if ($this->train_company_idx!=null) { $query .= "`train_company_idx`=? AND "; }
		$query .=	"`status`=? ";
		$query .=	"ORDER BY $sortBy $sortDirection ";
        if (!$total_count) { $query .= (($limit=='-1')&&($offset=='-1'))?'':"limit ? offset ? "; }

		$fmt = "";
        if ($this->playlist_idx!=null) { $fmt .= "i"; }
        if ($this->train_company_idx!=null) { $fmt .= "i"; }
        $fmt .= "i";
        if (!$total_count) { $fmt .= (($limit=='-1')&&($offset=='-1'))?'':"ii";  }

		$params = array($fmt);
        if ($this->playlist_idx!=null) { $params[] = &$this->playlist_idx; }
        if ($this->train_company_idx!=null) { $params[] = &$this->train_company_idx; }
		$params[] = &$this->status;

        if (($limit!='-1')&&($offset!='-1')) {
            $params[] = &$limit;
            $params[] = &$offset;
        }
        return array_map(function($item) {
            return ModelResultM::new($item);
        }, $this->postman->returnDataList( $query, $params ));
    }
}
