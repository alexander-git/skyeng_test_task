<?php

namespace app\controllers;

use Yii;
use yii\web\Response;
use yii\helpers\Json;
use app\utils\Test;

class TestController extends \yii\web\Controller
{     
    public $layout = 'dictionaryApp';
    
    public function actionIndex() {
        return $this->render('index');
    }
    
    public function actionGetUser() {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (!$this->hasTest() ) {
            return null;
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
            throw new \yii\base\Exception();
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
            throw new \yii\base\Exception();  
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
            throw new \yii\base\Exception();
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
