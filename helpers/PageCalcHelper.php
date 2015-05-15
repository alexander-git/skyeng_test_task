<?php

namespace app\helpers;

class PageCalcHelper {
    
    public static function getOffset($page, $itemsPerPage) {
        return ($page - 1) * $itemsPerPage;
    }
    
    public static function getNumberPages($itemsCount, $itemsPerPage) {
        return ceil($itemsCount / $itemsPerPage);
    }
    
    private function __construct() {
         
    }
}
