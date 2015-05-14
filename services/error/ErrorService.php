<?php

namespace app\services\error;

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
            return $error->id;
        } else {
            return -1;
        }
    }
    
}