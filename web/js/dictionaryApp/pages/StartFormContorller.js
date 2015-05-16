(function() {
    
    var _requestExecuted = false;
    
    var injectParams = [
        '$scope', 
        '$location',
        '$window',
        'BackendService',
        'InfoService'
    ];
     
    var StartFormController = function(
        $scope, 
        $location,
        $window,
        BackendService, 
        InfoService
    ) 
    {
        $scope.usernameMinLength = 3;
        $scope.usernameMaxLength = 30;  
        
        // Создадим шаблон для имени пользователя.
        var fs = "%&,!'`=#@$;~\"\\|\\:\\?\\+\\*\\(\\)\\[\\]\\^\\/\\\\"; // Запрещённые символы.
        var startFinishFs = "\\d-_\\." + fs;
        $scope.usernamePattern = new RegExp("^[^"+startFinishFs+"][^"+fs+"]*[^"+startFinishFs+"]$"); 
                
        // Используется в виде когда выполняется ajax-запрос.
        $scope.needShowProcess = false;
        $scope.needShowErrorMessage = false;
        
        $scope.showError = function(ngModelController, error) {
            if (!ngModelController.$dirty) {
                return false;
            }
            return ngModelController.$error[error];
        };
        
        $scope.canSubmitForm = function() {
            if (_requestExecuted) {
                return false;
            }
            // Если пользователь уже вошёл и его имя было сохранено раньше
            // или имя в форме введено верно, то можно отправлять
            // запрос на начало теста.
            return isUserLogged() || ($scope.startForm.$dirty && $scope.startForm.$valid);
        };
                
        $scope.submitForm = function() {
            var username;
            if (isUserLogged() ) { // Если пользователь вошёл используем сохранённое имя. 
                username = InfoService.getUser().username;
            } else { // Иначе возьмём его из формы.
                username = $scope.username;
            }
           
            beforeAjaxRequest();
            var startTestResult = BackendService.startTest(username);
            
            startTestResult.success(function(data, status, headers, config) {
                startTestSuccess(data);
            }).error(function(data, status, headers, config) {
                startTestError();
            });
        };
        
        function isUserLogged() {
            return InfoService.hasUser();
        }
       
        function beforeAjaxRequest() {
            _requestExecuted = true;
            $scope.needShowProcess = true;
        }

        function afterAjaxRequest() {
            _requestExecuted = false;
            $scope.needShowProcess = false;
        }
        
        // Вызывается при нормальном ответе сервера, 
        function startTestSuccess(data) {
            afterAjaxRequest();
            if (data.success !== undefined) {
                $location.url('/test');
            } else {
                $window.alert('Начать тест не удалось!');
            }
        }
        
        // Вызывается при ошибке связанной с запросом на сервер.
        function startTestError() {
            afterAjaxRequest();
            $window.alert('Произошла ошибка!');
        }
                
    };
    
    StartFormController.$inject = injectParams;
    
    
    angular.module('pages').controller('StartFormController', StartFormController);
    
})(); 