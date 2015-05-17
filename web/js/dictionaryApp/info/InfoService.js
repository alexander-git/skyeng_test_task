(function() {
                        
    var injectParams = [];
    
    // Предназначен для хранения информации о пользователе и состоянии теста. 
    function InfoService() {
        
        var _user = null;
        var _testData = null;
 
        var service = {};

        service.getUser = function() {
            return _user;
        };

        service.setUser = function(value) {    
            _user = value;
        };

        service.hasUser = function() {
            return (_user !== null) && (_user.username !== undefined);
        };

        service.setTestData = function(value) {
            _testData = value;
        };

        service.getTestData = function() {
            return _testData;
        };

        return service;
    };
    
    InfoService.$inject = injectParams;
    
    angular.module('info').factory('InfoService', InfoService);
    
}());