<?php

class CompanyStockM extends BusinessModel {

    // public variables
    public $idx                 = null;
    public $company_idx         = null;
    public $price               = null;
    public $prev_diff           = null;
    public $percentage          = null;
    public $open                = null;
    public $high                = null;
    public $low                 = null;
    public $volume              = null;
    public $date                = null;
    public $created_date_time   = null;
    public $status              = 1;

    // help to create quick objects
    public static function new( $data = array() ) { return (new CompanyStockM())->extend($data); }

    //// ------------------------------ create setter & getters

    public function setIdx( $idx ) { $this->idx = $idx; return $this; }
    public function getIdx() { return $this->idx; }

    public function setCompanyIdx($company_idx) { $this->company_idx = $company_idx; return $this; }
    public function getCompanyIdx() { return $this->company_idx; }

    public function setPrice($price) { $this->price = $price; return $this; }
    public function getPrice() { return $this->price; }

    public function setPriceDiff($prev_diff) { $this->prev_diff = $prev_diff; return $this; }
    public function getPriceDiff() { return $this->prev_diff; }

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

    public function setDate($date) { $this->date = $date; return $this; }
    public function getDate() { return $this->date; }

    public function setCreatedDateTime( $created_date_time ) { $this->created_date_time = $created_date_time; return $this; }
    public function getCreatedDateTime($format = 'Y-m-d H:i:s') { $d = new DateTime($this->created_date_time); return $d->format($format); }

    public function setStatus($status) { $this->status = $status; return $this; }
    public function getStatus() { return $this->status; }

    public static function convertToCandleStick($sock_list) {
        return array_map(function($stock) {
            $d = new DateTime($stock->getDate());
            return array(
                'x' => 'new Date('.$d->format('Y').', '.$d->format('m').', '.$d->format('d').')',
                'y' => array($stock->getOpen(), $stock->getHigh(), $stock->getLow(), $stock->getPrice())
            );
        }, $sock_list);
    }

    //// ------------------------------ action function

    public function getList( $sortBy = 'date', $sortDirection = 'desc', $limit = '-1', $offset = '-1', $total_count = false ) {

        $query	= "SELECT ";
        $query .=   ($total_count)?"count(*) as cnt ":"idx,price,prev_diff,percentage,open,high,low,volume,date ";
		$query .= "FROM ";
        $query .=   "`company_stock` ";
		$query .= "WHERE ";
        if ($this->company_idx!=null){ $query .= "company_idx=? AND "; }
		$query .=	"status=? ";
		$query .=	"ORDER BY $sortBy $sortDirection ";
        if (!$total_count) { $query .= (($limit=='-1')&&($offset=='-1'))?'':"limit ? offset ? "; }

		$fmt = "";
        if ($this->company_idx!=null){ $fmt .= "i"; }
        $fmt .= "i";
        if (!$total_count) { $fmt .= (($limit=='-1')&&($offset=='-1'))?'':"ii";  }

		$params = array($fmt);
        if ($this->company_idx!=null){ $params[] = &$this->company_idx; }
		$params[] = &$this->status;

		if ( $total_count ) {
            return $this->postman->returnDataObject( $query, $params );
        } else {
            if (($limit!='-1')&&($offset!='-1')) {
                $params[] = &$limit;
                $params[] = &$offset;
            }
            return array_map(function($item) {
                return CompanyStockM::new($item);
            }, $this->postman->returnDataList( $query, $params ));
		}
    }
}
