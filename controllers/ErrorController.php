<?php

namespace app\controllers;

use Yii;
use yii\web\Response;
use app\services\error\ErrorService;
use app\helpers\PageCalcHelper;

class ErrorController extends \yii\web\Controller
{        
    const ERRORS_PER_PAGE = 10;
    
    public function actionGetErrors() {
        $params = json_decode(trim(file_get_contents('php://input') ), true);
        $page = $params['page'];
        
        Yii::error($page);
        
        $errorService = new ErrorService();
        $errorCount = $errorService->getErrorsCount();
        $numberPages = PageCalcHelper::getNumberPages($errorCount, self::ERRORS_PER_PAGE);
        $errors = $errorService->getErrorsAsArray(self::ERRORS_PER_PAGE, PageCalcHelper::getOffset($page, self::ERRORS_PER_PAGE) );
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'numberPages' => $numberPages,
            'errors' => $errors
        ];
    }
    
}
