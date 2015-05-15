<?php

namespace app\services\word;

use Yii;
use app\models\Word;

class WordService {

    public function __construct() {
    
    }
    
    public function getRandomWord($exceptIds = null) {
        $db = Yii::$app->db;
        
        if ($exceptIds === null || empty($exceptIds) ) {
            $notInCondition = '';
        } else {
            $notInCondition = ' WHERE id NOT IN ('.$exceptIds[0];
            for ($i = 1; $i < count($exceptIds) ; $i++) {
                $notInCondition .= ', '.$exceptIds[$i];
            }
            $notInCondition .= ')';
        }
        
        $possibleIds = $db->createCommand('SELECT id FROM {{%word}}'.$notInCondition)->queryColumn();
        $min = 0;
        $max = count($possibleIds) - 1;
        $rand = mt_rand($min, $max);
        $id = $possibleIds[$rand];
        
        $word = Word::findOne($id);
        return $word;
    }
    
    public function getCount() {
        $db = Yii::$app->db;
        return intval($db->createCommand('SELECT COUNT(*) FROM {{%word}}')->queryScalar());
    }
    
}