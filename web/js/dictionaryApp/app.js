var app = angular.module('dictionaryApp', [
    'ngRoute',
    'main',
    'info',
    'backend',
    'start',
    'test'
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
        resolve : {
            user : ['BackendService', function(BackendService) {
                return BackendService.getUser();
            }],
            testData : ['BackendService', function(BackendService) {
                return BackendService.getTestData();
            }]
        }
        
    });
        
    $routeProvider.otherwise({
       redirectTo : '/' 
    });
}]);

app.constant('userUrl', dictionaryAppUrls.user);
app.constant('startTestUrl', dictionaryAppUrls.startTest);
app.constant('logoutUrl', dictionaryAppUrls.logout);
app.constant('testDataUrl', dictionaryAppUrls.testData);
app.constant('answerUrl', dictionaryAppUrls.answer);

app.config([
    'BackendServiceProvider', 
    'userUrl',
    'startTestUrl',
    'logoutUrl',
    'testDataUrl',
    'answerUrl',
    function(
        BackendServiceProvider, 
        userUrl,
        startTestUrl,
        logoutUrl,
        testDataUrl,
        answerUrl
    ) 
    {
        BackendServiceProvider.setUserUrl(userUrl);
        BackendServiceProvider.setStartTestUrl(startTestUrl);
        BackendServiceProvider.setLogoutUrl(logoutUrl);
        BackendServiceProvider.setTestDataUrl(testDataUrl);
        BackendServiceProvider.setAnswerUrl(answerUrl);
    }
]);

app.run(['$rootScope', '$route', function($rootScope, $route) {
        // Используется в index.html
        $rootScope.$route = $route;
}]);

app.run(['$rootScope', function($rootScope) {
    // При смене адреса показываем, что идёт процесс загрузки.
    $rootScope.isNeedShowLoading = false;
    
    $rootScope.$on('$routeChangeStart', function(event, next, current) {
        $rootScope.isNeedShowLoading = true;
    });
    
    $rootScope.$on('$routeChangeSuccess', function(event, next, current) {
        $rootScope.isNeedShowLoading = false;
    });
    
    $rootScope.$on('$routeChangeError', function(event, next, current) {
        $rootScope.isNeedShowLoading = false;
    });
}]); 