(function() {
            
    var injectParams = [];
      
    var BackendServiceProvider = function() {
        
        var _userUrl = null;
        var _startTestUrl = null;
        var _logoutUrl = null;
        var _testDataUrl = null;
        var _answerUrl = null;
        var _restartTestUrl = null;
        var _resultsUrl = null;
        var _errorsUrl = null;

        this.setUserUrl = function(value) {
            _userUrl = value;
        };
        
        this.setStartTestUrl = function (value) {
            _startTestUrl = value;
        };
        
        this.setLogoutUrl = function(value) {
            _logoutUrl = value;
        };
        
        this.setTestDataUrl = function(value) {
            _testDataUrl = value;
        };
        
        this.setAnswerUrl = function(value) {
            _answerUrl = value;
        };
        
        this.setRestartTestUrl = function(value) {
            _restartTestUrl = value;
        };
        
        this.setResultsUrl = function(value) {
            _resultsUrl = value;
        };
        
        this.setErrorsUrl = function(value) {
            _errorsUrl = value;
        };
        
        this.$get  = ['$http', '$q', '$route', 'InfoService', function($http, $q, $route, InfoService) {                      
            
            var service = {};
            
            service.getUser = function() {
                
                if (InfoService.hasUser() ) {
                    // Если информация о пользователе уже храниться, то запрос 
                    // на сервер не выполняем и просто возвращаем выполненное обещание.
                    var task = $q.defer();
                    task.resolve(InfoService.getUser() );
                    return task.promise;
                }
                
                return $http({
                    'method' : 'GET',
                    'url' : _userUrl
                }).then(function(response) {
                    InfoService.setUser(response.data);
                    return response.data;
                });
            };
            
            service.startTest = function(username) {    
                return $http({
                    'method' : 'POST',
                    'url' : _startTestUrl,
                    'data' : { 'username' : username }
                });
            };
            
            service.logout = function() {
                return $http({
                    'method' : 'GET',
                    'url' : _logoutUrl
                });
            };
            
            service.getTestData = function() {
                return $http({
                    'method' : 'GET',
                    'url' : _testDataUrl
                }).then(function(response) {
                    InfoService.setTestData(response.data);
                    return response.data;
                });
            };
            
            service.answer = function(answer) {
                return $http({
                    'method' : 'POST',
                    'url' : _answerUrl,
                    'data' : { 'answer' : answer }
                });
            };
            
            service.restartTest = function() {
                return $http({
                    'method' : 'GET',
                    'url' : _restartTestUrl,
                });
            };
            
            service.getResults = function() {
                return $http({
                    'method' : 'POST',
                    'url' : _resultsUrl,
                    'data' : { 'page' : $route.current.params.page }
                }).then(function(response) {
                    return response.data;
                });
            };
            
            service.getErrors = function() {  
                return $http({
                    'method' : 'POST',
                    'url' : _errorsUrl,
                    'data' : { 
                        'page' : $route.current.params.page,
                        'type' : $route.current.params.type
                    }
                }).then(function(response) {
                    return response.data;
                });
            };
            
            return service;
        }];       
        
    };
    
    BackendServiceProvider.$inject = injectParams;
    
    angular.module('backend').provider('BackendService', BackendServiceProvider);
    
}());