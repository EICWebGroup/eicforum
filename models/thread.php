<?php

	class Thread {

		public static $PERMISSIONS = array("private","public");

		const KEY_ID = "_id";
		const KEY_AUTHOR = "author_id";
		const KEY_TAG = "tag";
		const KEY_TITLE = "title";
		const KEY_UPDATE_TIME = "update_time";
		const KEY_UPDATE_DATE = "update_date";
		const KEY_UPDATE_MILLIS = "latest_update";
		const KEY_PERMISSION = "permission";
		const KEY_SPECIAL = "special";

		private $data;

		public static function query($q){
			$db = getDatabase();

			try{
				$stmt = $db->prepare($q);
				$stmt->execute();
			}catch(PDOException $ex){
				Utils::HandlePDOException($ex);
			}

			$result = array();
			foreach($stmt->fetchAll() as $thread){
				$temp = new Thread();
				$temp -> initWithVar($thread);
				array_push($result, $temp);
			}

			return $result;
		}

		public static function all($offset = 0, $row_count = 100){
			$db = getDatabase();
			$q = "SELECT * FROM post_list ORDER BY ".self::KEY_UPDATE_MILLIS." DESC LIMIT ".$offset.",".$row_count;
			try{
				$stmt = $db->prepare($q);
				$stmt->execute();
			}catch(PDOException $ex){
				Utils::HandlePDOException($ex);
			}

			$result = array();
			foreach($stmt->fetchAll() as $thread){
				$temp = new Thread();
				$temp -> initWithVar($thread);
				array_push($result, $temp);
			}

			return $result;
		}

		public static function allPermissionGiven($campus){
			$result = array();

			foreach(self::all() as $thread){

				if($thread -> getHost() -> getCampus() == $campus || $thread->getPermission() == "public"){
					array_push($result, $thread);
				}
			}

			return $result;
		}

		public static function create($host_id, $title, $update_time, $update_date, $latest_update, $content,$special, $permission){

			$db = getDatabase();

			// insert into post_list
			$q = "INSERT INTO post_list ( ".
					self::KEY_AUTHOR .",".
					self::KEY_TITLE . ",".
					self::KEY_UPDATE_TIME . "," .
					self::KEY_UPDATE_DATE .	"," .
					self::KEY_UPDATE_MILLIS . "," .
                    self::KEY_SPECIAL . "," .
					self::KEY_PERMISSION . " ".
					 ") VALUES (" .
					":host_id ,".
					":title ,".
					":update_time ,".
					":update_date ,".
					":latest_update ,".
                    ":special ,".
					":permission " .
					");";

			try {
				$stmt = $db->prepare($q);
				$stmt->bindParam(':host_id', $host_id);
				$stmt->bindParam(':title', $title);
				$stmt->bindParam(':update_time', $update_time);
				$stmt->bindParam(':update_date', $update_date);
				$stmt->bindParam(':latest_update', $latest_update);
                $stmt->bindParam(':special', $special);
				$stmt->bindParam(':permission', $permission);
				$stmt->execute();

			}catch(PDOException $ex){
				Utils::HandlePDOException($ex);
			}

			$last_created_id = 0;

			$last_created_id = $db->lastInsertId(self::KEY_ID);


			// third task -> create table after retrieved the latest table id
			$q = "CREATE TABLE post_" . $last_created_id . " ( ".
					Post::KEY_ID . " INT AUTO_INCREMENT NOT NULL PRIMARY KEY,".
					Post::KEY_TITLE . " VARCHAR(200),".
					Post::KEY_CONTENT . " TEXT,".
					Post::KEY_DATE . " VARCHAR(20),".
					Post::KEY_TIME . " VARCHAR(20),".
					Post::KEY_AUTHOR . " INT(200),".
					Post::KEY_INPUT_TYPE . " VARCHAR(200), ".
					Post::KEY_MODIFIED_TIME . " INT NOT NULL DEFAULT 0 , ".
					Post::KEY_CREATED_TIME . " INT NOT NULL DEFAULT 0 ".

					")";

			try{
				$stmt = $db->prepare($q);
				$stmt->execute();
			}catch(PDOException $ex){
				Utils::HandlePDOException($ex);
			}

			Post::create($last_created_id, $title, $content, $update_date, $update_time, $host_id, "text");

			return $last_created_id;
		}

		public function initWithId($id){
			$db = getDatabase();
			$q = "SELECT * FROM post_list where _id=:id LIMIT 1";
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

		public function update($post_id, $title, $update_time, $update_date, $latest_update, $content, $permission = "private"){
			$db = getDatabase();
			$q = "UPDATE post_list SET ".
				self::KEY_TITLE . "=:title,".
				self::KEY_UPDATE_TIME . "=:update_time,".
				self::KEY_UPDATE_DATE . "=:update_date,".
				self::KEY_UPDATE_MILLIS . "=:latest_update,".
				self::KEY_PERMISSION . "=:permission ".
				" where _id=:id LIMIT 1";

			try{
				$stmt = $db->prepare($q);
				$stmt->bindParam(':title',$title);
				$stmt->bindParam(':update_time',$update_time);
				$stmt->bindParam(':update_date',$update_date);
				$stmt->bindParam(':latest_update',$latest_update,PDO::PARAM_INT);
				$stmt->bindParam(':permission',$permission);

				$stmt->bindParam(':id',$this->data[self::KEY_ID],PDO::PARAM_INT);

				$stmt->execute();

			}catch(PDOException $ex){
				Utils::HandlePDOException($ex);

			}

			$this->getPostById($post_id)->update($title, $content, $update_time, $update_date,$latest_update);



		}

		public function updateUpdateTime($update_time, $update_date, $latest_update){
			$db = getDatabase();
			$q = "UPDATE post_list SET ".
				self::KEY_UPDATE_TIME . "=:update_time,".
				self::KEY_UPDATE_DATE . "=:update_date,".
				self::KEY_UPDATE_MILLIS . "=:latest_update ".
				" where _id=:id LIMIT 1";
			try{
				$stmt = $db->prepare($q);
				$stmt->bindParam(':update_time',$update_time);
				$stmt->bindParam(':update_date',$update_date);
				$stmt->bindParam(':latest_update',$latest_update,PDO::PARAM_INT);
				$stmt->bindParam(':id',$this->data[self::KEY_ID],PDO::PARAM_INT);
				$stmt->execute();
			}catch(PDOException $ex){
				Utils::HandlePDOException($ex);
			}
		}

		public function delete(){
			$db = getDatabase();
			$q = "DELETE FROM post_list WHERE _id=:id";
			try{
				$stmt = $db->prepare($q);
				$stmt->bindParam(':id',$this->data[self::KEY_ID],PDO::PARAM_INT);
				$stmt->execute();
			}catch(PDOException $ex){
				Utils::HandlePDOException($ex);
			}
		}

		public function getHost(){
			$acc = new Account();
			$acc->initWithId( $this->data[self::KEY_AUTHOR] );
			return $acc;
		}

		public function getPostById($post_id){
			$post = new Post();
			$post->initWithId(
					$this->data[self::KEY_ID],
					$post_id
				);
			return $post;
		}

		public function queryPosts($q){
			$db = getDatabase();

			$q = "SELECT * FROM post_{$this->data[self::KEY_ID]} ".$q;

			try{
				$stmt = $db->prepare($q);
				$stmt->execute();
			}catch(PDOException $ex){
				Utils::HandlePDOException($ex);
			}


			$posts = array();

			foreach( $stmt->fetchAll() as $p ){
				$temp = new Post();
				$temp -> initWithVariable($this->data[self::KEY_ID] ,$p);
				array_push($posts, $temp);
			}

			return $posts;
		}

		public function getAllPosts($offset = 0 , $row_count = 100){
			$db = getDatabase();
			$q = "SELECT * FROM post_{$this->data[self::KEY_ID]} ORDER BY ".Post::KEY_MODIFIED_TIME." DESC LIMIT ".$offset.",".$row_count;

			try{
				$stmt = $db->prepare($q);
				$stmt->execute();
			}catch(PDOException $ex){
				Utils::HandlePDOException($ex);
			}

			$posts = array();

			foreach( $stmt->fetchAll() as $p ){
				$temp = new Post();
				$temp -> initWithVariable($this->data[self::KEY_ID] ,$p);
				array_push($posts, $temp);
			}

			return $posts;
		}

		public function getId(){
			return $this->data[self::KEY_ID];
		}

		public function getTitle(){
			return $this->data[self::KEY_TITLE];
		}

		public function getUpdateTime(){
			return $this->data[self::KEY_UPDATE_TIME];
		}

		public function getUpdateDate(){
			return $this->data[self::KEY_UPDATE_DATE];
		}

		public function getUpdateTimeInMillis(){
			return $this->data[self::KEY_UPDATE_MILLIS];
		}

		public function getPermission(){
			return $this->data[self::KEY_PERMISSION];
		}

		public function getLastModifiedAuthor(){
			$thread_id = $this->getId();
			return Post::getLastModifiedPost($thread_id) -> getAuthor();
		}

		public function getSpecial(){
			return $this->data[self::KEY_SPECIAL];
		}

	}



?>
