app.controller('AdminController', function ($scope, $http, $mdDialog) {
    console.log('admin controller loaded');

    $scope.selectUser = function (u) {
        // $scope.currentUser = undefined;
        $scope.currentUser = u;
    };

    $scope.getUsers = function () {
        $http({
            method: 'GET',
            url: "services/admin/FetchUsers.php"
        }).then(function (value) {
            $scope.users = value.data;
            console.log($scope.users);
        }, function (reason) {
            alert("Something is Wrong!");
        });
    };

    $scope.showUserDialog = function (user, mode, ev) {
        $mdDialog.show({
            controller: UserDialogController,
            locals: {user: user, mode: mode},
            scope: $scope,
            preserveScope: true,
            templateUrl: 'templates/admin/user_dialog.php',
            parent: angular.element(document.body),
            targetEvent: ev,
            clickOutsideToClose: true,
            fullscreen: $scope.customFullscreen // Only for -xs, -sm breakpoints.
        }).then(function (answer) {

        }, function () {
        });
    };

    $scope.showAlert = function(title, content){
        $mdDialog.show(
            $mdDialog.alert()
                .parent(angular.element(document.querySelector('body')))
                .clickOutsideToClose(true)
                .title(title)
                .textContent(content)
                .ariaLabel('Dialog')
                .ok('OK!')
        );
    }
});

function UserDialogController($scope, $mdDialog, user, mode) {
    $scope.user = angular.copy(user);
    $scope.mode = mode;

    $scope.createUpdateUser = function(){
        var newUser = $scope.user;
        newUser.access = newUser === true ? "t" : "f";
        newUser = JSON.stringify($scope.user);

        console.log(newUser);
        $.ajax({
            url: 'services/admin/CreateUpdateUser.php',
            type: "POST",
            dataType: 'json',
            data: {mode: mode, user: newUser},
            async: false,
            success: function (response) {
                console.log(response);
                if (parseInt(response)) {
                    $scope.showAlert('Success', 'User '+mode.replace(" ", "") + "d Successfully");
                    $scope.cancel();
                    $scope.getUsers();
                }
            },
            error: function (response) {
                console.log(response);
            }
        });
    };

    $scope.cancel = function () {
        $mdDialog.cancel();
    };
}