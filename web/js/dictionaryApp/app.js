var app = angular.module('dictionaryApp', [
    'ngRoute',
    'main',
    'info',
    'backend',
    'pages'
]);

app.config(['$routeProvider', function($routeProvider) {
   
    $routeProvider.when('/', {
        templateUrl : '/dictionaryAppViews/index.html',
        contentUrl : '/dictionaryAppViews/start.html', 
        resolve : {
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
        redirectTo : '/errors/page/1'
    });
    
    $routeProvider.when('/errors/page/:page', {
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
        // Используется в index.html
        $rootScope.$route = $route;
}]);

app.run(['$rootScope', function($rootScope) {
    // При смене адреса показываем, что идёт процесс загрузки.
    $rootScope.needShowLoading = false;
    
    $rootScope.$on('$routeChangeStart', function(event, next, current) {
        $rootScope.needShowLoading = true;
    });
    
    $rootScope.$on('$routeChangeSuccess', function(event, next, current) {
        $rootScope.needShowLoading = false;
    });
    
    $rootScope.$on('$routeChangeError', function(event, next, current) {
        $rootScope.needShowLoading = false;
    });
}]); 