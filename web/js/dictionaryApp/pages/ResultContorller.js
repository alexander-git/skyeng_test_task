(function() {
    

    var injectParams = [
        '$scope', 
        'resultsData'
    ];
     
    var ResultController = function($scope, resultsData)  {
        $scope.results = resultsData.results;
        
        var pageHrefs = [];
        for (var i = 1; i <= resultsData.numberPages; i++) {
            pageHrefs.push('#/results/page/' + i);
        }
        $scope.pageHrefs = pageHrefs;
    };
    
    ResultController.$inject = injectParams;
    
    angular.module('pages').controller('ResultController', ResultController);
    
})(); 