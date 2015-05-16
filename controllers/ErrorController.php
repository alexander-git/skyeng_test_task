<?php

namespace app\controllers;

use Yii;
use yii\web\Response;
use yii\helpers\Json;
use app\services\error\ErrorService;
use app\helpers\PageCalcHelper;

class ErrorController extends \yii\web\Controller
{        
    const ERRORS_PER_PAGE = 10;
    
    public function actionGetErrors() {
        $params = Json::decode(trim(file_get_contents('php://input') ), true);
        $page = $params['page'];
        $type = $params['type'];
        if ($type === 'simple') {
            $needCompositeErrors = false;
        } else {
            $needCompositeErrors = true;
        }

        $errorService = new ErrorService();
        $errorCount = $errorService->getErrorsCount($needCompositeErrors);
        $numberPages = PageCalcHelper::getNumberPages($errorCount, self::ERRORS_PER_PAGE);
        $offset = PageCalcHelper::getOffset($page, self::ERRORS_PER_PAGE);
        $errors = $errorService->getErrorsAsArray($needCompositeErrors, self::ERRORS_PER_PAGE,  $offset);
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'numberPages' => $numberPages,
            'errors' => $errors
        ];
    }
    
}
