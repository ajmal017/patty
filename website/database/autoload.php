<?php

	// -------------------------------- constants

	// create base path
	define('POSTMAN_BASEPATH', dirname(__FILE__) . '/');

	// model base path
	define('POSTMAN_BASEPATH_MODEL', dirname(__FILE__) . '/model/');

	// system base path
	define('POSTMAN_BASEPATH_SYSTEM', dirname(__FILE__) . '/system/');

	// ------------------------------ begin

	// load the data model base class
	require_once POSTMAN_BASEPATH_SYSTEM.'DataModel.php';

	// load the business model base class
	require_once POSTMAN_BASEPATH_SYSTEM.'BusinessModel.php';

	// load the postman class
	require_once POSTMAN_BASEPATH_SYSTEM.'Postman.php';

    /* **
     * Time to load all models
     */
    // get list of models in the model directory
    $model_file_list = array_diff(scandir(POSTMAN_BASEPATH_MODEL), array('..','.'));

    // go through list of models and include each file
    foreach($model_file_list as $model_file) {
        include POSTMAN_BASEPATH_MODEL . $model_file;
    }

	// lets go!
	Postman::init();
