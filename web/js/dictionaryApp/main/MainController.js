(function() {
    
    var MenuItem = function(href, text) {
        this.href = href;
        this.text = text;
    };
    
    var injectParams = [
        '$scope', 
        '$location', 
        '$window', 
        'BackendService', 
        'InfoService',
        'UrlService'
    ];
        
    var MainController = function($scope, $location, $window, BackendService, InfoService, UrlService) {
        
        repaintMenuItems();
        
        $scope.logout = function() {
            var logoutResult = BackendService.logout();

            logoutResult.success(function(data, status, headers, config) {
                logoutSuccess();
            }).error(function(data, status, headers, config) {
                $window.alert('Произошла ошибка!');
            });  
        };
        
        function repaintMenuItems() {
            var currentUrl = $location.url();
            var onStartPage = currentUrl === UrlService.getStartUrl();
            var isUserLogged = InfoService.hasUser();

            var menuItems = [];
            if (!onStartPage) {
                if (!isUserLogged) {
                    menuItems.push(new MenuItem(UrlService.getStartHrefUrl(), 'Тест') );
                } else {
                    menuItems.push(new MenuItem(UrlService.getTestHrefUrl(), 'Тест') );
                }
            }
            menuItems.push(new MenuItem(UrlService.getResultsHrefUrl(1), 'Результаты') );
            menuItems.push(new MenuItem(UrlService.getSimpleErrorsHrefUrl(1), 'Ошибки') );
            $scope.menuItems = menuItems;

            $scope.isUserLogged = isUserLogged;
            if (isUserLogged) {
                $scope.user = InfoService.getUser();
            }
        }         
        
        function logoutSuccess() {
            InfoService.setUser(null);
            repaintMenuItems();
            // Если выход производиться странице которую не должен видеть 
            // незалогиненный пользователь, то перенаправим на главную.
            var currentUrl = $location.url();
            if (currentUrl === UrlService.getTestUrl() )  {
                $location.url(UrlService.getStartUrl() );
            }
        }
        
    };
    
   
    MainController.$inject = injectParams;
    
    angular.module('main').controller('MainController', MainController);
    
})(); 
 