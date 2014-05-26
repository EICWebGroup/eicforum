<?php
	include "private.php";

	$user = new Account();
	$user->initWithId($_SESSION[KEY_SESSION][Account::KEY_ID]);
	if(!$user->isAdmin()){
		// We redirect them to the login page
	    header("Location: /thread/" );
	    die();
	}


	if(!empty($_POST)){
		if($_POST["action"] == "noticeboard_create"){

			// create new note post request
			$author_id = $_SESSION[KEY_SESSION][Account::KEY_ID];
			$content = $_POST["notice_content"];
            $content = preg_replace("/\n/","<br>",$content);

            $content = preg_replace_callback("|\[(.*?)\]\((.*?)\)|", function($matches){
            	
            	return "<a href=\"{$matches[2]}\">$matches[1]</a>";
            }, $content);


			if(!empty($content)){
				NoticeBoard::create($author_id, $content);
			}

			die();
		}else if($_POST["action"] == "noticeboard_update"){
			// update note status

			$id = abs(intval($_POST["id"]));
			$checked = false;
			if($_POST["checked"] == "checked")$checked = true;

			$notice = new NoticeBoard();
			$notice -> initWithId($id);
			$notice -> setChecked($checked);

			die();

		}
	}

	class ManagerController{


		private static $path = "manager/";


		public static function noticeboard(){
                    $noticeboard = true;
                    $loginrecord = false;
                    $permission = false;
                    $googleanalytics = false;

                    include VIEWS_PATH."private-nav.php";
                    include VIEWS_PATH . self::$path . "noticeboard.php";

		}

		public static function loginrecord(){

                    $noticeboard = false;
                    $loginrecord = true;
                    $permission = false;
                    $googleanalytics = false;


                    include VIEWS_PATH."private-nav.php";
                    include VIEWS_PATH . self::$path . "loginrecord.php";
		}
                
		public static function permission(){

                    $noticeboard = false;
                    $loginrecord = false;
                    $permission = true;
                    $googleanalytics = false;

                    include VIEWS_PATH."private-nav.php";
                    include VIEWS_PATH . self::$path . "permission.php";

		}
                
                public static function googleAnalytics(){
                    $googleanalytics = true;
                    $noticeboard = false;
                    $loginrecord = false;
                    $permission = false;
                    
                    include VIEWS_PATH . "private-nav.php";
                    include VIEWS_PATH . self::$path . "googleAnalytics.php";
                }

	}
?>