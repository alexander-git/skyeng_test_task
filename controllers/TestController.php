<?php

namespace app\controllers;

use Yii;
use yii\web\Response;
use yii\filters\VerbFilter;
use yii\helpers\Json;
use app\utils\Test;
use app\exceptions\LogicException;


class TestController extends \yii\web\Controller
{     
    public $layout = 'dictionaryApp';
    
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'get-user' => ['get'],
                    'start-test' => ['post'],
                    'logout' => ['get'],
                    'get-test-data' => ['get'],
                    'answer' => ['post'],
                    'restart-test' => ['get']
                ],
            ],
        ];
    }
    
    public function actionIndex() {
        return $this->render('index');
    }
    
    public function actionGetUser() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (!$this->hasTest() ) {
            return [];
        } else {
            return [
                'username' => $this->getTest()->getUsername() 
            ];
        }
    }
    
    public function actionStartTest() {
        $params = Json::decode(trim(file_get_contents('php://input') ), true);
        $username = isset($params['username']) ? $params['username'] : null;
        if ($username === null) {
            throw new LogicException();
        }
        
        $this->removeTest();
        $test = new Test();
        $test->start($username);
        $this->saveTest($test);
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ['success' => true];
    }

    public function actionLogout() {
        $this->removeTest();
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        return ['success' => true];
    }
    
    public function actionGetTestData() {
        $test = $this->getTest();
        $responseData = $test->prepareData();
        $this->saveTest($test);
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $responseData;
    }
    
    public function actionAnswer() {
        $params = Json::decode(trim(file_get_contents('php://input') ), true);
        
        $test = $this->getTest();
        if ($test->isFinished() ) {
            throw new LogicException();  
        }

        $responseData = $test->answer($params['answer']);
        $this->saveTest($test);
        
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $responseData;
    }
    
    public function actionRestartTest() {
        $test = $this->getTest();
        $responseData = $test->restartTest();
        $this->saveTest($test);

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $responseData;
    }
    
    // Функции для работы с объектом теста в сессии.
    ////////////////////////////////////////////////////////////////////////////
    
    private function hasTest() {
        return Yii::$app->session->has('test');
    }
    
    private function getTest() { 
        $session = Yii::$app->session;
        if (!$session->has('test') ) {
            throw new LogicException();
        }     
        return $session['test'];
    }
    
    private function saveTest($test) { 
        Yii::$app->session['test'] = $test;
    }
    
    private function removeTest() {
        $session = Yii::$app->session;
        $session->remove('test');
        $session->destroy();
    }
       
}
