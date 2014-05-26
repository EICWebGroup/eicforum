<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of temp_validation_code
 *
 * @author LikWee-PC
 */
class TempValidationCode
{

    const KEY_ID = "_id";
    const KEY_CODE = "temp_validate_code";
    const KEY_STUDENT_ID = "student_id";
    const KEY_CREATED_TIME = "created_time";

    private $data;

    public static function create($studentId, $validate_code){
        $db = getDatabase();
        $q = "INSERT INTO temp_validation_table ("
                . self::KEY_CODE.","
                . self::KEY_STUDENT_ID .","
                . self::KEY_CREATED_TIME
                    . ") VALUES " .
                "("
                    . ":validate_code,"
                    . ":studentid, "
                    . ":create_time"
                . ")";
        try{
            $stmt = $db->prepare($q);
            $stmt->bindParam(":validate_code", $validate_code);
            $stmt->bindParam(":studentid", $studentId);
            $stmt->bindParam(":create_time", time());
            $stmt->execute();

            $lastInserted = $db->lastInsertId();

            $t = new TempValidationCode();
            $t ->initWithId($lastInserted);
            return $t;

        } catch (Exception $ex) {
            Utils::HandlePDOException($ex);
        }

        return null;
    }

    public function initWithId($id){
        $db = getDatabase();
        $q = "SELECT * FROM temp_validation_table where _id=:id LIMIT 1";
        try{
            $stmt = $db->prepare($q);
            $stmt->bindParam(':id',$id,PDO::PARAM_INT);
            $stmt->execute();
        }catch(PDOException $ex){
            Utils::HandlePDOException($ex);
        }

        $this->data = $stmt->fetch();

    }

    public function initWithValidationCode($validate_code){
        $db = getDatabase();

        $q = "SELECT * FROM temp_validation_table WHERE ".self::KEY_CODE."=:validate_code LIMIT 1;";

        try{
            $stmt = $db->prepare($q);
            $stmt->bindParam(":validate_code", $validate_code);
            $stmt->execute();
        } catch (Exception $ex) {
            Utils::HandlePDOException($ex);
        }
        if($stmt->rowCount() == 0){
            throw new Exception("Validate code not found ".$validate_code);
        }else{
            $this->data = $stmt->fetch();
        }
    }

    public function isExpired(){
        $date = intval($this->data[self::KEY_CREATED_TIME]);
        $now = time();
        return ($now - $date)/60 > 60;
    }

    public function validate(){
        $db = getDatabase();
        $q = "UPDATE temp_validation_table SET ".self::KEY_CREATED_TIME."=0 WHERE ".self::KEY_ID."=".$this->data[self::KEY_ID];
        try{
            $stmt = $db -> prepare($q);
            $stmt->execute();
        } catch (PDOException $ex) {
            Utils::HandlePDOException($ex);
        }
    }

    public function getStudentId(){
        return $this->data[self::KEY_STUDENT_ID];
    }

}
