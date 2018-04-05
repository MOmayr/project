var csv;
app.controller('ExcelController', function ($scope, $http, $mdDialog, $rootScope) {
    loadScript("scripts/getcsv.js", csv, function () {

    });
    $rootScope.selectedTab = 1;

    $scope.startDate = new Date();
    $scope.endDate = new Date();
    $scope.maxDate = new Date();

    $scope.dropdownChange = function (id, obj) {
        if (id === 'district') {
            if(obj.name === "All"){
                $scope.circle = "All";
            }else $scope.circle = undefined;
        }
        // console.log($scope.district, $scope.circle);
    };

    $scope.getData = function(){
        var circle = $scope.circle === "All" ? "%" : $scope.circle.name;
        var data = {district: $scope.district.name === "All" ? "%" : $scope.district.name, circle: circle, startDate: $scope.selectedDateStart, endDate: $scope.selectedDateEnd};
        // console.log(data);
        $.ajax({
            url: 'services/excel/GetExcel.php',
            type: "GET",
            dataType: 'json',
            data: data,
            async: false,
            success: function (response) {
                if(response.error === "cout") {
                    location.reload();
                    return;
                }
                var arr = response.vals;
                var keys = response.keys;
                var csvData = convertResArrayToObj(arr, keys);
                // console.log(csvData);
                if(csvData.length===0){
                    alert("No Data Found");
                }else JSONToCSVConvertor(csvData, "Android Data", true,  "");
            },
            error: function (response) {
                console.log(response);
                alert("Something is Wrong!");
            }
        });

        // $http({
        //     method: 'GET',
        //     url: "services/excel/GetExcel.php",
        //     // data: data
        // }).then(function (value) {
        //     var arr = value.data.vals;
        //     var keys = value.data.keys;
        //     var csvData = convertResArrayToObj(arr, keys);
        //     console.log(csvData);
        //     JSONToCSVConvertor(csvData, "Android Data", true,  "");
        // }, function (reason) {
        //     alert("Something is Wrong!");
        // });
    };

    $scope.getDistrictCircle = function () {
        $http({
            method: 'GET',
            url: "services/excel/GetDistrictCircle.php"
        }).then(function (value) {
            if(value.data.error === "cout") {
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

app.directive('myDropdown', function () {
    return {
        template: function (elem, attr) {
            return '<md-input-container flex="100">' +
                '<label>' + attr.label + '</label>' +
                '<md-select ng-model="' + attr.myDropdown + '" ng-change="' + attr.callback + '" required>' +
                '<md-option value="All"><em>All</em></md-option>' +
                '<md-option ng-repeat="' + attr.array + '" ng-value="obj">' +
                '{{obj.name}}' +
                '</md-option>' +
                '</md-select>' +
                '</md-input-container>';
        }
    }
});

app.config(function ($mdDateLocaleProvider) {
    $mdDateLocaleProvider.formatDate = function (date) {
        if (date === undefined) return;
        var day = date.getDate();
        var monthIndex = date.getMonth();
        var year = date.getFullYear();

        return year + '-' + (monthIndex + 1) + '-' + day;
    };
});
