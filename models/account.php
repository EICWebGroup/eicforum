<?php

	class Account{

		const KEY_ID = "_id";
		const KEY_USERNAME = "username";
		const KEY_ENCRPYPED_PASSWORD = "password";
		const KEY_NICKNAME = "nickname";
		const KEY_GRADE = "grade";
		const KEY_DEPARTMENT = "department";
		const KEY_MAJOR = "major";
		const KEY_BLOG = "blog_link";
		const KEY_MAIL = "mail_link";
		const KEY_OTHER_LINK = "other_link";
		const KEY_COMMENT = "comment";
		const KEY_POSITION = "position";
		const KEY_SALT = "salt";
		const KEY_ADMIN = "_admin";
		const KEY_COOKIE_LOCK = "cookie_lock";
		const KEY_VALIDATE_CODE = "validate_code";
		const KEY_VALIDATED = "validated";
		const KEY_STUDENTID = "student_id";
		const KEY_CAMPUS = "campus";

		private $data;


		public static function createAccount($username, $encrypted_password, $nickname, $salt,$validate_code, $student_id, $campus ){
            $db = getDatabase();
            $q = "INSERT INTO account_list (username,password,nickname,salt,validate_code,validated,student_id,campus) VALUES( :username,:encrypted_password,:nickname,:salt,:validate_code,0,:studentid, :campus)";

            $query_params = array(
                    ':username' => $username,
                    ':encrypted_password' => $encrypted_password,
                    ':nickname' => $nickname,
                    ':salt' => $salt,
                    ':validate_code' => $validate_code,
                    ':studentid' => $student_id,
                    ':campus' => $campus
            );

            try{
                $stmt = $db->prepare($q);
                $stmt->execute($query_params);
                return $db->lastInsertId();
            }catch(PDOException $ex){
                Utils::HandlePDOException($ex);
            }
            return 0;
		}

        public static function query($q){
			$db = getDatabase();

			try{
				$stmt = $db->prepare($q);
				$stmt->execute();
			}catch(PDOException $ex){
				Utils::HandlePDOException($ex);
			}

            $len = $stmt->rowCount();
            if($len <= 0){
                throw new Exception("no item");
            }else if($len == 1){
                $temp = new Account();
                $temp ->initWithVar($stmt->fetch());
                return $temp;
            }else{
                $result = array();
                foreach($stmt->fetchAll() as $thread){
                    $temp = new Account();
                    $temp -> initWithVar($thread);
                    array_push($result, $temp);
                }

                return $result;

            }
		}

        public static function duplicateUsername($username){

            $db = getDatabase();
            $q = "SELECT ".self::KEY_USERNAME." FROM account_list WHERE ".self::KEY_USERNAME."='$username'";
            try{
                $stmt = $db->prepare($q);
                $stmt->execute();
            } catch (PDOException $ex) {
                Utils::HandlePDOException($ex);
            }

            return $stmt->rowCount() > 0;

        }

		public function initWithUsername($username){
			$db = getDatabase();
			$q = "SELECT * FROM account_list where ".self::KEY_USERNAME."=:username LIMIT 1";
			try{
				$stmt = $db->prepare($q);
				$stmt->bindParam(':username',$username,PDO::PARAM_INT);
				$stmt->execute();
			}catch(PDOException $ex){
				Utils::HandlePDOException($ex);
			}

			$this->data = $stmt->fetch();
		}

		public function initWithId($id){
			$db = getDatabase();
			$q = "SELECT * FROM account_list where ".self::KEY_ID."=:id LIMIT 1";
			try{
				$stmt = $db->prepare($q);
				$stmt->bindParam(':id',$id,PDO::PARAM_INT);
				$stmt->execute();
			}catch(PDOException $ex){
				Utils::HandlePDOException($ex);
			}

			$this->data = $stmt->fetch();
		}

        public function initWithVar($var){
			$this->data = $var;
		}

		//update
		public function updateProfile(
				$nickname,
				$grade,
				$department,
				$major,
				$blog,
				$mail,
				$other_link,
				$comment,
				$position
			){

				$db = getDatabase();
				$q = "UPDATE account_list SET ".
					self::KEY_NICKNAME . "=:nickname ,".
					self::KEY_GRADE . "=:grade , ".
					self::KEY_DEPARTMENT . "=:department , ".
					self::KEY_MAJOR . "=:major , ".
					self::KEY_BLOG . "=:blog , ".
					self::KEY_MAIL . "=:mail , ".
					self::KEY_OTHER_LINK . "=:other_link , ".
					self::KEY_COMMENT . "=:comment , ".
					self::KEY_POSITION . "=:position ".
					" WHERE ".self::KEY_ID."=:id ";
				try{
					$stmt = $db->prepare($q);
					$stmt->bindParam(':nickname',$nickname);
					$stmt->bindParam(':grade',$grade);
					$stmt->bindParam(':department',$department);
					$stmt->bindParam(':major',$major);
					$stmt->bindParam(':blog',$blog);
					$stmt->bindParam(':mail',$mail);
					$stmt->bindParam(':other_link',$other_link);
					$stmt->bindParam(':comment',$comment);
					$stmt->bindParam(':position',$position);

					$stmt->bindParam(':id',$this->data[self::KEY_ID],PDO::PARAM_INT);
					$stmt->execute();
					return true;
				}catch(PDOException $ex){
					Utils::HandlePDOException($ex);
					return false;
				}
		}

        public function updateValidate(){
            $db = getDatabase();

            $q = "UPDATE account_list SET ".
                    self::KEY_VALIDATED . "=1 " .
                    "WHERE ".
                    self::KEY_ID . "=". $this->data[self::KEY_ID];

            try{
                $stmt = $db->prepare($q);
                $stmt->execute();
            } catch (PDOException $ex) {
                Utils::HandlePDOException($ex);
            }

        }

		public function resetCookieLock(){
			$db = getDatabase();
			$new_cookie_key =  dechex(mt_rand(0, 2341)) . dechex(mt_rand(0, 1643));

			$q = "UPDATE account_list SET {self::KEY_COOKIE_LOCK}='{$new_cookie_key}' WHERE {self::KEY_ID}={$this->data[self::KEY_ID]} LIMIT 1";
			try{
				$stmt = $db->prepare($q);
				$stmt->execute();
			}catch(PDOException $ex){
				Utils::HandlePDOException($ex);
			}
		}

		public function resetPassword($new_password){
			$hash_key = getHashKey();
			$db = getDatabase();

			$salt =  dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647));

			$new_encrypted_password = Utils::encrpytPassword($new_password , $salt);

			$q = "UPDATE account_list SET " . self::KEY_ENCRPYPED_PASSWORD . "='{$new_encrypted_password}' , ".
					self::KEY_SALT . "='{$salt}' WHERE ".
					self::KEY_ID . "=:id LIMIT 1";

			$params = array(":id" => $this -> data[self::KEY_ID]);
			try{
				$stmt = $db->prepare($q);
				$stmt->execute($params);
			}catch(PDOException $ex){
				Utils::HandlePDOException($ex);
			}
		}

		public function setStudentId($studentId){
            $db = getDatabase();
            $q = "UPDATE account_list SET ".self::KEY_STUDENTID."=:student_id WHERE ".self::KEY_ID."=".$this->data[self::KEY_ID]." LIMIT 1";

            try{

                $stmt = $db->prepare($q);
                $stmt->bindParam(":student_id", $studentId);
                $stmt->execute();

            } catch (PDOException $ex) {
                Utils::HandlePDOException($ex);
            }
        }

		public function getId(){
			return $this->data[self::KEY_ID];
		}

		public function getUsername(){
			return $this->data[self::KEY_USERNAME];
		}

		public function getEncryptedPassword(){
			return $this->data[self::KEY_ENCRPYPED_PASSWORD];
		}

		public function getNickname(){
			return $this->data[self::KEY_NICKNAME];
		}

		public function getGrade(){
			return $this->data[self::KEY_GRADE];
		}

		public function getDepartment(){
			return $this->data[self::KEY_DEPARTMENT];
		}

		public function getMajor(){
			return $this->data[self::KEY_MAJOR];
		}

		public function getBlog(){
			return $this->data[self::KEY_BLOG];
		}

		public function getMail(){
			return $this->data[self::KEY_MAIL];
		}

		public function getOtherLink(){
			return $this->data[self::KEY_OTHER_LINK];
		}

		public function getComment(){
			return $this->data[self::KEY_COMMENT];
		}

		public function getPosition(){
			return $this->data[self::KEY_POSITION];
		}

		public function getSalt(){
			return $this->data[self::KEY_SALT];
		}

		public function isAdmin(){
			return $this->data[self::KEY_ADMIN] == 1;
		}

		public function getCookieLock(){
			return $this->data[self::KEY_COOKIE_LOCK];
		}

		public function getValidationCode(){
			return $this->data[self::KEY_VALIDATE_CODE];
		}

		public function isValidated(){
			return $this->data[self::KEY_VALIDATED] == 1;
		}

		public function getStudentId(){
			return $this->data[self::KEY_STUDENTID];
		}

		public function getCampus(){
			return $this->data[self::KEY_CAMPUS];
		}

		public function throwData(){
			return $this->data;
		}

	}

?>