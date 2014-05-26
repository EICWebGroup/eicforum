<?php

	function getAccount(){
		$account = new Account();
		if(!empty($_GET[KEY_TARGET])){
			$account->initWithId($_GET[KEY_TARGET]);
		}else{
			$account->initWithId($_SESSION[KEY_SESSION][Account::KEY_ID]);
		}
		return $account;
	}

	class AccountController{

		/////////////////////
		public static function show(){


			$account = getAccount();

			$owner_profile = false;
			if($account->getId() == $_SESSION[KEY_SESSION][Account::KEY_ID]){
				$owner_profile = true;
			}

			include "private.php";
			include VIEWS_PATH."private-nav.php";
			include VIEWS_PATH . "account/show.php";
		}

		/////////////////////
		public static function login(){
			include "login.php";
			include VIEWS_PATH . "account/login.php";
		}

        public static function create(){

            $error_message = "";
            $studentId = "";
            $username = "";
            $password = "";
            $nickname = "";

            if(!empty($_POST)){

                $studentId  = $_POST["studentid"];
                $username   = $_POST["username"] ;
                $password   = $_POST["password"];
                $repeatPassword = $_POST["repeat_password"];
                $nickname   = $_POST["nickname"];


                // 確認処理ー
                if(empty($studentId)){
                    $error_message .= "<li>学生番号を空白にしないでください。</li>";
                }else if(preg_match("/^j/", $studentId)){
                    $error_message .= "<li>英文字 j はいりません。数字のみです。</li>";
                }else if(!preg_match("/^[0-9]{7}$/", $studentId)){
                    $error_message .= "<li>正しい学籍番号を入力してください。</li>";
                }else if(!in_array($studentId,getAllowableStudentIdList())){
                    $error_message .= "<li>この学籍番号は使えません。</li>";
                }

                if(empty($username)){
                    $error_message .= "<li>ログイン名を空白にしないでください。</li>";
                }else if(!preg_match("/^[0-9a-zA-Z]+$/", $username)){
                    $error_message .= "<li>ログイン名は英数字以外の文字は受け付けません。[@_?,.]などの文字も使えません。</li>";
                }else if(Account::duplicateUsername($username)){
                    $error_message .= "<li>ログイン名はすでに使われました。別のログイン名にしてください。</li>";
                }

                if(empty($password)){
                    $error_message .= "<li>パスワードを空白にしないでください。</li>";
                }

                if(empty($repeatPassword)){
                    $error_message .= "<li>再確認パスワードを空白にしないでください。</li>";
                }else if($repeatPassword != $password){
                    $error_message .= "<li>パスワードと再確認のパスワードは一致しません。</li>";
                }

                if(empty($nickname)){
                    $error_message .= "<li>表示名を空白にしないでください。</li>";
                }

                // there is no error, and success to create a new account
                if(strlen($error_message) == 0){

                    $salt = Utils::generateSalt();
                    $encrypted_password = Utils::encrpytPassword($password, $salt);

                    // verify campus by
                    if(preg_match("/^[8]/", $studentId)){
                        $campus = "葛飾";
                    }else if(preg_match("/^[67]/", $studentId)){
                        $campus = "野田";
                    }else{
                        $campus = "謎";
                    }

                    $validate_code = Utils::generateValidationCode();

                    $new_account_id = Account::createAccount($username, $encrypted_password, $nickname, $salt, $validate_code, $studentId, $campus );

                    $mail_content = "下記のアカウントを作成しました。\n　ログイン名：　{$username}\n パスワード：　{$password}\n次のリンクをクリックして認証が自動に行います。";

                    self::sendMail($new_account_id,$studentId,$validate_code,$mail_content);

                    header("Location: /account/verifyplease?accountid=".$new_account_id);
                    die();
                }
            }

            $content = "create.php";
            include VIEWS_PATH . "account/public.php";
        }

        // tools for create method
        private static function sendMail($_id, $student_id, $validate_code,$msg_before_encoded){
            $validate_url = "http://eicforum.mydns.jp/account/verifyplease?request=validate&accountid={$_id}&studentid={$student_id}&validate={$validate_code}";
            $subject = mb_convert_encoding("【EIC掲示板】認証メール", "Shift-JIS","UTF-8");
            $message = mb_convert_encoding($msg_before_encoded."\r\n\r\n".$validate_url, "Shift-JIS","UTF-8");

            Utils::mailSend($student_id, $subject, $message);
        }

        public static function verifyPlease(){

            $accountId = $_GET["accountid"];
            $noticemessage = "";

            if(!empty($_GET["request"])){
                $account = new Account();
                $account->initWithId($accountId);
                $username = $account->getUsername();

                $studentId = $_GET["studentid"];
                $validate_code = $_GET["validate"];


                if($_GET["request"] == "resend"){

                    $mail_content = "下記のアカウントを作成しました。\n　ログイン名：　{$username}\n 次のリンクをクリックして認証が自動に行います。";
                    self::sendMail($account->getId(), $account->getStudentId(), $account->getValidationCode() ,$mail_content);

                    $noticemessage = "<h3>再送信しました</h3>";

                }else if($_GET["request"] == "validate"){

                    if($account->getValidationCode() == $validate_code && $account->getStudentId() == $studentId){

                        $account->updateValidate();

                        header("Location: /account/verifydone");
                        die();
                    }
                }
            }


            $content = "verify_please.php";
            include VIEWS_PATH . "account/public.php";
        }

        public static function verifyDone(){
            $content = "verify_done.php";
            include VIEWS_PATH . "account/public.php";
        }

        public static function forgetPassword(){

            $error_message = "";

            if(!empty($_POST)){

                if(!empty($_POST["studentid"])){

                    $studentId = $_POST["studentid"];
                    if(empty($studentId)){
                        $error_message .= "<li>学籍番号を空白にしないでください。</li>";
                    }else if(!preg_match("/^[0-9]{7}$/", $studentId)){
                        $error_message .= "<li>学籍番号は正しくありません。</li>";
                    }else if(!in_array($studentId,getAllowableStudentIdList())){
                        $error_message .= "<li>この学籍番号は使えません。</li>";
                    }

                    // if success continue to process forgetpassowrd (Student Id)
                    if(strlen($error_message) == 0){

                        try{
                            $account = Account::query("SELECT * FROM account_list WHERE ".Account::KEY_STUDENTID."='{$studentId}' LIMIT 1");
                            $validate_code = Utils::generateValidationCode();
                            $studentId = $account->getStudentId();

                            TempValidationCode::create($studentId, $validate_code);

                            self::sendForgetPasswordMail($account->getId(), $studentId, $validate_code);

                            $content = "forgetpassword_mailsent.php";
                        } catch (Exception $ex) {
                            $content = "forgetpassword_username.php";
                        }

                    }else{
                        $content = "forgetpassword_studentid.php";
                    }
                    // end of process forgetpassword (Student Id)

                }else if(!empty($_POST["username"])){
                    $username = $_POST["username"];
                    $studentId = $_POST["username_studentid"];
                    if(empty($username)){
                        $error_message .= "<li>ログイン名を空白にしないでください。</li>";
                    }


                    if(strlen($error_message) == 0){
                        $account = new Account();
                        $account->initWithUsername($username);

                        if($account->getUsername() != NULL){
                            // success to process forgetpassword (Username)
                            $account->setStudentId($studentId);

                            $validate_code = Utils::generateValidationCode();

                            TempValidationCode::create($studentId, $validate_code);

                            self::sendForgetPasswordMail($account->getId(), $studentId, $validate_code);

                            $content = "forgetpassword_mailsent.php";
                            include VIEWS_PATH . "account/public.php";
                            return ;

                        }else{
                            // failed to get username
                            $error_message .= "<li>正しいログイン名を入力してください。</li>";
                        }
                    }

                     $content = "forgetpassword_username.php";
                    // end to process forgetpassword (Username)
                }else{
                    $error_message .= "<li>学籍番号を空白にしないでください。</li>";
                    $content = "forgetpassword_studentid.php";
                }

            }else{
                // get request
                $content = "forgetpassword_studentid.php";
            }

            include VIEWS_PATH . "account/public.php";
        }

        // tools for forget password method
        private static function sendForgetPasswordMail($_id, $student_id, $validate_code){
            $msg_before_encoded = "下記のリンクにてパスワードをリセットしてください。\r\n"
                    . "なお、このメールは1時間の有効時間しかありません。その期間以内にパスワードをリセットしておかないと下記のリンクは無効になります。";

            $validate_url = "http://eicforum.mydns.jp/account/resetpassword?accountid={$_id}&validate={$validate_code}";
            $subject = mb_convert_encoding("【EIC掲示板】パスワードリセット", "Shift-JIS","UTF-8");
            $message = mb_convert_encoding($msg_before_encoded."\r\n\r\n".$validate_url, "Shift-JIS","UTF-8");

            Utils::mailSend($student_id, $subject, $message);

        }

        public static function resetPassword(){

            $error_message = "";
            $password = "";


            if(!empty($_GET)){

                $validate_code = $_GET["validate"];
                $accountId = intval($_GET["accountid"]);

                try{
                    $t = new TempValidationCode();
                    $t->initWithValidationCode($validate_code);

                    $account = new Account();
                    $account->initWithId($accountId);

                    if($account->getStudentId() != $t->getStudentId() || $t->isExpired()){
                        throw new Exception("");
                    }else{
                        // allow to reset password
                    }

                } catch (Exception $ex) {
                    // fail to validate
                    if($t->isExpired()){
                        $content = "forgetpassword_expired.php";
                        include VIEWS_PATH . "account/public.php";
                        return;
                    }else{
                        header("Location: /");
                        die();
                    }
                }
            }else{
                // fail to validate
                header("Location: /");
                die();
            }

            if(!empty($_POST)){
                $password = $_POST["password"];
                $confirm_password = $_POST["password-confirm"];

                if(empty($password)){
                    $error_message .= "<li>パスワードを空白にしないでください。</li>";
                }

                if(empty($confirm_password)){
                    $error_message .= "<li>再確認のパスワードを空白にしないでください。</li>";
                }else if($confirm_password != $password){
                    $error_message .= "<li>パスワードと再確認のパスワードは一致しません。</li>";
                }


                if(strlen($error_message) == 0){
                    // success and reset password
                    $account = new Account();
                    $account->initWithId($accountId);
                    $account->resetPassword($password);

                    $t = new TempValidationCode();
                    $t->initWithValidationCode($validate_code);
                    $t->validate();

                    header("Location: /account/resetpassworddone");
                    die();
                }
            }

            $content = "forgetpassword_resetpassword.php";
            include VIEWS_PATH . "account/public.php";
        }

        public static function resetPasswordDone(){
            $content = "forgetpassword_done.php";
            include VIEWS_PATH . "account/public.php";
        }

		/////////////////////
		public static function edit(){

			$account = new Account();
			$account -> initWIthId($_SESSION[KEY_SESSION][Account::KEY_ID]);

			//////////////////////////////////////////////////////////////////////
			//
			//  Request of アカウントのプロフィール編集
			//
			//////////////////////////////////////////////////////////////////////
			if(!empty($_POST) && $_POST["action"] == "edit_account"){

				$nickname	= $_POST["nickname"];
				$grade		= $_POST["grade"];
				$department	= $_POST["department"];
				$major		= $_POST["major"];
				$blog		= $_POST["blog"];
				$mail		= $_POST["mail"];
				$other_link = $_POST["others_link"];
				$comment	= $_POST["comment"];
				$position	= $_POST["position"];

				if(empty($nickname)){

					$nickname_error = "名前を空白にしないでください。";
					$updated_message = "プロフィールの変更が失敗しました。原因は下記に書いてあります。";
					$success = false;
				}else{
					// update profile if no error
					$success = $account-> updateProfile(
											$nickname,
											$grade,
											$department,
											$major,
											$blog,
											$mail,
											$other_link,
											$comment,
											$position
										);
					if($success)
						$updated_message = "プロフィールが変更しました。";
					else
						$updated_message = "予想外のエラーが発生しました。プロフィールの変更が失敗しました。";
				}
			}else{

				$nickname	= $account->getNickname();
				$grade		= $account->getGrade();
				$department	= $account->getDepartment();
				$major		= $account->getMajor();
				$blog		= $account->getBlog();
				$mail		= $account->getMail();
				$other_link = $account->getOtherLink();
				$comment	= $account->getComment();
				$position	= $account->getPosition();

			}

			$owner_profile = true;

			include "private.php";
			include VIEWS_PATH."private-nav.php";
			include VIEWS_PATH . "account/edit.php";
		}

		public static function passwordEdit(){

			$old_password = "";
			$new_password = "";
			$new_password_reentry = "";

			//////////////////////////////////////////////////////////////////////
			//
			//  Request of アカウントのプロフィール編集
			//
			//////////////////////////////////////////////////////////////////////
			if(!empty($_POST) && $_POST["action"] == "edit_password"){
				$old_password		  = $_POST["old_password"];
				$new_password		  = $_POST["new_password"];
				$new_password_reentry = $_POST["new_password_reentry"];

				$account = new Account();
				$account->initWithId($_SESSION[KEY_SESSION][Account::KEY_ID]);



				$updated_message1 = "";
				$updated_message2 = "";
				$updated_message3 = "";

				//
				// 古いパスワードの確認
				$success = false;
				$old_password_correct = false;
				if( Utils::encrpytPassword($old_password , $account->getSalt())
					== $account->getEncryptedPassword()	){

					$old_password_correct = true;
				}else{
					$updated_message1 = "パスワードが間違いました。";
				}

				//
				//新しいパスワードパスワードの確認
				$new_password_correct = false;
				if(!empty($new_password) && preg_match('/[A-Za-z0-9]+/', $new_password)){
					$new_password_correct = true;
				}else{
					$updated_message2 = "半角英数字以外の文字を使用禁止になっています。";
				}


				//
				// 再確認用パスワードの確認
				$new_password_reentry_correct = false;
				if($new_password_correct){
					if($new_password == $new_password_reentry){
						$new_password_reentry_correct = true;
					}else{
						$updated_message3 = "新しいパスワードと違います。";
					}

					if(empty($new_password_reentry)){
						$new_password_reentry_correct = false;
						$updated_message3 = "この項目を空白にしないでください。";
					}
				}

				$old_password_correct= true;

				// 新しいパスワードの確認が完了、新しいパスワードへの書き込み
				if($new_password_correct && $old_password_correct && $new_password_reentry_correct){
					$success = true;
					$account -> resetPassword($new_password);
					$updated_message = "パスワードの変更ができました。";
				}

			}

			$owner_profile = true;

			include "private.php";
			include VIEWS_PATH."private-nav.php";
			include VIEWS_PATH . "account/passwordedit.php";
		}

		/////////////////////
		public static function signout(){
			include "signout.php";
		}

	}
