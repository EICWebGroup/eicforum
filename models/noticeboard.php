<?php
	
	class NoticeBoard {
		
		const KEY_ID = "_id";
		const KEY_AUTHOR_ID = "author_id";
		const KEY_CONTENT = "content";
		const KEY_UPDATE_DATE = "update_date";
		const KEY_UPDATE_TIME = "update_time";
		const KEY_CHECKED = "checked";
		
		private $id;
		private $data;
		
		public static function create($author_id, $content){
			
			$db = getDatabase();
			
			$q = "INSERT INTO nortification_board (".
					self::KEY_AUTHOR_ID.",".
					self::KEY_CONTENT.",".
					self::KEY_UPDATE_DATE.",".
					self::KEY_UPDATE_TIME.
										
				")VALUES(".
					
					":author_id,".
					":content,".
					":date,".
					":time".					
				")";
			
			$date = date('Y/n/d');	
			$time = Utils::getCurrentTime();
				
			try{
				$stmt = $db->prepare($q);
				$stmt->bindParam(':author_id', $author_id);
				$stmt->bindParam(':content', $content);				
				$stmt->bindParam(':date', $date);				
				$stmt->bindParam(':time', $time);				
				
				$stmt->execute();
			}catch(PDOException $ex){
				Utils::HandlePDOException($ex);
			}
			
		}
		
		public static function all(){
			$db = getDatabase();
			$q = "SELECT * FROM nortification_board ORDER BY ".self::KEY_ID. " DESC ";
			try{
				$stmt = $db->prepare($q);				
				$stmt->execute();
			}catch(PDOException $ex){
				Utils::HandlePDOException($ex);		
			}
			
			$result = array();
			foreach($stmt->fetchAll() as $notice){
				$temp = new NoticeBoard();
				$temp -> initWithVar($notice);
				array_push($result, $temp);
			}
			
			return $result;
		}
		
		public static function AllChecked(){
			$db = getDatabase();
			$q = "SELECT * FROM nortification_board ORDER BY ".self::KEY_ID." DESC";
			try{
				$stmt = $db->prepare($q);				
				$stmt->execute();
			}catch(PDOException $ex){
				Utils::HandlePDOException($ex);		
			}
			
			$result = array();
			foreach($stmt->fetchAll() as $notice){
				$temp = new NoticeBoard();
				$temp -> initWithVar($notice);
				
				if($temp -> isChecked()){
					array_push($result, $temp);
				}
				
			}
			
			return $result;
		}
		
		public function initWithVar($var){
			$this->data = $var;
		}
		
		public function initWithId($id){
			$db = getDatabase();
			$q = "SELECT * FROM nortification_board where ".self::KEY_ID."=:id LIMIT 1";
			try{
				$stmt = $db->prepare($q);
				$stmt->bindParam(':id',$id,PDO::PARAM_INT);
				$stmt->execute();
			}catch(PDOException $ex){
				Utils::HandlePDOException($ex);		
			}
			
			$this->id = $id;
			$this->data = $stmt->fetch();
		}
		
		public function setChecked($checked){
			
			if($checked){
				$checked = 1;
			}else{
				$checked = 0;
			}
			
			$db = getDatabase();
			
			$q = "UPDATE nortification_board SET ".					
					self::KEY_CHECKED. 					
				"=".					
					":checked".
				" WHERE ".
					self::KEY_ID . 
				"=".
					$this->data[self::KEY_ID];
			
			$date = Utils::getCurrentDate();
			$time = Utils::getCurrentTime();
				
			try{
				$stmt = $db->prepare($q);				
				$stmt->bindParam(':checked', $checked);				
				$stmt->execute();
			}catch(PDOException $ex){
				Utils::HandlePDOException($ex);
			}
		}
		
		public function getId(){
			return $this->data[self::KEY_ID];
		}
		
		public function getAuthor(){
			$author = new Account();
			$author->initWithId($this->data[self::KEY_AUTHOR_ID]);
			return $author;
		}
		
		public function getContent(){
			return $this->data[self::KEY_CONTENT];
		}
		
		public function getUpdateDate(){
			return $this->data[self::KEY_UPDATE_DATE];
		}
		
		public function getUpdateTime(){
			return $this->data[self::KEY_UPDATE_TIME];
		}
		
		public function isChecked(){
			return $this->data[self::KEY_CHECKED] == 1;
		}
		
		public function throwData(){
			return $this->data;
		}
	}
?>