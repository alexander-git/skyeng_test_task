<?php

namespace app\utils;

use app\services\word\WordService;
use app\services\error\ErrorService;
use app\services\result\ResultService;

use yii\helpers\ArrayHelper;

// Хранит состояние теста, генерирует новые вопросы и готовит эти данные
// в форме пригодной для отправке пользователю.
class Test {
    
    const ANSWERS_COUNT = 4; // Количество предлагаемых вариантов ответа.
    const MAX_POSSIBLE_ERRORS = 2; // Количество ошибок, которое может допустить пользователь.
    
    // Стадии выполнения теста.
    const START_TEST_STATE = 0;
    const PROCESS_TEST_STATE = 1;
    const FINISH_TEST_STATE = 2;
    
    private $username = null; // Имя пользователя.
    private $successCount = null; // Количество успешных ответов.
    private $errorCount = null; // Количество ошибок.
    private $questionCount = null; // Общее числов вопросов в тесте.
    private $testState = null; // Стадия выполнения теста.
    private $word = null; // Текущеее слово.
    private $wordId = null; // Id текущего слова.
    private $isEnglishWord = null; // Это английское или русское слово.
    private $validAnswer = null; // Правильный перевод.
    private $answers = null; // Сгенерированные варианты ответа.
    private $wrongAnswers = null; // Уже выбранные пользователем неправильные варианты ответа.
    private $alreadyUsedWordIds = null; // Слова уже использованные в тесте.
    
    public function __construct() {
        
    }
   
    public function start($username) {
        $this->username = $username;
        // Указываем, что нужно начать тест сначала и сгенерировать новый вопрос.
        $this->testState = self::START_TEST_STATE; 
    }
    
    public function getUsername() {
        return $this->username;
    }
    
    // Создаёт массив данных для отправки пользователю согласно текущей стадии выполнения теста.
    public function prepareData() {
        if($this->testState === self::START_TEST_STATE) { // Нужно начать новый тест.
            $this->prepareNewTest();
        } 
        
        if ($this->testState === self::FINISH_TEST_STATE) { 
            // Если тест завершён, то посылаем только финальную статистику.
            return ArrayHelper::merge(
                $this->createStatisticData(),
                $this->createTestStateData() 
            );
        } else {
            return ArrayHelper::merge(
                $this->createStatisticData(), 
                $this->createQuestionData()  
            );
        } 
    }
    
    public function isFinished() {
        return $this->testState === self::FINISH_TEST_STATE;
    }
    
    // Регистрирует ответ и возвращает данные для отправки пользователю.
    public function answer($userAnswer) {
        if ($this->validAnswer === $userAnswer) { // Ответ пользователя правильный.
            ++$this->successCount;
            $this->alreadyUsedWordIds []= $this->wordId; // Запоминаем слово как использованное.
            
            if ($this->questionCount  === $this->successCount) { // На все вопросы даны правильные ответы.
                $this->saveResult();
                $this->testState = self::FINISH_TEST_STATE;
                return ArrayHelper::merge(
                    $this->createStatisticData(),
                    $this->createQuestionData(),
                    $this->createLastAnswerData(true),
                    $this->createTestStateData() 
                );
            } else { // Иначе создадим ещё один вопрос.
                ++$this->questionNumber;
                $this->generateNewQuestion();
                return ArrayHelper::merge(
                    $this->createStatisticData(),
                    $this->createQuestionData(),
                    $this->createLastAnswerData(true) 
                );
                
            }
        } else { // Ответ пользователя неправильный.
            ++$this->errorCount;
            $this->wrongAnswers []= $userAnswer;
            $this->saveError($userAnswer);
            
            if ($this->errorCount > self::MAX_POSSIBLE_ERRORS) { // Исчерпаны все попытки.
                $this->saveResult();
                $this->testState = self::FINISH_TEST_STATE;
                return ArrayHelper::merge(
                    $this->createStatisticData(),
                    $this->createQuestionData(),
                    $this->createLastAnswerData(false),
                    $this->createTestStateData() 
                );
            } else { // Дать ещё одну попытку.
                return ArrayHelper::merge(
                    $this->createStatisticData(),
                    $this->createQuestionData(),
                    $this->createLastAnswerData(false)
                );
            }
        }
    }
    
    public function restartTest() {
        $this->testState = self::START_TEST_STATE;
        $this->prepareNewTest();

        return ArrayHelper::merge(
            $this->createStatisticData(), 
            $this->createQuestionData()  
        );  
    }
    

    private function prepareNewTest() {
        $wordService = new WordService();
 
        $this->errorCount = 0;
        $this->successCount = 0;
        $this->questionCount = $wordService->getCount();
        //$this->questionCount = 3;
        $this->questionNumber = 1;
        $this->alreadyUsedWordIds = [];
        $this->testState = self::PROCESS_TEST_STATE; // Отмечаем, что тест начался. 
        $this->generateNewQuestion();
    }
    
    // Генерирует для нового вопроса. 
    private function generateNewQuestion() {
        $wordService = new WordService();
        $randomWord = $wordService->getRandomWord($this->alreadyUsedWordIds);
                
        // Нужно будет выбрать перевод для английского или для русского слова?
        $this->isEnglishWord =  (mt_rand(0, 1) === 0);
        
        // Запоминаем слово и правильный перевод.
        $this->wordId = $randomWord->id;
        if ($this->isEnglishWord) {
            $this->word = $randomWord->eng;
            $this->validAnswer = $randomWord->rus;
        } else {
            $this->word = $randomWord->rus;
            $this->validAnswer = $randomWord->eng;
        }
        
        // Создаём варианты ответа. Храним только переводы.
        $this->answers = []; 
        $this->answers []= $this->validAnswer; 
        $notAllowedAnswerIds = [];
        $notAllowedAnswerIds []= $randomWord->id;
        for ($i = 1; $i <= self::ANSWERS_COUNT - 1; $i++) {
            $answer = $wordService->getRandomWord($notAllowedAnswerIds); 
            if ($this->isEnglishWord) {
                $this->answers []= $answer->rus;
            } else {
                $this->answers []= $answer->eng;
            }
            $notAllowedAnswerIds []= $answer->id;
        }
        shuffle($this->answers);
        
        
        // Пока неправильных ответов нет.
        $this->wrongAnswers = []; 
    }
    
    private function saveError($userAnswer) {
        $errorService = new ErrorService();
        $errorService->createError($this->word, $userAnswer, $this->isEnglishWord);
    }
    
    private function saveResult() {
        $points = $this->successCount;
        $resultService = new ResultService();
        $resultService->createResult($this->username, $points);
    }
    
    private function createQuestionData() {
        return [
            'questionNumber' => $this->questionNumber,
            'word' => $this->word,
            'isEnglishWord' => $this->isEnglishWord,
            'answers' => $this->answers,
            'wrongAnswers' => $this->wrongAnswers
        ];  
    }
    
    private function createStatisticData() {
        return [
            'errorCount' => $this->errorCount,
            'successCount' => $this->successCount
        ];
    }
    
    private function createTestStateData() {
        return [
            'testFinished' => $this->isFinished()
        ]; 
    }
    
    private function createLastAnswerData($isLastAnswerValid) {
        return [
            'isLastAnswerValid' => $isLastAnswerValid
        ]; 
    }
   
}
