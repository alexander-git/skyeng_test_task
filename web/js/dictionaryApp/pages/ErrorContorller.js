(function() {
    
    var injectParams = [
        '$scope', 
        '$routeParams',
        'UrlService',
        'errorsData'
    ];
     
    var ErrorController = function($scope, $routeParams, UrlService, errorsData)  {
        var needShowSimpleErrors = $routeParams.type === 'simple';     
        
        // Создаём ссылки для передачи их в вид.
        var pageHrefs = [];
        for (var i = 1; i <= errorsData.numberPages; i++) {
            if (needShowSimpleErrors) {
                pageHrefs.push(UrlService.getSimpleErrorsHrefUrl(i) );
            } else {
                pageHrefs.push(UrlService.getCompositeErrorsHrefUrl(i) );
            }
        }
        var currentPage = $routeParams.page;
        if (needShowSimpleErrors) {
            $scope.simpleErrorsHref = UrlService.getSimpleErrorsHrefUrl(currentPage);
            $scope.compositeErrorsHref = UrlService.getCompositeErrorsHrefUrl(1);
        } else {
            $scope.simpleErrorsHref = UrlService.getSimpleErrorsHrefUrl(1);
            $scope.compositeErrorsHref = UrlService.getCompositeErrorsHrefUrl(currentPage);
        }
        $scope.needShowSimpleErrors = needShowSimpleErrors;
        $scope.pageHrefs = pageHrefs;
        $scope.errors = errorsData.errors;
    };
    
    ErrorController.$inject = injectParams;
    
    angular.module('pages').controller('ErrorController', ErrorController);
    
})(); 