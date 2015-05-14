(function() {
    
    var DURATION_OF_SHOW_ERROR_MESSAGE = 5000;
    
    var injectParams = [
        '$scope', 
        'BackendService',
        'InfoService'
    ];
     
    var TestController = function(
        $scope, 
        BackendService, 
        InfoService
    ) 
    {
                
        function beforeAjaxRequest() {
            $scope.isNeedShowProcess = true;
            $scope.isNeedShowError = false;
        }

        function afterAjaxRequest() {
            $scope.isNeedShowProcess = false;
        }

        function showErrorMessage() {
            $scope.isNeedShowErrorMessage = true;
            setTimeout(
                function() { $scope.isNeedShowErrorMessage = false; }, 
                DURATION_OF_SHOW_ERROR_MESSAGE
            );
        }
        
        $scope.hideErrorMessage = function() {
            $scope.isNeedShowErrorMessage = false;
        };
                   
    };
    
    TestController.$inject = injectParams;
    
    
    angular.module('test').controller('TestController', TestController);
    
})(); 