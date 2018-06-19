<?php

class PlaylistGroupM extends BusinessModel {

    // public variables
    public $idx                 = null;
    public $sort_idx            = null;
    public $name                = null;
    public $created_date_time   = null;
    public $status              = 1;

    // help to create quick objects
    public static function new( $data = array() ) { return (new PlaylistGroupM())->extend($data); }

    //// ------------------------------ create setter & getters

    public function setIdx( $idx ) { $this->idx = $idx; return $this; }
    public function getIdx() { return $this->idx; }

    public function setSortIdx($sort_idx) { $this->sort_idx = $sort_idx; return $this; }
    public function getSortIdx() { return $this->sort_idx; }

    public function setName($name) { $this->name = $name; return $this; }
    public function getName() { return $this->name; }

    public function setCreatedDateTime( $created_date_time ) { $this->created_date_time = $created_date_time; return $this; }
    public function getCreatedDateTime($format = 'Y-m-d H:i:s') { $d = new DateTime($this->created_date_time); return $d->format($format); }

    public function setStatus($status) { $this->status = $status; return $this; }
    public function getStatus() { return $this->status; }

    //// ------------------------------ action function

    public function create() {
        $field  = array( 'sort_idx', 'name' );
        $data   = array( $this->sort_idx, $this->name );
        $fmt    = 'is';
        return $this->create_omr('playlist_group', $field, $data, $fmt);
    }

    public function getList( $sortBy = 'sort_idx', $sortDirection = 'asc', $limit = '-1', $offset = '-1', $total_count = false ) {

        $query	= "SELECT ";
        $query .=   ($total_count)?"count(*) as cnt ":"idx,name ";
		$query .= "FROM ";
        $query .=   "`playlist_group` ";
		$query .= "WHERE ";
		$query .=	"`status`=? ";
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
                return PlaylistGroupM::new($item);
            }, $this->postman->returnDataList( $query, $params ));
		}
    }

    public function get($select = ' idx,sort_idx,name ') {

        $query	= "SELECT ";
        $query .=   $select." ";
		$query .= "FROM ";
        $query .=   "`playlist_group` ";
		$query .= "WHERE ";
        $query .=	"`idx`=? AND ";
		$query .=	"`status`=? ";

		$params = array("ii");
        $params[] = &$this->idx;
		$params[] = &$this->status;

        return PlaylistGroupM::new($this->postman->returnDataObject( $query, $params ));
    }

    public function getCount() {

        $query	= "SELECT ";
        $query .=   "count(*) as cnt ";
		$query .= "FROM ";
        $query .=   "`playlist` ";
		$query .= "WHERE ";
        $query .=	"`group_idx`=? AND ";
		$query .=	"`status`=? ";

		$params = array("ii");
        $params[] = &$this->idx;
		$params[] = &$this->status;

        return $this->postman->returnDataObject( $query, $params );
    }

    public function update() {

        $query	= "UPDATE ";
        $query .=   "`playlist_group` ";
		$query .= "SET ";
		$query .=   "`sort_idx`=?, ";
        $query .=   "`name`=? ";
        $query .= "WHERE ";
		$query .=   "`idx`=? ";

		$params = array("isi");
        $params[] = &$this->sort_idx;
        $params[] = &$this->name;
        $params[] = &$this->idx;

        $this->postman->execute( $query, $params );
    }

    public function remove() {

        $query	= "UPDATE ";
        $query .=   "`playlist_group` ";
		$query .= "SET ";
		$query .=   "`status`=? ";
        $query .= "WHERE ";
		$query .=   "`idx`=? ";

        $status = 0;

		$params = array("ii");
        $params[] = &$status;
        $params[] = &$this->idx;

        $this->postman->execute( $query, $params );
    }
}
