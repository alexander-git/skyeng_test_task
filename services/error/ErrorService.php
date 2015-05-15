<?php

namespace app\services\error;

use Yii;
use app\models\Error;

class ErrorService {

    public function __construct() {
    
    }
    
    public function createError($word, $answer, $isEnglishWord) {
        $error = new Error();
        $error->word = $word;
        $error->answer = $answer;
        if ($isEnglishWord) {
            $error->type = Error::ENG_TYPE;
        } else {
            $error->type = Error::RUS_TYPE;
        }
        if ($error->save() ) {
            return $error;
        } else {
            return -1;
        }
    }
    
    public function getErrorsAsArray($limit = null, $offset = 0) {
        $db = Yii::$app->db;
        
        $limitCondition = "";
        if ($limit !== null) {
            $limitCondition = "LIMIT $offset, $limit";   
        }
        
        $errors = $db->createCommand(
            "SELECT word, count(id) as quantity FROM {{%error}}". 
            "    GROUP BY word ORDER BY quantity DESC $limitCondition"
        )->queryAll();

        
        return $errors;
    }
    
    public function getErrorsCount() {
        $db = Yii::$app->db;
        return intval($db->createCommand('SELECT COUNT(DISTINCT word) FROM {{%error}}')->queryScalar());  
    }
    
}