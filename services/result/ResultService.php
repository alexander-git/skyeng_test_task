<?php

namespace app\services\result;

use app\models\Result;

class ResultService {

    public function __construct() {
    
    }
    
    public function createResult($username, $points) {
        $result = new Result();
        $result->username = $username;
        $result->points = $points;
        if ($result->save() ) {
            return $result->id;
        } else {
            return -1;
        }
    }
    

    
}