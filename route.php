<?php
	if($_GET[KEY_PATH] == "thread"){

		// path = thread
		include CONTROLLER_PATH . "thread.php";

		if(empty($_GET[KEY_TARGET])){
			ThreadController::_list();

		}else{
			if($_GET[KEY_TARGET] == "create"){
				ThreadController::create();
            }else if($_GET[KEY_TARGET] == "createrss"){
                ThreadController::createRSS();
			}else{

				if(empty($_GET[KEY_OTHER_1]))
					ThreadController::show($_GET[KEY_TARGET]);
				else{
					if($_GET[KEY_OTHER_1] == "edit"){
						ThreadController::edit($_GET[KEY_TARGET], $_GET[KEY_TARGET_2]);
					}else if($_GET[KEY_OTHER_1] == "delete"){
						ThreadController::delete($_GET[KEY_TARGET], $_GET[KEY_TARGET_2]);
					}else if($_GET[KEY_OTHER_1] == "editrss"){
						ThreadController::editRSS($_GET[KEY_TARGET], $_GET[KEY_TARGET_2]);
					}
				}

			}
		}
	}else if($_GET[KEY_PATH] == "account"){

        // begin
        // if current account is guest account, redirect it to no-permission page
        if($_GET[KEY_TARGET] != "login" && $_GET[KEY_TARGET] != "signout"){
            if($_SESSION[KEY_SESSION][Account::KEY_USERNAME] == "guest"){
                Utils::showNoPermissionPage();
                die();
            }
        }
        // end


		include CONTROLLER_PATH . "account.php";

		// path = account
		if(empty($_GET[KEY_TARGET])){
			AccountController::show();
		}else{
			if($_GET[KEY_TARGET] == "edit"){
				AccountController::edit();
			}else if($_GET[KEY_TARGET] == "login"){
				AccountController::login();
			}else if($_GET[KEY_TARGET] == "signout"){
				AccountController::signout();
			}else if($_GET[KEY_TARGET] == "passwordedit"){
				AccountController::passwordEdit();
			}else if($_GET[KEY_TARGET] == "create"){
                AccountController::create();
            }else if($_GET[KEY_TARGET] == "verifyplease"){
                AccountController::verifyPlease();
            }else if($_GET[KEY_TARGET] == "verifydone"){
                AccountController::verifyDone();
            }else if($_GET[KEY_TARGET] == "forgetpassword"){
                AccountController::forgetPassword();
            }else if($_GET[KEY_TARGET] == "resetpassword"){
                AccountController::resetPassword();
            }else if($_GET[KEY_TARGET] == "resetpassworddone"){
                AccountController::resetPasswordDone();
            }else{
				AccountController::show();
			}

		}
	}else if($_GET[KEY_PATH] == "manager"){

		include CONTROLLER_PATH . "manager.php";

		// path = manager
		if($_GET[KEY_TARGET] == "noticeboard"){
                    ManagerController::noticeboard();
		}else if($_GET[KEY_TARGET] == "loginrecord"){
                    ManagerController::loginrecord();
		}else if($_GET[KEY_TARGET] == "permission"){
                    ManagerController::permission();
		}else if($_GET[KEY_TARGET] == "googleanalytics"){
                    ManagerController::googleAnalytics();
                }

	}else if($_GET[KEY_PATH] == "help"){
		include CONTROLLER_PATH . "help.php";
		HelpController::show();
	}else if($_GET[KEY_PATH] == "search"){
            include CONTROLLER_PATH . "search.php";
            SearchController::index();
        }
?>