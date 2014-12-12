<?php

	//
	// show error on browser
	// error_reporting( E_ALL ); ini_set( "display_errors", 1 );


    // httpからhttpsへリダイレクト
	
    //if( empty($_SERVER['HTTPS']) ){
    //    $redirect = "https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
    //    header("Location: ".$redirect);
    //    exit;
    //}

	define('ROOT', dirname(__FILE__) .'/', TRUE);
	define('CONTROLLER_PATH', dirname(__FILE__). '/controller/', TRUE);
	define('VIEWS_PATH', dirname(__FILE__). '/views/', TRUE);

	define('KEY_PATH','path',TRUE);
	define('KEY_TARGET', 'target',TRUE);
	define('KEY_SESSION','thisissession', TRUE);
	define('KEY_OTHER_1','action', TRUE);
	define('KEY_TARGET_2','tgt2', TRUE);

	// system directory (config and common)
	include ROOT . "config/config.php";
	
	
	include ROOT . "common/utils.php";
	include ROOT . "common/simple_html_dom.php";

	// models directory
	include ROOT . "models/thread.php";
	include ROOT . "models/account.php";
    include ROOT . "models/post.php";
    include ROOT . "models/noticeboard.php";
    include ROOT . "models/temp_validation_code.php";

    // libs
    include ROOT . "libs/html_to_markdown.php";
