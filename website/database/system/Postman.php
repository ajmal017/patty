<?php

class Postman {

	// postman singleton
	static $singleton;

	// mysql connection
	var $mysqlConnection;

	public static function init() {
		if ( Postman::$singleton == null) {

			// create new object
			Postman::$singleton = new Postman();

			// create connection
			Postman::$singleton->connect();
		}

		return Postman::$singleton;
	}

	public function connect() {

		if ($this->mysqlConnection  == null ) {

			// init mysql connection
			$this->mysqlConnection = mysqli_init();

			// load database connection information
			$config = json_decode(file_get_contents('/var/www/database.config'));

			// create connection
			if(mysqli_real_connect($this->mysqlConnection, $config->snu->host, $config->snu->user, $config->snu->password, 'stock', $config->snu->port)) {
				mysqli_set_charset( $this->mysqlConnection, $config->snu->charset );
				mysqli_query($this->mysqlConnection, 'SET NAMES ' . $config->snu->connection);
			}
		}

		return $this->mysqlConnection;
	}

	function db_bind_param(&$stmt, $params) {
		$f = array($stmt, "bind_param");
		return call_user_func_array($f, $params);
	}

	function __destruct() {
		if ( $this->mysqlConnection != null ) {
			@mysqli_close($this->mysqlConnection);
		}
	}

	// -------------------------------------------------

	function sql($query, $params) {

		for ($i = 1; $i <= (count($params) - 1); $i++) {
			$query = $this->str_replace_first('?', '\''. $params[$i] . '\'', $query);
		}

		return $query;
	}

	function str_replace_first($from, $to, $subject) {
		$from = '/'.preg_quote($from, '/').'/';
		return preg_replace($from, $to, $subject, 1);
	}

	function execute($query, $params, $return_insert_idx = false) {

		$stmt = $this->mysqlConnection->stmt_init();
		$stmt = $this->mysqlConnection->prepare($query);

		$this->db_bind_param($stmt, $params);
		$result = $stmt->execute();

		if (!$result) {
			exit(json_encode( array( 'code' => '400', 'msg' => $this->mysqlConnection->error, 'sql' => $query3 ) ));
		}

		$result = $stmt->get_result();

		if ( $return_insert_idx ) {
			return $stmt->insert_id;
		} else {
			return $result;
		}
	}

	function returnDataList($query, $params) {

		$result = $this->execute($query, $params);

		$return_data = array();
		while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
			$object = new stdClass();
			foreach ($row as $key => $value) {
				$object->$key = $value;
			}
			array_push($return_data, $object);
		}

		return $return_data;
	}

	function returnDataObject($query, $params) {
		$list = $this->returnDataList($query, $params);
		return (isset($list[0])) ? $list[0] : new stdClass();
	}
}