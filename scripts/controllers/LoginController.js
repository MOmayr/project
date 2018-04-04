// var loginApp = angular.module('loginApp', []);

app.controller('LoginController', function ($scope) {
    console.log('login controller loaded');
});

app.config(['$stateProvider', '$urlRouterProvider', function ($stateProvider, $urlRouterProvider) {
    $stateProvider
        .state('login', {
            url: '/Login',
            templateUrl: 'templates/login.php'
        });
    $urlRouterProvider.otherwise('/Login');
}]);

// app.requires.push('loginApp');