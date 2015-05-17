<?php

namespace app\services\error;

use Yii;
use app\models\Error;
use app\services\SaveResult;

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
            return new SaveResult($error, true);
        } else {
            return new SaveResult($error, false);
        }
    }
    
    // Параметр $needCompositeErrors отвечает за то какие ошибки нужно выбрать.
    // Если он равен false, то выбираются слово и количество ошибок 
    // при переводе этого слова. Если $needCompositeErrors = true, то выбирается 
    // пара "слово-ответ пользователя" и количество случаев когда при переводе
    // этого слова был выбран именно такой неправильный ответ.
    public function getErrorsAsArray($needCompositeErrors = false, $limit = null, $offset = 0) {
        $db = Yii::$app->db;
        
        $limitCondition = "";
        if ($limit !== null) {
            $limitCondition = "LIMIT $offset, $limit";   
        }
        $select = "word";
        if ($needCompositeErrors) {
            $select .= ", answer";
        }
        
        $errors = $db->createCommand(
            "SELECT $select, COUNT(id) as quantity FROM {{%error}}". 
            "    GROUP BY $select ORDER BY quantity DESC, word ASC $limitCondition"
        )->queryAll();

        
        return $errors;
    }
    
    public function getErrorsCount($needCompositeErrors = false) {
        $db = Yii::$app->db;
        $distinctCondition = "DISTINCT word";
        if ($needCompositeErrors) {
            $distinctCondition .= ", answer";
        }
        return intval($db->createCommand("SELECT COUNT($distinctCondition) FROM {{%error}}")->queryScalar());  
    }
    
}