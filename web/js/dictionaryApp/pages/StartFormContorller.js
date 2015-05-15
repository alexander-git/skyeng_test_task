(function() {
    
    var DURATION_OF_SHOW_ERROR_MESSAGE = 5000;
    
    var injectParams = [
        '$scope', 
        '$location',
        'BackendService',
        'InfoService'
    ];
     
    var StartFormController = function(
        $scope, 
        $location,
        BackendService, 
        InfoService
    ) 
    {
        $scope.usernameMinLength = 3;
        $scope.usernameMaxLength = 20;        
        $scope.usernamePattern = /^[a-zA-Z][a-zA-Z0-9-_\.]*$/;
        
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
            // Если пользователь уже вошёл и его имя было сохранено раньше
            // или имя в форме введено верно, то може отправлять
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
            $scope.needShowProcess = true;
            $scope.needShowErrorMessage = false;
        }

        function afterAjaxRequest() {
            $scope.needShowProcess = false;
        }

        function showErrorMessage() {
            $scope.needShowErrorMessage = true;
            setTimeout(
                function() { $scope.needShowErrorMessage = false; }, 
                DURATION_OF_SHOW_ERROR_MESSAGE
            );
        }
        
        // Вызывается при нормальном ответе сервера, 
        function startTestSuccess(data) {
            afterAjaxRequest();
            if (data.success !== undefined) {
                $location.url('/test');
            } else {
                showErrorMessage();
            }
        }
        
        // Вызывается при ошибке связанной с запросом на сервер.
        function startTestError() {
            afterAjaxRequest();
            showErrorMessage();
        }
                
    };
    
    StartFormController.$inject = injectParams;
    
    
    angular.module('pages').controller('StartFormController', StartFormController);
    
})(); 