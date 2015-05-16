(function() {
    
    var injectParams = [
        '$scope', 
        '$routeParams',
        'errorsData'
    ];
     
    var ErrorController = function($scope, $routeParams, errorsData)  {
        var needShowSimpleErrors = $routeParams.type === 'simple';     
        
        var pageHrefs = [];
        for (var i = 1; i <= errorsData.numberPages; i++) {
            if (needShowSimpleErrors) {
                pageHrefs.push('#/errors/simple/page/' + i);
            } else {
                pageHrefs.push('#/errors/composite/page/' + i);
            }
        }
        $scope.currentPage = $routeParams.page;
        $scope.needShowSimpleErrors = needShowSimpleErrors;
        $scope.pageHrefs = pageHrefs;
        $scope.errors = errorsData.errors;
    };
    
    ErrorController.$inject = injectParams;
    
    angular.module('pages').controller('ErrorController', ErrorController);
    
})(); 