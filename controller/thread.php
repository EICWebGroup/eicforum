<?php

	include "private.php";

	class ThreadController{


		public static function create(){



            // begin
            if($_SESSION[KEY_SESSION][Account::KEY_USERNAME] == "guest"){
                Utils::showNoPermissionPage();
                die();
            }
            // end

			$isMobile = Utils::is_mobile();

			$textarea_title = "";
			$textarea_content = "";
			$error_message = "";

			if(!empty($_POST)){
				// create thread

				$host_id = $_SESSION[KEY_SESSION][Account::KEY_ID];
				$title = $_POST["title"];
				$update_time = Utils::getCurrentTime();
				$update_date = Utils::getCurrentDate();
				$latest_update = time();
				$content = $_POST["content"];
				$permission = $_POST["permission"];

				$content_len = 0;
				if($isMobile) {
					$content = preg_replace("/<br \/>|<br\/>|<br>/",'',$content);
					$content = preg_replace("/\r\n|\r|\n/",'<br />',$content);
					$content_len = strlen($content);
				}else{
					$content_len = Utils::textLength($content);
				}

				if($content_len > 5 && strlen($title) > 5 && in_array($permission, Thread::$PERMISSIONS)){

					// succeess
					$last_created_id = Thread::create($host_id, $title, $update_time, $update_date, $latest_update, $content,NULL, $permission);
					header("Location: /thread/".$last_created_id);
		    		die();

				}else{
					// fail
					$error_message = "コンテンツとタイトルの文字数は必ず５文字以上でなければいけません。";

					$textarea_title = $title;
					$textarea_content = $content;
				}
			}


			$content = "create.php";
			include VIEWS_PATH."private-nav.php";
			include VIEWS_PATH . "thread/thread.php";
		}

        public static function createRSS(){


            // begin
            if($_SESSION[KEY_SESSION][Account::KEY_USERNAME] == "guest"){
                Utils::showNoPermissionPage();
                die();
            }
            // end

            $error_message   = "";
            $title           = "";
            $url             = "";

            if(!empty($_POST)){

                $title = $_POST["title"];
                $url = $_POST["url"];


                if(empty($title))
                    $error_message .= "<li>タイトルを空白にしないでください。</li>";

                if(empty($url))
                    $error_message .= "<li>URLを空白にしないでください。</li>";
                else if(!preg_match("/^https?:\/\//", $url))
                    $error_message .= "<li>URLは正しくありません。</li>";



                // if not error, there should be no error_message
                if(strlen($error_message) == 0){
                    $json = json_encode(array(
                        "title"     =>$title,
                        "url"       =>$url,
                        "version"   => 1
                    ));


                    $host_id = $_SESSION[KEY_SESSION][Account::KEY_ID];
                    $update_time = Utils::getCurrentTime();
                    $update_date = Utils::getCurrentDate();
                    $latest_update = time();
                    $content = "<script>$json</script>";
                    $permission = $_POST["permission"];

                    $last_created_id = Thread::create($host_id, $title, $update_time, $update_date, $latest_update, $content,"rss", $permission);
                    header("Location: /thread/".$last_created_id);
		    		die();
                }

            }

            $content = "create-rss.php";

            Utils::output_view(["private-nav.php","thread/thread.php"] ,["content"=>$content]);
        }



		public static function show($thread_id){

			$thread = new Thread();
			$error_message = "";
			$textarea_content = "";

			$isMobile = Utils::is_mobile();

            // comment
			if(!empty($_POST)){
				// insert post into thread


				$thread->initWithId($thread_id);



				$author_id = $_SESSION[KEY_SESSION][Account::KEY_ID];
				$title = $thread -> getTitle();
				$update_time = Utils::getCurrentTime();
				$update_date = Utils::getCurrentDate();
				$latest_update = time();
				$content = $_POST["content"];


				$content_len = 0;
				if($isMobile){
					$content = preg_replace("/<br \/>|<br\/>|<br>/",'',$content);
					$content = preg_replace("/\r\n|\r|\n/",'<br />',$content);
					$content_len = strlen($content);
				}else{
					$content_len = Utils::textLength($content);
				}


				if($content_len > 5  ){
					// post request success

					Post::create($thread_id, $title, $content, $update_date, $update_time, $author_id, "text");


					header("Location: /thread/".$thread_id);
					die();

				}else{
					// post request fail
					$error_message = "コンテンツ文字数は必ず５文字以上でなければいけません。";
					$textarea_content = $content;
				}

                // end of post comment
			}else{
				// get request

				$thread_id = $_GET[KEY_TARGET];

				$thread->initWithId($thread_id);
			}

			if($isMobile){
                            foreach($thread->getAllPosts() as $p){
                                if(Utils::appletTagFound($p->getContent())){
						// if applet tag is found, redirect to unable_to_access

						$content = ROOT."common/no-available-applet.php";
						include VIEWS_PATH."private-nav.php";
						include VIEWS_PATH . "thread/thread.php";

						die();
					}
				}
			}

			// number of thread show per page. Current is 10
			$show = 10;
			if(empty($_GET["page"])){
				$offset = 0;
				$page = 1;
			}else{
				$page = abs(intval($_GET["page"]));

				if($page == 0)$page = 1;
				$offset = ($page - 1) * $show;
			}


			$showPageCallback =  function($i, $current_page){

				if($i == $current_page)
					echo "<a class='anchor' href='/thread/{$_GET[KEY_TARGET]}/page/$i'><span class='page_number current'>$i</span></a>";
				else
					echo "<a class='anchor' href='/thread/{$_GET[KEY_TARGET]}/page/$i'><span class='page_number'>$i</span></a>";

			};

			$posts = array();
			// query single post
			if($offset == 0){
				foreach($thread->queryPosts(" WHERE _id=1;") as $t){
					$script_text = $t->getScriptText();
                    if(empty($script_text)){
                        // there is no script tag inside this post

                         array_push($posts, $t);
                    }else{
                        // script tag
                        $json = json_decode($script_text, true);

                        $new_content = '<script>parseRSS("'.$json["url"].'",function(data){var newElement = document.createElement("div");'
                                . 'newElement.innerHTML = "<p class=\"rss-link\">このスレッドは<a href=\'"+data.getLinkByTitle("'.$json["title"].'") + "\'>こちら</a>から引用しました。</p>";'
                                . 'console.log(data.getTitle("'.$json["title"].'"));'
                                . 'newElement.innerHTML += data.getTitle("'.$json["title"].'").content;'
                                . 'document.getElementsByClassName("post")[0].insertBefore(newElement,document.getElementsByClassName("post")[0].childNode);});</script>';


                        $t->setContent($new_content);

                        array_push($posts, $t);
                    }
				}
				foreach($thread->queryPosts(" WHERE _id!=1 ORDER BY ".Post::KEY_MODIFIED_TIME." DESC LIMIT ".$offset.",".($show - 1)) as $t){


                    array_push($posts, $t);

				}
			}else{
				$posts = $thread->queryPosts(" WHERE _id!=1 ORDER BY ".Post::KEY_MODIFIED_TIME." DESC LIMIT ".$offset.",".$show);
			}


			// query multiple post
			$content = "show.php";
			include VIEWS_PATH."private-nav.php";
			include VIEWS_PATH . "thread/thread.php";
		}

        public static function editRSS($thread_id, $post_id){


            // begin
            if($_SESSION[KEY_SESSION][Account::KEY_USERNAME] == "guest"){
                Utils::showNoPermissionPage();
                die();
            }
            // end

            $thread = new Thread();
            $thread->initWithId($thread_id);
            $post = $thread->getPostById($post_id);


            // it is impossible that script text is empty
            if(strlen($post->getScriptText()) == 0 ||
                    $post->getAuthor()->getId() != $_SESSION[KEY_SESSION][Account::KEY_ID] ){
                Utils::showNoPermissionPage();
		   		return ;
            }

            $json = json_decode($post->getScriptText(), true);

            $error_message   = "";

            if(!empty($_POST)){

                $title = $_POST["title"];
                $url = $_POST["url"];


                if(empty($title))
                    $error_message .= "<li>タイトルを空白にしないでください。</li>";

                if(empty($url))
                    $error_message .= "<li>URLを空白にしないでください。</li>";
                else if(!preg_match("/^https?:\/\//", $url))
                    $error_message .= "<li>URLは正しくありません。</li>";



                // if not error, there should be no error_message
                if(strlen($error_message) == 0){
                    $json = json_encode(array(
                        "title"     =>$title,
                        "url"       =>$url,
                        "version"   => 1
                    ));


                    $host_id = $_SESSION[KEY_SESSION][Account::KEY_ID];
                    $update_time = Utils::getCurrentTime();
                    $update_date = Utils::getCurrentDate();
                    $latest_update = time();
                    $content = "<script>$json</script>";
                    $permission = $_POST["permission"];

                    $thread -> update($post_id,$title, $update_time, $update_date, $latest_update, $content, $permission);

                    header("Location: /thread/".$thread_id);
		    		die();
                }

            }else{
                // get request
                $title  = $json["title"];
                $url    = $json["url"];
            }

            $content = "edit-rss.php";
			include VIEWS_PATH."private-nav.php";
			include VIEWS_PATH . "thread/thread.php";
        }

		public static function edit($thread_id, $post_id){


            // begin
            if($_SESSION[KEY_SESSION][Account::KEY_USERNAME] == "guest"){
                Utils::showNoPermissionPage();
                die();
            }
            // end



			$isMobile = Utils::is_mobile();


			$thread = new Thread();

            $error_message = "";
			$textarea_title = "";
			$textarea_content = "";

			$thread -> initWithId($thread_id);
			$post = $thread -> getPostById($post_id);


			if(!empty($_POST) && $post->getAuthor()->getId() == $_SESSION[KEY_SESSION][Account::KEY_ID] ){

				// update post
				$title = $_POST["title"];
				$update_time = Utils::getCurrentTime();
				$update_date = Utils::getCurrentDate();
				$latest_update = time();

				$content = $_POST["content"];
				$permission = $_POST["permission"];

				$title_len = strlen($title);
				$content_len = 0;
				if($isMobile) {
					$content = preg_replace("/<br \/>|<br\/>|<br>/",'',$content);
					$content = preg_replace("/\r\n|\r|\n/",'<br />',$content);
					$content_len = strlen($content);
				}else{
					$content_len = Utils::textLength($content);
				}


				if($content_len > 5 && $title_len > 5 ){

					// success

					if($thread->getHost()->getId() == $_SESSION[KEY_SESSION][Account::KEY_ID] && in_array($permission, Thread::$PERMISSIONS) ){
						$thread -> update($post_id,$title, $update_time, $update_date, $latest_update, $content, $permission);
					}else{
						$thread -> update($post_id,$title, $update_time, $update_date, $latest_update, $content, $thread->getPermission());
					}

					header("Location: /thread/".$thread_id);

					unset($thread);
					unset($post_id);
					unset($thread_id);

			    	die();

				}else{
					// fail
					$error_message = "コンテンツ文字数は必ず５文字以上でなければいけません。";
					$textarea_content = $content;
					$textarea_title = $title;
				}
			}else{
				// get request
				$thread -> initWithId($thread_id);
				$post = $thread -> getPostById($post_id);

				$permission = $thread -> getPermission();
				if(!self::checkingPermission($thread_id, $post_id , $permission) || !($_SESSION[KEY_SESSION][Account::KEY_ID] == $post->getAuthor()->getId())){
					Utils::showNoPermissionPage();
		   			return ;
				}


				$textarea_title = $thread -> getTitle();
				$textarea_content = $post -> getContent();

			}

            $textarea_content = preg_replace('/src="\/?uploadManager\//', " src=\"/common/uploadManager/", $textarea_content);

            if (get_magic_quotes_gpc())
                $textarea_content = stripslashes($textarea_content);

            $textarea_content = new HTML_To_Markdown($textarea_content);
            $textarea_content = preg_replace_callback('/\!\[(.*?)\]\((.*?) \"(.*?)\"\)/',function($m){
                $m[2] = preg_replace("/\s/", "%20", $m[2]);
                return "![$m[1]]($m[2] \"$m[3]\")";
            },$textarea_content);

			$content = "edit.php";
			include VIEWS_PATH."private-nav.php";
			include VIEWS_PATH . "thread/thread.php";
		}

		public static function _list(){

			// number of thread show per page. Current is 10
			$show = 10;
			if(empty($_GET["page"])){
				$offset = 0;
				$page = 1;
			}else{
				$page = abs(intval($_GET["page"]));

				if($page == 0)$page = 1;
				$offset = ($page - 1) * $show;

			}

			$showPageCallback =  function($i, $current_page){


				if(empty($_GET["tab"])){
					$tab = "all";
				}else{
					$tab = $_GET["tab"];
				}

				if($i == $current_page)
					echo "<a class='anchor' href='/thread/$tab/page/$i'><span class='page_number current'>$i</span></a>";
				else
					echo "<a class='anchor' href='/thread/$tab/page/$i'><span class='page_number'>$i</span></a>";

			};



			if($_GET["tab"] == "noda"){
				// tab - noda
				$tab_views_all 	= "";
				$tab_views_noda = "current-tab";
				$tab_views_katsushika = "";


				$all_threads = array();
				foreach(Thread::allPermissionGiven($_SESSION[KEY_SESSION][Account::KEY_CAMPUS]) as $t){

					if($t->getHost()->getCampus() == "野田"){
						array_push($all_threads, $t);
					}
				}

				$len = count($all_threads);

				$threads = self::extractArray($all_threads, $offset, $show);


			}else if($_GET["tab"] == "katsushika"){
				// tab - katsushika
				$tab_views_all 	= "";
				$tab_views_noda = "";
				$tab_views_katsushika = "current-tab";


				$all_threads = array();
				foreach(Thread::allPermissionGiven($_SESSION[KEY_SESSION][Account::KEY_CAMPUS]) as $t){

					if($t->getHost()->getCampus() == "葛飾"){
						array_push($all_threads, $t);
					}


				}

				$len = count($all_threads);

				$threads = self::extractArray($all_threads, $offset, $show);

			}else{
				// tab - all
				$tab_views_all 	= "current-tab";
				$tab_views_noda = "";
				$tab_views_katsushika = "";

				$all_permitted_threads = Thread::allPermissionGiven($_SESSION[KEY_SESSION][Account::KEY_CAMPUS]);
				$len = count($all_permitted_threads);
				$threads = self::extractArray($all_permitted_threads, $offset, $show);
			}

			$content = "list.php";
			Utils::output_view(["private-nav.php","thread/thread.php"] ,
				[	
					"content"=>$content, 
					"threads" => $threads,
					"tab_views_all"=>$tab_views_all,
					"tab_views_noda"=>$tab_views_noda,
					"tab_views_katsushika"=>$tab_views_katsushika,
					"all_permitted_threads"=>$all_permitted_threads,
					"len"=>$len,

				]);
			
		}

		public static function delete($thread_id, $post_id){


            // begin
            if($_SESSION[KEY_SESSION][Account::KEY_USERNAME] == "guest"){
                Utils::showNoPermissionPage();
                die();
            }
            // end

			$thread = new Thread();
			$thread -> initWithId($thread_id);
			$post = $thread-> getPostById($post_id);

			if(!empty($_GET["confirm"]) && $_GET["confirm"]=="true"){
				// delete post, if current person is thread's host, delete thread as well



				if($post -> getAuthor() -> getId() == $_SESSION[KEY_SESSION][Account::KEY_ID]){

					$redirect_to = "/thread/";

					// it means host thread
					if($post -> isHost()){
						$thread->delete();
						$post -> delete();
					}else{
						$post -> delete();

						$latest_update = Post::getLastModifiedPost($thread_id) -> getModifiedTime();

						$dt = new DateTime();
						$dt -> setTimestamp($latest_update);

						$update_time = $dt->format("g:iA");
						$update_date = $dt->format("Y/m/d");

						$thread->updateUpdateTime($update_time, $update_date, $latest_update);

						$redirect_to .= $thread_id;
					}

					header("Location: ".$redirect_to);
					die();

				} else{
					// you are not the owner of the post, you don't have the permission to alter
					Utils::showNoPermissionPage();
					include VIEWS_PATH . "private-nav.php";
					include VIEWS_PATH . "thread/thread.php";

					die();
				}



			}else{
				// get request
				$thread -> initWithId($thread_id);
				$post = $thread -> getPostById($post_id);



			}


			$permission = $thread -> getPermission();
			if(!self::checkingPermission($thread_id, $post_id , $permission) || !($_SESSION[KEY_SESSION][Account::KEY_ID] == $post->getAuthor()->getId())){
				Utils::showNoPermissionPage();
	   			return ;
			}

			$content = "delete.php";

			include VIEWS_PATH."private-nav.php";


			include VIEWS_PATH . "thread/thread.php";
		}

		/*
		public static function create_comment($thread_id){



			$action = KEY_CREATE_COMMENT;
			$content = "edit.php";
			include VIEWS_PATH."private-nav.php";
			include VIEWS_PATH . "thread/thread.php";

		}*/

		// tools
		public static function checkingPermission($thread_id, $post_id , $permission){

			$permission = json_decode($permission, true);

			if(count($permission) == 0){
				return true;
			}

			return false;
		}

		// tools
		public static function extractArray($array, $offset, $len){
			$threads = array();
			$i = 0;
			$cnt = 0;
			foreach($array as $t){

				if($i >= $offset){
					if($cnt >= $len)
						break;

					$cnt = $cnt + 1;
					array_push($threads, $t);
				}

				$i = $i + 1;
			}
			return $threads;
		}
	}

	//
	// この関数は_list関数用の関数
	// pageNumber（HTML要素）を表示する関数
	function showPageBar($show = 10, $current_page, $len , $work = null){

		echo "<div class='clearfix'>";
		echo "<div class='page_bar'>";
		for($i = 1 ; $i < ($len / $show) + 1 ; $i ++){
			if($work != null){
				$work($i, $current_page);
			}
		}
		echo "<span class='page_number next'>ページへ&nbsp;&nbsp;（{$show}スレ表示）</span>";
		echo "</div>";
		echo "</div>";
	}
?>