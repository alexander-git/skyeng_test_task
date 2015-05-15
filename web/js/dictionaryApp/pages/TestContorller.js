(function() {
    
    var _requestExecuted = false;  
    var _testFinished = false;
    
    var AnswerItem = function(text, isWrong) {
        this.text = text;
        this.isWrong = isWrong;
    };
    
    
    var injectParams = [
        '$scope', 
        'BackendService',
        'InfoService'
    ];
     
    var TestController = function(
        $scope, 
        BackendService, 
        InfoService
    ) 
    {
        repaint();
        
        $scope.restartTest = function() {
            if (_requestExecuted) {
                return;
            }
            beforeAjaxRequest();
            var restartTestResult = BackendService.restartTest();
            
            restartTestResult.success(function(data, status, headers, config) {
                restartTestSuccess(data);
            }).error(function(data, status, headers, config) {
                restartTestError();
            });
            
        };
        
        $scope.selectAnswer = function(answerText) {
            if (_requestExecuted || _testFinished) {
                return;
            }
            
            beforeAjaxRequest();
            var selectAnswerResult = BackendService.answer(answerText);

            selectAnswerResult.success(function(data, status, headers, config) {
                selectAnswerSuccess(data);
            }).error(function(data, status, headers, config) {
                selectAnswerError();
            });
        };
        
        
        function repaint() {
            var testData = InfoService.getTestData();
            repaintTestState(testData);
            showQuestionIfNeed(testData);
            showFinishStatistiIfNeed(testData);
        }
                
                
        function beforeAjaxRequest() {
            $scope.needShowProcess = true;
            _requestExecuted = true;
        }

        function afterAjaxRequest() {
            $scope.needShowProcess = false;
            _requestExecuted = false;
        }

        function showQuestionIfNeed(testData) {
            var needShowQuestion = (testData.word !== undefined);
            if (needShowQuestion) {
                $scope.word = testData.word;
                $scope.isEnglishWord = testData.isEnglishWord;
                $scope.questionNumber = testData.questionNumber;
                repaintAnswers(testData);
                showWrongAnswerMessageIfNeed(testData);
            }
            $scope.needShowQuestion = needShowQuestion;
        }
        
        function repaintAnswers(testData) {
            var answers = testData.answers;
            var wrongAnswers = testData.wrongAnswers;
            var answerItems = [];
            for (var i = 0; i < answers.length; i++) {
                // Помечаем неправильные варианты ответа, которые уже выбирал пользователь.
                var isWrong = false;
                for (var j = 0; j < wrongAnswers.length; j++) {
                    if (answers[i] === wrongAnswers[j]) {
                        isWrong = true;
                        break;
                    }
                }
                answerItems.push(new AnswerItem(answers[i], isWrong) );
            }
            $scope.answerItems = answerItems;
        }
        
        function showWrongAnswerMessageIfNeed(testData) {
            if ( (testData.isLastAnswerValid !== undefined) && !testData.isLastAnswerValid) {
                $scope.needShowWrongAnswerMessage = true;
            } else {
                $scope.needShowWrongAnswerMessage = false;
            }
        }
        
        function repaintTestState(testData) {
            $scope.successCount = testData.successCount;
            $scope.errorCount = testData.errorCount;
        }
        
        function showFinishStatistiIfNeed(testData) {
            _testFinished = (testData.testFinished !== undefined) && (testData.testFinished === true);
            if (_testFinished) {
                $scope.successCount = testData.successCount;
                $scope.needShowQuestion = false;
            }
            
            $scope.needShowFinishStatistic = _testFinished; 
            
        }
        
        function selectAnswerSuccess(data) {
            afterAjaxRequest();
            InfoService.setTestData(data);
            repaint();
        }
        
        function selectAnswerError() {
             afterAjaxRequest();
             alert('Произошла ошибка!');
        }
        
        function restartTestSuccess(data) {
            afterAjaxRequest();
            InfoService.setTestData(data);
            repaint();
        }
        
        function restartTestError() {
             afterAjaxRequest();
             alert('Произошла ошибка!');
        }
                           
    };
    
    TestController.$inject = injectParams;
    
    
    angular.module('pages').controller('TestController', TestController);
    
})(); 