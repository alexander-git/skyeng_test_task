(function() {
    
    var MenuItem = function(href, text) {
        this.href = href;
        this.text = text;
    };
    
    function repaintMenuItems($scope, $location, InfoService) {
        var currentUrl = $location.url();
        var onStartPage = currentUrl === '/';
        
        var menuItems = [];
        if (!onStartPage) {
            if (!isUserLogged) {
                menuItems.push(new MenuItem('#/', 'Тест') );
            } else {
                menuItems.push(new MenuItem('#/test', 'Тест') );
            }
        }
        menuItems.push(new MenuItem('#/results', 'Результаты') );
        menuItems.push(new MenuItem('#/errors', 'Ошибки') );
        
        $scope.menuItems = menuItems;
        
        var user = InfoService.getUser();
        var isUserLogged = (user !== null) && (user.username !== undefined);
        $scope.isUserLogged = isUserLogged;
        if (isUserLogged) {
            $scope.user = user;
        }
    }
    
    var injectParams = ['$scope', '$location', 'BackendService', 'InfoService'];
    
    var MainController = function($scope, $location, BackendService, InfoService) {
        
        repaintMenuItems($scope, $location, InfoService);
        
        $scope.logout = function() {
            var logoutResult = BackendService.logout();

            logoutResult.success(function(data, status, headers, config) {
                logoutSuccess();
            }).error(function(data, status, headers, config) {
  
            });
            
        };
        
        function logoutSuccess() {
            InfoService.setUser(null);
            repaintMenuItems($scope, $location, InfoService);
            // Если выход производиться странице которые не должен видеть 
            // не залогиненный пользователь, то перенаправим на главную
            var currentUrl = $location.url();
            if (currentUrl === '/test' || currentUrl === '/finish') {
                $location.url('/');
            }
        }
                
    };
    
   
    MainController.$inject = injectParams;
    
    angular.module('main').controller('MainController', MainController);
    
})(); 
 