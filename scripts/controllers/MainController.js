app.controller('MainController', function ($scope, $state) {
    $scope.roles = roles;
    if(roles instanceof Array){
        $state.go(roles[0].role_name);
    }
    console.log($scope.roles);

    $scope.changeState = function(path) {
        console.log(path);
        $state.go(path);
    }
});

app.config(['$stateProvider', '$urlRouterProvider', function ($stateProvider, $urlRouterProvider) {
    $stateProvider
        .state('Excel Report', {
            url: '/ExcelReport',
            templateUrl: 'templates/excel/excel.php',
            onEnter: function () {
                // console.log('state entered')
            },
            onExit: function () {
                // console.log('state exit');
            }
        })
        .state('Admin', {
            url: '/Admin',
            templateUrl: 'templates/admin/admin.php',
            onEnter: function () {
                // console.log('state entered')
            },
            onExit: function () {
                // console.log('state exit');
            }
        })
        .state('Data Viewer', {
            url: '/DataViewer',
            templateUrl: 'templates/viewer/viewer.php',
            onEnter: function () {
                // console.log('state entered')
            },
            onExit: function () {
                // console.log('state exit');
            }
        })
        .state('Dashboard', {
            url: '/Dashboard',
            templateUrl: 'templates/dashboard/dashboard.php',
            onEnter: function () {
                // console.log('state entered')
            },
            onExit: function () {
                // console.log('state exit');
            }
        });
    // $urlRouterProvider.otherwise(roles[0].role_name);
}]);

app.directive('myNumeric', function () {
    return function (scope, element) {
        element.bind('keyup', function () {
            var prevVal = element.val();
            var chars = element.val().split('');
            var value = "";
            for (var i = 0; i < chars.length; i++) {
                if (chars[i] === " ") continue;
                if (chars[i] === 0) {
                    value += chars[i];
                } else if (parseInt(chars[i])) {
                    value += chars[i];
                    //console.log(value);
                }
            }
            if (prevVal !== value) element.val(value);
            scope.$apply();
        });
    };
});

