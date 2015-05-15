(function() {
            
    //#TODO переписать как фабрику.
            
    var injectParams = [];
      
    var InfoServiceProvider = function() {
        
        var _user = null;
        var _testData = null;
 
 
        this.$get  = [function() {                      
            
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
        }];       
        
    };
    
    InfoServiceProvider.$inject = injectParams;
    
    angular.module('info').provider('InfoService', InfoServiceProvider);
    
}());