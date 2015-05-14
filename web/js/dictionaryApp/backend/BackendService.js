(function() {
            
    var injectParams = [];
      
    var BackendServiceProvider = function() {
        
        var _userUrl = null;
        var _startTestUrl = null;
        var _logoutUrl = null;
        var _testDataUrl = null;
        var _answerUrl = null;

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
        
        this.$get  = ['$http', '$q', 'InfoService', function($http, $q, InfoService) {                      
            
            var service = {};
            
            service.getUser = function() {
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
            
            service.getTestData = function() {
                console.log(_testDataUrl);
                return $http({
                    'method' : 'GET',
                    'url' : _testDataUrl
                }).then(function(response) {
                    InfoService.setTestData(response.data);
                    return response.data;
                });
            };
            
            service.logout = function() {
                return $http({
                    'method' : 'GET',
                    'url' : _logoutUrl
                });
            };
            
            return service;
        }];       
        
    };
    
    BackendServiceProvider.$inject = injectParams;
    
    angular.module('backend').provider('BackendService', BackendServiceProvider);
    
}());