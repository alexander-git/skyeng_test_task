<?php

namespace app\services\result;

use Yii;
use app\models\Result;

class ResultService {

    public function __construct() {
    
    }
    
    public function createResult($username, $points) {
        $result = new Result();
        $result->username = $username;
        $result->points = $points;
        if ($result->save() ) {
            return $result;
        } else {
            return -1;
        }
    }
    
    public function getResultsAsArray($limit = null, $offset = 0) {
        $q = Result::find()->asArray()->select(['username', 'points'])->orderBy(['points' => SORT_DESC]);
        if ($limit !== null) {
            $q->offset($offset)->limit($limit);
        } 
        return $q->all();
    }
    
    public function getResultsCount() {
        $db = Yii::$app->db;
        return intval($db->createCommand('SELECT COUNT(*) FROM {{%result}}')->queryScalar()); 
    }
    
}