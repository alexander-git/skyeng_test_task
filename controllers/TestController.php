<?php

namespace app\controllers;

use Yii;
use yii\web\Response;
use app\services\word\WordService;
use app\services\result\ResultService;

class TestController extends \yii\web\Controller
{
    // Стадии выполнения теста.
    const START_TEST_STATE = 0;
    const PROCESS_TEST_STATE = 1;
    const FINISH_TEST_STATE = 2;
    
    const ANSWERS_COUNT = 4; // Количество предлагаемых вариантов ответа.
    const MAX_POSSIBLE_ERRORS = 2; // Количество ошибок, которое может допустить пользователь.
    
    public $layout = 'dictionaryApp';
    
    public function actionIndex() {
        return $this->render('index');
    }
    
    public function actionGetUser() {
        $response = Yii::$app->response;
        $response->format = Response::FORMAT_JSON;
      
        $session = Yii::$app->session;
        //TODO# Разобраться с $session->isActive. 
        if (!$session->has('test') ) {
            return [];
        } else {
            return [
                'username' => $session['test']['username'] 
            ];
        }
    }
    
    public function actionStartTest() {
        $params = json_decode(trim(file_get_contents('php://input') ), true); //TODO# Исправить.

        $response = Yii::$app->response;
        $response->format = Response::FORMAT_JSON;
        $session = Yii::$app->session;
        
        $username = isset($params['username']) ? $params['username'] : null;
        if ($username === null) {
            throw new \yii\base\Exception();
        }
        
        $session->remove('test');
        $test = [];
        $test['username'] = $username;
        // Указываем, что нужно начать тест сначала и сгенерировать новый вопрос.
        $test['testState'] = self::START_TEST_STATE;
        $session['test'] = $test;
        
        return ['success' => true]; //TODO# Вынести в общее.
    }

    public function actionLogout() {
        $session = Yii::$app->session;
        $session->remove('test'); //TODO# Нужно ли это?
        $session->destroy();
        
        $response = Yii::$app->response;
        $response->format = Response::FORMAT_JSON;
        return ['success' => true]; //TODO# Вынести в общее.
    }
    
    public function actionGetTestData() {
        $session = Yii::$app->session;
        if (!$session->has('test') ) {
            throw new \yii\base\Exception();
        }
        
        if ($session['test']['testState'] === self::START_TEST_STATE) { // Нужно начать новый тест.
            $this->prepareNewTest();
        } 
        
        $test = $session['test'];
        $response = Yii::$app->response;
        $response->format = Response::FORMAT_JSON;
        
        if ($test['testState'] === self::FINISH_TEST_STATE) { 
            // Если тест завершён, то посылаем только финальную статистику.
            return [
                'testFinished' => true,
                'username' => $test['username'],
                'errorCount' => $test['errorCount'],
                'successCount' => $test['successCount']
            ];
        } else {
            return [
                'username' => $test['username'],
                'errorCount' => $test['errorCount'],
                'questionNumber' => $test['questionNumber'],
                'word' => $test['word'],
                'answers' => $test['answers'],
                'wrongAnswers' => $test['wrongAnswers']
            ];
        }
    }
    
    public function actionAnswer() {
        $params = json_decode(trim(file_get_contents('php://input') ), true); //TODO# Исправить.
        $session = Yii::$app->session;
        if (!$session->has('test') ) {
            throw new \yii\base\Exception();
        }
        $test = $session['test'];
        
        $response = Yii::$app->response;
        $response->format = Response::FORMAT_JSON;
       
        $userAnswer = $params['answer'];
        if ($userAnswer === $test['validAnswer'] ) {
            ++$test['successCount'];
            if ($test['questionCount'] === $test['successCount']) {
                $this->saveResult($test['username'], $test['successCount']);
                $test['alreadyUsedWordIds'] []= $test['idWord'];
                
                $test['testState'] = self::FINISH_TEST_STATE;
                $session['test'] = $test;
                return [
                    'username' => $test['username'],
                    'errorCount' => $test['errorCount'],
                    'successCount' => $test['successCount'],
                    'questionNumber' => $test['questionNumber'],
                    'word' => $test['word'],
                    'answers' => $test['answers'],
                    'wrongAnswers' => $test['wrongAnswers'],
                    'isLastAnswerValid' => true,
                    'testFinished' => true
                ];
            } else {
                ++$test['questionNumber'];
                $session['test'] = $test;
                $this->generateNewQuestion();
                return [
                    'username' => $test['username'],
                    'errorCount' => $test['errorCount'],
                    'questionNumber' => $test['questionNumber'],
                    'word' => $test['word'],
                    'answers' => $test['answers'],
                    'wrongAnswers' => $test['wrongAnswers'],
                    'isLastAnswerValid' => true //Отличие от результата в actionGetTestData.
                ];
            }
        } else {
            ++$test['errorCount'];
            $test['wrongAnswers'] []= $userAnswer;
            $this->saveError($test['word'], $userAnswer, $test['isEnglishWord']);
            
            if ($test['errorCount'] > self::MAX_POSSIBLE_ERRORS) {
                $this->saveResult($test['username'], $test['successCount']);
                
                $test['testState'] = self::FINISH_TEST_STATE;
                $session['test'] = $test;
                
                return [
                    'username' => $test['username'],
                    'errorCount' => $test['errorCount'],
                    'successCount' => $test['successCount'],
                    'questionNumber' => $test['questionNumber'],
                    'word' => $test['word'],
                    'answers' => $test['answers'],
                    'wrongAnswers' => $test['wrongAnswers'],
                    'isLastAnswerValid' => false,
                    'testFinished' => true
                ];
            } else {
                $session['test'] = $test;
                
                return [
                    'username' => $test['username'],
                    'errorCount' => $test['errorCount'],
                    'successCount' => $test['successCount'],
                    'questionNumber' => $test['questionNumber'],
                    'word' => $test['word'],
                    'answers' => $test['answers'],
                    'wrongAnswers' => $test['wrongAnswers'],
                    'isLastAnswerValid' => false
                ];
                
            }
        }    
    }
    
