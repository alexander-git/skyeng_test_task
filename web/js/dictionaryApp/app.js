var app = angular.module('dictionaryApp', [
    'ngRoute',
    'main',
    'url',
    'info',
    'backend',
    'pages'
]);

app.config(['$routeProvider', function($routeProvider) {
   
    $routeProvider.when('/', {
        templateUrl : '/dictionaryAppViews/index.html',
        contentUrl : '/dictionaryAppViews/start.html', 
        resolve : {
            // Напрямую данные полученные через resolve используются для
            // внедрения в контроллер не всегда. BackendService кроме возврата 
            // некоторых данных в случае успешного выполнения запроса, также 
            // сохраняет их  в InfoService, который уже  в свою очередь может 
            // быть внедрён в любой нужный контроллер. Так сделано потому что 
            // эти данные могут быть нужны в нескольких контроллерах 
            // на одной странице.
            user : ['BackendService', function(BackendService) {
                return BackendService.getUser();
            }]
        } 
    });
    
    $routeProvider.when('/test', {
        templateUrl : '/dictionaryAppViews/index.html',
        contentUrl : '/dictionaryAppViews/test.html', 
        controller : 'TestController',
        resolve : {
            user : ['BackendService', function(BackendService) {
                return BackendService.getUser();
            }],
            testData : ['BackendService', function(BackendService) {
                return BackendService.getTestData();
            }]
        } 
    });
    
    $routeProvider.when('/results', {
        redirectTo : '/results/page/1'
    });
    
    $routeProvider.when('/results/page/:page', {
        templateUrl : '/dictionaryAppViews/index.html',
        contentUrl : '/dictionaryAppViews/result.html', 
        controller : 'ResultController',
        resolve : {
            user : ['BackendService', function(BackendService) {
                return BackendService.getUser();
            }],
            resultsData : ['BackendService', function(BackendService) {
                return BackendService.getResults();
            }]
        } 
    });
    
    $routeProvider.when('/errors', {
        redirectTo : '/errors/simple/page/1'
    });
    
    $routeProvider.when('/errors/:type/page/:page', {
        templateUrl : '/dictionaryAppViews/index.html',
        contentUrl : '/dictionaryAppViews/error.html', 
        controller : 'ErrorController',
        resolve : {
            user : ['BackendService', function(BackendService) {
                return BackendService.getUser();
            }],
            errorsData : ['BackendService', function(BackendService) {
                return BackendService.getErrors();
            }]
        } 
    });
    
    $routeProvider.otherwise({
       redirectTo : '/' 
    });
    
}]);

app.config(['BackendServiceProvider', function(BackendServiceProvider) {
        BackendServiceProvider.setUserUrl(dictionaryAppUrls.user);
        BackendServiceProvider.setStartTestUrl(dictionaryAppUrls.startTest);
        BackendServiceProvider.setLogoutUrl(dictionaryAppUrls.logout);
        BackendServiceProvider.setTestDataUrl(dictionaryAppUrls.testData);
        BackendServiceProvider.setAnswerUrl(dictionaryAppUrls.answer);
        BackendServiceProvider.setRestartTestUrl(dictionaryAppUrls.restartTest);
        BackendServiceProvider.setResultsUrl(dictionaryAppUrls.results);
        BackendServiceProvider.setErrorsUrl(dictionaryAppUrls.errors);
    }
]);

app.run(['$rootScope', '$route', function($rootScope, $route) {
        // Используется в '/dictionaryAppViews/index.html'.
        $rootScope.$route = $route;
}]);

// При смене адреса показываем, что идёт процесс загрузки.
app.run(['$rootScope', function($rootScope) {
    
    $rootScope.needShowLoading = false;
    
    /*  
    // Стоит расскомментировать на реальном хостинге.  
    $rootScope.$on('$routeChangeStart', function(event, next, current) {
        $rootScope.needShowLoading = true;
    });
    
    $rootScope.$on('$routeChangeSuccess', function(event, next, current) {
        $rootScope.needShowLoading = false;
    });
    
    $rootScope.$on('$routeChangeError', function(event, next, current) {
        $rootScope.needShowLoading = false;
    });
    */
}]);