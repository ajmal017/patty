<?php

class DataModel {

    protected $postman = null;

    public function __construct() {
        $this->postman = Postman::init();
    }

    public function create_omr( $tableName, $field_list, $data_list, $fmt ) {

		$query	= "INSERT INTO ";
		$query .=   "`$tableName` ";
		$query .=	"( ";
        foreach($field_list as $field) {
            $query .=	"`$field`, ";
        }
        $query .=	" `created_date_time`, `status`) ";
		$query .= "VALUES ";
		$query .=	"( ";
        foreach($data_list as $data) {
            $query .=	" ?, ";
        }
        $query .=	" ?, ?) ";

		$created_date_time	= date('Y-m-d H:i:s');
		$status				= '1';

		$params = array($fmt."si");
        foreach($data_list as &$data) {
            $params[] = &$data;
        }
		$params[] = &$created_date_time;
		$params[] = &$status;
        // echo $this->postman->sql( $query, $params );
        return $this->postman->execute( $query, $params, true );
    }
}
