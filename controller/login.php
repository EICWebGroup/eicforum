<?php
	//
	// session is available, redirect to private page
	if(!empty($_SESSION[KEY_SESSION])){

		if($_SESSION[KEY_SESSION][Account::KEY_ID] > 0){
			header('Location: /thread');
			die();
		}
	}


	if(!empty($_POST) || !empty($_COOKIE["k"])){

		if(!empty($_POST)){
			$username = $_POST["username"];
			$password = $_POST["password"];
			$autologin = false;
		}else{
			$username = $_COOKIE["usr"];
			$password = $_COOKIE["k"];
			$autologin = true;
		}


		$rememberMe = ($_POST["rememberme"] == "on");



		// instance account with username given by $_POST["username"]
		$account = new Account();
		$account->initWithUsername($username);

		///// パスワードの確認
		$login = false;
		if($autologin){

			if($password == $account->getCookieLock()){
				$login = true;

            }

		}else{
			if( Utils::encrpytPassword($password , $account->getSalt())
				== $account->getEncryptedPassword()	){

				$login = true;
			}
		}

		///// パスワードの確認が完了し、　ログインができます。　
		if($login){

            // 認証済み？
            if(!$account->isValidated()){
                header('Location: /account/verifyplease?accountid='.$account->getId());
                die();
            }

			if($rememberMe || $autologin){
				setAutoLogin($account);
			}

			$data = $account->throwData();

			unset($data['salt']);
			unset($data['password']);
			unset($data['validate_code']);
			unset($data['validated']);



			$_SESSION[KEY_SESSION] = $data;

			// successfully login
			// logging login here
			$text1 = "pc";
			if(Utils::is_mobile()){
				$text1 = "mobile";
			}

			if($autologin) $text2 = $account->getNickname() . " is auto-login";
			else $text2 = $_SERVER["REMOTE_ADDR"] . " is ".$account->getNickname()." and login successfully";

			Utils::setLoginLog(Utils::getCurrentDate() . " " . Utils::getCurrentTime() . " " . $text1 . " " . $text2);

			//// logging end /////



			if(empty($_GET["request_page"])){
				// Redirect the user to the private members-only page.
				header('Location: /thread');
				die();
			}else{
				// Redirect to requested_page if request_page parameter is not empty
				header('Location: http://eicforum.mydns.jp'.urldecode($_GET["request_page"]));
				die();
			}
		}else{

			// successfully fail to login
			// logging login here
			$text1 = "pc";
			if(Utils::is_mobile()){
				$text1 = "mobile";
			}
			Utils::setLoginLog(Utils::getCurrentDate() . " " . Utils::getCurrentTime()." ". $text1 ." ". $_SERVER["REMOTE_ADDR"] ." try to login ".$username." but fail");

		}
	}else{
		// GET request
		// logging request here
		if(Utils::is_bot()){
			// requested user is robot
			Utils::setLoginLog(Utils::getCurrentDate() . " " . Utils::getCurrentTime()." ROBOT => ". $_SERVER["HTTP_USER_AGENT"]);
		}else{
			// requested user is human
			$text1 = "pc";
			if(Utils::is_mobile()){
				$text1 = "mobile";
			}
			Utils::setLoginLog(Utils::getCurrentDate() . " " . Utils::getCurrentTime()." ". $text1 ." ". $_SERVER["REMOTE_ADDR"] ." request");
		}
	}


	//Utils::setLoginLog(Utils::getCurrentDate() . " " . Utils::getCurrentTime()." ". $text1 ." ". $_SERVER["REMOTE_ADDR"] ." is ".$username." ".$text2);


	function setAutoLogin($acc){

		// auto login cookie info



		// if there row["cookie_lock"] return null, get new key and new lock, then update new lock to row
		// else retrieve the row and set cookie

		// update cookie_lock
		if($acc->getCookieLock() == null){
			$acc->resetCookieLock();
		}

		setcookie("k",$acc->getCookieLock(),Cookie::EXPIRE_DATE(), Cookie::$PATH , Cookie::$URL);
		setcookie("usr",$acc->getUsername(),Cookie::EXPIRE_DATE(), Cookie::$PATH , Cookie::$URL);

	}