    private function prepareNewTest() {
        $session = Yii::$app->session;
        $wordService = new WordService();
    
        $test = $session['test'];
        $test['errorCount'] = 0;
        $test['successCount'] = 0;
        $test['questionCount'] = $wordService->getCount();
        $test['questionNumber'] = 1;
        $test['alreadyUsedWordIds'] = [];
        $test['testState'] = self::PROCESS_TEST_STATE; // Отмечаем, что тест начался. 
        $session['test'] = $test;

        $this->generateNewQuestion(); //TODO# обязательно переписать. 
    }
    
    // Генерирует для нового вопроса и сохраняет их в сессии. 
    private function generateNewQuestion() {
        $session = Yii::$app->session;

        $wordService = new WordService();
        $test = $session['test'];
        $notAllowedWordIds = $test['alreadyUsedWordIds'];
        $word = $wordService->getRandomWord($notAllowedWordIds);
                
        // Нужно будет выбрать перевод для английского или для русского слова?
        $isEnglishWord =  (mt_rand(0, 1) === 0);
        // Запоминаем правильный вариант.
        if ($isEnglishWord) {
            $validAnswer = $word->rus;
        } else {
            $validAnswer = $word->eng;
        }
        
        // Создаём варианты ответа. Хранит только переводы.
        $answers = []; 
        $answers [] = $validAnswer; 
        $notAllowedAnswerIds = [];
        $notAllowedAnswerIds []= $word->id;
        for ($i = 1; $i <= self::ANSWERS_COUNT - 1; $i++) {
            $answer = $wordService->getRandomWord($notAllowedAnswerIds); 
            Yii::error($answer->eng);
            if ($isEnglishWord) {
                $answers []= $answer->rus;
            } else {
                $answers []= $answer->eng;
            }
            $notAllowedAnswerIds []= $answer->id;
        }
        shuffle($answers);
        
        // Сохраняем сгенерированный вопрос в сессиии.
        if ($isEnglishWord) {
            $test['word'] = $word->eng;
        } else {
            $test['word'] = $word->rus;
        }
        $test['idWord'] = $word->id;
        $test['validAnswer'] = $validAnswer;
        $test['isEnglishWord'] = $isEnglishWord;
        $test['answers'] = $answers;
        $test['wrongAnswers'] = []; // Неправильные ответы уже выбранные пользователем.
        
        $session['test'] = $test;
    }
    
    private function saveResult($username, $points) {
        $resultService = new ResultService();
        $resultService->createResult($username, $points);
    }
    
    private function saveError($word, $answer, $isEnglishWord) {
        $errorService = new ErrorService();
        $errorService->createError($word, $answer, $isEnglishWord);
    }
    
    
    private function getAppDataFromSession() { //TODO# Начать использовать.
        $session = Yii::$app->session;
        if (!$session->has('test') ) {
            throw new \yii\base\Exception();
        }
        
        return $session['test'];
    }
    
    private function saveAppDataToSession($data) { //TODO# Начать использовать.
        $session = Yii::$app->session;
        $session['test'] = $data;
    }
    
    private function removeAppDataFromSession($data) {
        $session = Yii::$app->session;
        $session->remove('test');
        $session->destroy();
    }
       
}
