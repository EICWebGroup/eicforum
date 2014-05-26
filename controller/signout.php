<?php
	// We remove the user's data from the session
    unset($_SESSION[KEY_SESSION]);

	$expiredate = time() - 300;

	// unset cookie by set to past date
	setcookie("k"," ",$expiredate,"/",Cookie::$URL);
	setcookie("usr"," ",$expiredate,"/",Cookie::$URL);

    // We redirect them to the login page
    header("Location: " . Common::$HOME );
    die("Redirecting to: " . Common::$HOME );
    
