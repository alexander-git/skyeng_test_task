(function() {
    
    var MenuItem = function(href, text) {
        this.href = href;
        this.text = text;
    };
    
    var injectParams = ['$scope', '$location', '$window', 'BackendService', 'InfoService'];
    
    var MainController = function($scope, $location, $window, BackendService, InfoService) {
        
        repaintMenuItems();
        
        $scope.logout = function() {
            var logoutResult = BackendService.logout();

            logoutResult.success(function(data, status, headers, config) {
                logoutSuccess();
            }).error(function(data, status, headers, config) {
                $window.alert('Произошла ошибка!');
            });
            
        };
        
        function logoutSuccess() {
            InfoService.setUser(null);
            repaintMenuItems();
            // Если выход производиться странице которые не должен видеть 
            // незалогиненный пользователь, то перенаправим на главную
            var currentUrl = $location.url();
            if (currentUrl === '/test') {
                $location.url('/');
            }
        }
        
        function repaintMenuItems() {
            var currentUrl = $location.url();
            var onStartPage = currentUrl === '/';
            var isUserLogged = InfoService.hasUser();

            var menuItems = [];
            if (!onStartPage) {
                if (!isUserLogged) {
                    menuItems.push(new MenuItem('#/', 'Тест') );
                } else {
                    menuItems.push(new MenuItem('#/test', 'Тест') );
                }
            }
            
            menuItems.push(new MenuItem('#/results/page/1', 'Результаты') );
            menuItems.push(new MenuItem('#/errors/simple/page/1', 'Ошибки') );

            $scope.menuItems = menuItems;

            $scope.isUserLogged = isUserLogged;
            if (isUserLogged) {
                $scope.user = InfoService.getUser();
            }
        }         
    };
    
   
    MainController.$inject = injectParams;
    
    angular.module('main').controller('MainController', MainController);
    
})(); 
 