<?php

namespace app\controllers;

use Yii;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\services\error\ErrorService;
use app\helpers\PageCalcHelper;

class ErrorController extends \yii\web\Controller
{        
    const ERRORS_PER_PAGE = 10;
    
    // Возможные значения для параметра type в методе actionGetErrors.
    const SIMPLE_TYPE_REQUEST_PARAM_VALUE = 'simple';
    const COMPOSITE_TYPE_REQUEST_PARAM_VALUE = 'composite';
    
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'get-errors' => ['get'],
                ],
            ],
        ];
    }
    
    public function actionGetErrors() {
        $request = Yii::$app->request;
        $page = $request->get('page');
        $type = $request->get('type');
        if ($type === self::SIMPLE_TYPE_REQUEST_PARAM_VALUE) {
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
