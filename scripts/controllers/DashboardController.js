var highchart, exporting;
var GREEN = "green";
var BLUE = "blue";
var RED = "red";
var ORANGE = "orange";
app.controller('DashboardController', function ($scope, $http, $mdDialog, $rootScope, $state) {

    $scope.getAllStats = function () {
        $http({
            method: 'GET',
            url: "services/dashboard/GetAllStats.php"
        }).then(function (value) {
            if (value.data.error === "cout") {
                location.reload();
                return;
            }
            var data = value.data;
            if (data.length > 0) {
                var all = {};
                var firstObj = data[0];
                Object.keys(firstObj).forEach(function (k, i) {
                    all[k] = [];
                });

                data.forEach(function (obj, i) {
                    Object.keys(obj).forEach(function (key, index) {
                        all[key].push(obj[key]);
                    });
                });
                console.log(all);
                var seriesProperty = [];
                seriesProperty.push({name: "Total Properties", data: all.total});
                seriesProperty.push({name: "Surveyed Properties", data: all.surveyed});
                seriesProperty.push({name: "Un-Surveyed Properties", data: all.unsurveyed});
                generateBarChart('propertyCountChart', all.district_name, seriesProperty, [BLUE, GREEN, RED]);

                var seriesPrType = [];
                seriesPrType.push({name: "Land (Covered & Uncovered)", data: all.land});
                seriesPrType.push({name: "Open Plots", data: all.openplot});
                generateBarChart('propertyTypeChart', all.district_name, seriesPrType, [GREEN, BLUE]);

                var seriesOccStatus = [];
                seriesOccStatus.push({name: "Self", data: all.self});
                seriesOccStatus.push({name: "Rented", data: all.rented});
                seriesOccStatus.push({name: "Both", data: all.both});
                generateBarChart('propertyOccStatus', all.district_name, seriesOccStatus, [GREEN, BLUE, ORANGE]);

                var seriesLandUsage = [];
                seriesLandUsage.push({name: "Commercial", data: all.commercial});
                seriesLandUsage.push({name: "Residential", data: all.residential});
                seriesLandUsage.push({name: "Special", data: all.special});
                generateBarChart('landUsageChart', all.district_name, seriesLandUsage, [GREEN, BLUE, ORANGE]);

            }
        }, function (reason) {
            alert("Something is Wrong!");
        });
    };

    // loadScript("jslibs/Highcharts/highcharts.js", highchart, function () {});
    loadScript("https://code.highcharts.com/4.2.3/highcharts.js", highchart, function () {
        highchart = true;
        loadScript("https://code.highcharts.com/4.2.3/modules/exporting.js", exporting, function () {
            exporting = true;
            $scope.getAllStats();
        });
    });
    // loadScript("jslibs/Highcharts/exporting.js", exporting, function () {});

    $.each(roles, function (i, obj) {
        // console.log(obj);
        if (obj.role_name === $state.current.name) $rootScope.selectedTab = i;
    });

    $scope.dropdownChange = function (entity, val) {
        if (entity === "district") $scope.circle = "All";
        console.log($scope.district, $scope.circle);
        if($scope.district==="All" && $scope.circle === "All"){
            $scope.getAllStats();
        }
    };

    function generateBarChart(div, xCats, series, colors) {
        return new Highcharts.chart(div, {
            chart: {
                type: 'column',
                width: xCats.length > 10 ? xCats.length*70 : null
            },
            title: {
                text: ''
            },
            credits: false,
            subtitle: {
                text: ''
            },
            xAxis: {
                categories: xCats,
                crosshair: true
            },
            yAxis: {
                min: 0,
                title: {
                    text: 'No of Properties'
                }
            },
            colors: colors,
            tooltip: {
                headerFormat: '<span style="font-size:10px">{point.key}</span><table>',
                pointFormat: '<tr><td style="color:{series.color};padding:0">{series.name}: </td>' +
                '<td style="padding:0"><b>{point.y}</b></td></tr>',
                footerFormat: '</table>',
                shared: true,
                useHTML: true
            },
            plotOptions: {
                column: {
                    pointPadding: 0.2,
                    borderWidth: 0
                },
                series:{
                    dataLabels: {
                        enabled: true,
                        format: '{point.y}'
                    }
                }
            },
            series: series
        });
    }

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
            $scope.circle = "All";
            // console.log(value.data);
        }, function (reason) {
            alert("Something is Wrong!");
        });
    };
});