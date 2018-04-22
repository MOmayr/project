app.controller('DataViewerController', function ($scope, $http, $mdDialog, $rootScope, $state) {

    $.each(roles, function (i, obj) {
        // console.log(obj);
        if (obj.role_name === $state.current.name) $rootScope.selectedTab = i;
    });

    $scope.startDate = new Date();
    $scope.endDate = new Date();
    $scope.maxDate = new Date();

    $scope.gridOptions = {
        // columnDefs: [{field: "Front type of Property"}],
        enableFiltering: true,
        enableGridMenu: true,
        exporterMenuPdf: false,
        exporterMenuCsv: false,
        enableSelectAll: true,
        exporterExcelFilename: 'Android Data.xlsx',
        exporterExcelSheetName: 'Data',
        minimumColumnSize: 150,
        onRegisterApi: function (gridApi) {
            $scope.gridApi = gridApi;
        }
    };

    $scope.dropdownChange = function (id, obj) {
        if (id === 'district') {
            $scope.circle = undefined;
        }
        ;
        // console.log($scope.district, $scope.circle);
    };

    $scope.getData = function () {
        var circle = $scope.circle === "All" ? "%" : $scope.circle.name;
        var district = $scope.district === "All" ? "%" : $scope.district;
        $http({
            method: 'GET',
            url: "services/viewer/GetCircleData.php?district=" + district + "&circle=" + circle +
            "&startDate=" + $scope.selectedDateStart + "&endDate=" + $scope.selectedDateEnd
            // url: "services/viewer/GetCircleData.php?district=Lahore&circle=Abbot Road"
        }).then(function (value) {
            if (value.data.error === "cout") {
                location.reload();
                return;
            }
            $scope.stats = value.data.stats;
            $scope.gridOptions.data = convertResArrayToObj(value.data.vals, value.data.keys);
            $scope.gridOptions.columnDefs = [];
            if (value.data.keys instanceof Array) {
                value.data.keys.forEach(function (val, i) {
                    $scope.gridOptions.columnDefs.push({
                        field: val, cellTooltip: true, headerTooltip: true,
                        enableColumnResizing: true
                    });
                })
            }
            // console.log($scope.gridOptions.data);
        }, function (reason) {
            alert("Something is Wrong!");
        });
    };
    // $scope.getCircleData();

    $scope.getDistrictCircle = function () {
        $http({
            method: 'GET',
            url: "services/dashboard/GetDistrictCircle.php"
        }).then(function (value) {
            if (value.data.error === "cout") {
                location.reload();
                return;
            }
            $scope.districts = value.data.districts;
            $scope.districts.unshift({name: "All"});
            $scope.circles = value.data.circles;
            // $scope.circles.push({name: "All", district_name: "All"});
            $scope.district = "All";
            // $scope.circle = "All";
            // console.log(value.data);
        }, function (reason) {
            alert("Something is Wrong!");
        });
    };

    $scope.$watch('startDate', function (newVal) {
        try {
            $scope.selectedDateStart = newVal.getFullYear() + "-" + (newVal.getMonth() + 1) + "-" + newVal.getDate();
        } catch (E) {
            $scope.selectedDateStart = undefined;
        }
    });

    $scope.$watch('endDate', function (newVal) {
        try {
            $scope.selectedDateEnd = newVal.getFullYear() + "-" + (newVal.getMonth() + 1) + "-" + newVal.getDate();
        } catch (E) {
            $scope.selectedDateEnd = undefined;
        }
    });
});