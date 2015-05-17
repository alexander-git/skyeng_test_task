(function() {
    

    var injectParams = ['$scope', 'UrlService', 'resultsData'];
     
    var ResultController = function($scope, UrlService, resultsData)  {
        var pageHrefs = [];
        for (var i = 1; i <= resultsData.numberPages; i++) {
            pageHrefs.push(UrlService.getResultsHrefUrl(i) );
        }
        $scope.pageHrefs = pageHrefs;
        $scope.results = resultsData.results;
    };
    
    ResultController.$inject = injectParams;
    
    angular.module('pages').controller('ResultController', ResultController);
    
})(); 