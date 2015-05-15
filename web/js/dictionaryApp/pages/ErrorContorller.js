(function() {
    
    var injectParams = [
        '$scope', 
        'errorsData'
    ];
     
    var ErrorController = function($scope, errorsData)  {
        $scope.errors = errorsData.errors;     
        
        var pageHrefs = [];
        for (var i = 1; i <= errorsData.numberPages; i++) {
            pageHrefs.push('#/errors/page/' + i);
        }
        $scope.pageHrefs = pageHrefs;
    };
    
    ErrorController.$inject = injectParams;
    
    angular.module('pages').controller('ErrorController', ErrorController);
    
})(); 