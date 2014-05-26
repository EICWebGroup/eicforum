<?php

	class Post {

		const KEY_ID = "_id";
		const KEY_TITLE = "title";
		const KEY_CONTENT = "content";
		const KEY_DATE = "date";
		const KEY_TIME = "time";
		const KEY_AUTHOR = "author_id";
		const KEY_INPUT_TYPE = "input_type";
		const KEY_MODIFIED_TIME = "modified_milliseconds";
		const KEY_CREATED_TIME = "created_milliseconds";


		private $data;
		private $thread_id;

		public static function create($thread_id, $title, $content, $date, $time, $author_id, $input_type){

			$db = getDatabase();


			// (1) insert into post_22
			$dt = new DateTime();
			$current_timestamp = $dt->getTimestamp();

			$q = "INSERT INTO post_".$thread_id." (".
					self::KEY_TITLE.",".
					self::KEY_CONTENT.",".
					self::KEY_DATE.",".
					self::KEY_TIME.",".
					self::KEY_AUTHOR. ",".
					self::KEY_INPUT_TYPE. ",".
					self::KEY_MODIFIED_TIME. ",".
					self::KEY_CREATED_TIME. " ".

				")VALUES(".

					":title,".
					":content,".
					":date,".
					":time,".
					":author_id,".
					":input_type,".
					$current_timestamp.",".
					$current_timestamp." ".
				")";

			try{
				$stmt = $db->prepare($q);
				$stmt->bindParam(':title', $title);
				$stmt->bindParam(':content', $content);
				$stmt->bindParam(':date', $date);
				$stmt->bindParam(':time', $time);
				$stmt->bindParam(':author_id', $author_id);
				$stmt->bindParam(':input_type', $input_type);

				$stmt->execute();

				$lastInsertId = $db->lastInsertId(self::KEY_ID);

				// (2) update post_list update time & date
				$thread = new Thread();
				$thread->initWithId($thread_id);
				$thread->updateUpdateTime($time, $date, $current_timestamp);

				// (3) get the last insert post
				$post = new Post();
				$post->initWithId($thread_id, $lastInsertId );

				return $post;

			}catch(PDOException $ex){
				Utils::HandlePDOException($ex);
			}

			return null;

		}


		public static function getLastModifiedPost($thread_id){
			$db = getDatabase();
			$q = "SELECT * FROM post_{$thread_id} ORDER BY ".self::KEY_MODIFIED_TIME." DESC LIMIT 1";
			try{
				$stmt = $db->prepare($q);
				$stmt->execute();
			}catch(PDOException $ex){
				Utils::HandlePDOException($ex);
			}

			$post = new Post();
			$post->initWithVariable($thread_id, $stmt->fetch());
			return $post;
		}

	// fourth (final) task -> insert the thread into post created by user


		public function initWithId($thread_id, $post_id){
			$db = getDatabase();
			$q = "SELECT * FROM post_{$thread_id} where _id=:id LIMIT 1";
			try{
				$stmt = $db->prepare($q);
				$stmt->bindParam(':id',$post_id,PDO::PARAM_INT);
				$stmt->execute();
			}catch(PDOException $ex){
				Utils::HandlePDOException($ex);
			}

			$this->thread_id = $thread_id;
			$this->data = $stmt->fetch();

		}

		public function initWithVariable($thread_id, $var){
			$this->thread_id = $thread_id;
			$this->data = $var;
		}

		public function update($title, $content, $update_time, $update_date,$latest_update){

			$db = getDatabase();

            $q = "UPDATE post_".$this->thread_id." SET ".
            		self::KEY_TITLE. "=:title, ".
            		self::KEY_CONTENT . "=:content,".
            		self::KEY_TIME."='{$update_time}',".
            		self::KEY_DATE."='{$update_date}',".
            		self::KEY_MODIFIED_TIME."={$latest_update} ".
            		" WHERE ".
            		self::KEY_ID."=:id";

            try{

				// (1) update post
                $stmt = $db->prepare($q);
				$stmt->bindParam(':title', $title);
				$stmt->bindParam(':content', $content);
				$stmt->bindParam(':id', $this->data[self::KEY_ID]);
                $stmt->execute();



            }catch(PDOException $ex){
                Utils::HandlePDOException($ex);
            }



		}


		public function delete(){

			$db = getDatabase();
			$q = "DELETE FROM post_".$this->thread_id." WHERE _id=:id";
			try{
				$stmt = $db->prepare($q);
				$stmt->bindParam(':id',$this->data[self::KEY_ID],PDO::PARAM_INT);
				$stmt->execute();
			}catch(PDOException $ex){
				Utils::HandlePDOException($ex);
			}
		}

        public function getScriptText(){
            $html = str_get_html($this->data[self::KEY_CONTENT]);

            if(($element = $html->find("script",0)) != NULL){
                return $element->innertext;
            }

            return "";
        }

        public function setContent($content){
            $this->data[self::KEY_CONTENT] = $content;
        }

		public function getThreadId(){
			return $this->thread_id;
		}

		public function getPostId(){
			return $this->data[self::KEY_ID];
		}


		public function getTitle(){
			$temp = new Thread();
			$temp -> initWithId($this->thread_id);
			return $temp -> getTitle();
		}

		public function getContent(){
			return $this->data[self::KEY_CONTENT];
		}

		public function getAuthor(){
			$author = new Account();
			$author->initWithId( $this->data[self::KEY_AUTHOR] );
			return $author;
		}

		public function isHost(){
			return $this->data[self::KEY_ID] == 1;
		}

		public function getModifiedTime(){
			return $this->data[self::KEY_MODIFIED_TIME];
		}

		public function getCreatedTime(){
			return $this->data[self::KEY_CREATED_TIME];
		}


	}

?>