(function() {

    var injectParams = [];

    // UrlService предназначен для централизованного создания всех адресов
    // приложения. Результаты вызова его методов можно использовать при 
    // создании ссылок или в качестве аргумента метода $location.url().
    function UrlService() {
                 
        var service = {};
        
        service.getStartUrl = function() {
            return '/';
        };
        
        service.getTestUrl = function() {
            return '/test';
        };
        
        service.getResultsUrl = function(pageNumber) {
            if (pageNumber === undefined) {
                pageNumber = 1;
            }
            return '/results/page/' + pageNumber;
        };
        
        service.getSimpleErrorsUrl = function(pageNumber) {
            if (pageNumber === undefined) {
                pageNumber = 1;
            }
            return '/errors/simple/page/' + pageNumber;
        };
             
        service.getCompositeErrorsUrl = function(pageNumber) {
            if (pageNumber === undefined) {
                pageNumber = 1;
            }
            return '/errors/composite/page/' + pageNumber; 
        };
        
        // Функции использующиеся для создания ссылок
        
        service.getStartHrefUrl = function() {
            return '#' + this.getStartUrl();
        };
        
        service.getTestHrefUrl = function() {
            return '#' + this.getTestUrl();
        };
        
        service.getResultsHrefUrl = function(pageNumber) {
            return '#' + this.getResultsUrl(pageNumber);
        };
        
        service.getSimpleErrorsHrefUrl = function(pageNumber) {
            return '#' + this.getSimpleErrorsUrl(pageNumber);
        };
             
        service.getCompositeErrorsHrefUrl = function(pageNumber) {
            return '#' + this.getCompositeErrorsUrl(pageNumber); 
        };
        
        return service;
    };

    UrlService.$inject = injectParams;
    
    angular.module('url').factory('UrlService', UrlService);

})();