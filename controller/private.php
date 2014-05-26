<?php

	if(empty($_SESSION[KEY_SESSION])){
	    header("Location: " . Common::$HOME."?request_page=".urlencode($_SERVER["REQUEST_URI"]));
	    die("Redirecting to: " . Common::$HOME );
	}
