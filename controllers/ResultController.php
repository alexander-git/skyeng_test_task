<?php

namespace app\controllers;

use Yii;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\services\result\ResultService;
use app\helpers\PageCalcHelper;

class ResultController extends \yii\web\Controller
{         
    const RESULTS_PER_PAGE = 10;
    
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'get-results' => ['get'],
                ],
            ],
        ];
    }
    
    public function actionGetResults() {
        $page = Yii::$app->request->get('page');
        
        $resultService = new ResultService();
        $resultCount = $resultService->getResultsCount();
        $numberPages = PageCalcHelper::getNumberPages($resultCount, self::RESULTS_PER_PAGE);
        $offset = PageCalcHelper::getOffset($page, self::RESULTS_PER_PAGE);
        $results = $resultService->getResultsAsArray(self::RESULTS_PER_PAGE, $offset);
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'numberPages' => $numberPages,
            'results' => $results
        ];
    }
    
}