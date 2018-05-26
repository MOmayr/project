var highchart, exporting, boost;
var TOTAL = "#5E6B6D";
var SURVEYED = "#00B7A9";
var UNSURVEYED = "#FF625E";
var UNASSESSED = "#F5C729";
var BARCHART = "column";
var PIECHART = "pie";
var note = "Unassessed Properties are subject to Review & Discussion!";
app.controller('DashboardController', function ($scope, $http, $rootScope, $state) {

    $.each(roles, function (i, obj) {
        // console.log(obj);
        if (obj.role_name === $state.current.name) $rootScope.selectedTab = i;
    });

    $scope.startDate = new Date("2018-04-01");
    $scope.endDate = new Date();
    $scope.maxDate = new Date();

    $scope.dropdownChange = function (entity, val) {
        if (entity === "district") $scope.circle = "All";
        // if($scope.district === "All") $scope.circle = "All";
        console.log($scope.district, $scope.circle);
        if ($scope.district === "All" && $scope.circle === "All") {
            $scope.getAllStats(500);
        } else if ($scope.district !== "All" && $scope.circle === "All") {
            $scope.getDistrictStats(700);
        }else if($scope.district !== "All" && $scope.circle !== "All"){
            $scope.getCircleStats(400);
        }
    };

    $scope.getAllStats = function (interval) {
        $http({
            method: 'GET',
            url: "services/dashboard/GetAllStats.php?startDate=" + $scope.selectedDateStart + "&endDate=" + $scope.selectedDateEnd
        }).then(function (value) {
            if (value.data.error === "cout") {
                location.reload();
                return;
            }
            // var data = value.data;
            console.log(value.data);
            var all = performInitials(value.data.stats);
            $scope.tl = value.data.tl;
            generateAllOrDistrictChart(all, interval);
        }, function (reason) {
            alert("Something is Wrong!");
        });
    };

    $scope.getDistrictStats = function (interval) {
        $http({
            method: 'GET',
            url: "services/dashboard/GetDistrictStats.php?district=" + $scope.district +
            "&startDate=" + $scope.selectedDateStart + "&endDate=" + $scope.selectedDateEnd
        }).then(function (value) {
            if (value.data.error === "cout") {
                location.reload();
                return;
            }
            // var data = value.data;
            var all = performInitials(value.data.stats);
            $scope.tl = value.data.tl;
            generateAllOrDistrictChart(all, interval);
            console.log(value.data)
        }, function (reason) {
            alert("Something is Wrong!");
        });
    };

    $scope.getCircleStats = function (interval) {
        $http({
            method: 'GET',
            url: "services/dashboard/GetCircleStats.php?district=" + $scope.district + "&circle="+$scope.circle.name +
            "&startDate=" + $scope.selectedDateStart + "&endDate=" + $scope.selectedDateEnd
        }).then(function (value) {
            if (value.data.error === "cout") {
                location.reload();
                return;
            }
            // var data = value.data;
            var all = performInitials(value.data.stats);
            $scope.tl = value.data.tl;
            generateCircleChart(all, interval);
            console.log(value.data)
        }, function (reason) {
            alert("Something is Wrong!");
        });
    };

    $scope.$watch('startDate', function (newVal) {
        try {
            $scope.selectedDateStart = newVal.getFullYear() + "-" + (newVal.getMonth() + 1) + "-" + newVal.getDate();
            $scope.dropdownChange();
        } catch (E) {
            // $scope.selectedDateStart = undefined;
        }
    });

    $scope.$watch('endDate', function (newVal) {
        try {
            $scope.selectedDateEnd = newVal.getFullYear() + "-" + (newVal.getMonth() + 1) + "-" + newVal.getDate();
            $scope.dropdownChange();
        } catch (E) {
            // $scope.selectedDateEnd = undefined;
        }
    });

    function generateAllOrDistrictChart(all, interval) {

        setTimeout(function () {
            var seriesProperty = [];
            seriesProperty.push({name: "Total Properties", data: all.total});
            seriesProperty.push({name: "Surveyed Properties", data: all.surveyed});
            seriesProperty.push({name: "Un-Surveyed Properties", data: all.unsurveyed});
            generateBarChart('propertyCountChart', all.name, seriesProperty, [TOTAL, SURVEYED, UNSURVEYED], BARCHART, 'Assessed Properties Statistics',
                null);

            setTimeout(function () {

                var seriesPrType = [{
                    name: 'Properties',
                    colorByPoint: true,
                    data: [{
                        name: 'Assessed',
                        y: sum(all.surveyed)
                    }, {
                        name: 'Unassessed',
                        y: sum(all.unassessed)
                    }]
                }];
                // seriesPrType.push({name: "Properties", data: {name: "Assessed Properties", y: sum(all.surveyed)}});
                // seriesPrType.push({name: "Unassessed Properties", data: [sum(all.unassessed)]});
                generateBarChart('propertyTypeChart', all.name, seriesPrType, [SURVEYED, UNASSESSED], PIECHART, 'Assessed / Unassessed Properties',
                    note);

                // setTimeout(function () {
                //     var seriesOccStatus = [];
                //     seriesOccStatus.push({name: "Self", data: all.self});
                //     seriesOccStatus.push({name: "Rented", data: all.rented});
                //     seriesOccStatus.push({name: "Both", data: all.both});
                //     generateBarChart('propertyOccStatus', all.name, seriesOccStatus, [SURVEYED, TOTAL, UNASSESSED], BARCHART);
                //
                //     setTimeout(function () {
                //         var seriesLandUsage = [];
                //         seriesLandUsage.push({name: "Commercial", data: all.commercial});
                //         seriesLandUsage.push({name: "Residential", data: all.residential});
                //         seriesLandUsage.push({name: "Special", data: all.special});
                //         generateBarChart('landUsageChart', all.name, seriesLandUsage, [SURVEYED, TOTAL, UNASSESSED], BARCHART);
                //     }, interval);
                // }, interval);
            }, interval)
        }, 0);
    }

    function generateCircleChart(all, interval) {

        setTimeout(function () {
            var seriesProperty = [{
                name : "Properties",
                colorByPoint: true,
                data : []
            }];
            seriesProperty[0].data.push({name: "Total Properties", y: sum(all.total)});
            seriesProperty[0].data.push({name: "Surveyed Properties", y: sum(all.surveyed)});
            seriesProperty[0].data.push({name: "Un-Surveyed Properties", y: sum(all.unsurveyed)});
            generateBarChart('propertyCountChart', all.name, seriesProperty, [TOTAL, SURVEYED, UNSURVEYED], PIECHART, 'Assessed Properties Statistics',
                null);

            setTimeout(function () {
                // var seriesPrType = [];
                // seriesPrType.push({name: "Land (Covered & Uncovered)", data: all.land});
                // seriesPrType.push({name: "Open Plots", data: all.openplot});
                // generateBarChart('propertyTypeChart', all.name, seriesPrType, [SURVEYED, TOTAL], BARCHART);

                // var seriesPrType = [];
                var seriesPrType = [{
                    name: 'Survey Status',
                    colorByPoint: true,
                    data: [{
                        name: 'Assessed',
                        y: sum(all.surveyed)
                    }, {
                        name: 'Unassessed',
                        y: sum(all.unassessed)
                    }]
                }];
                // seriesPrType.push({name: "Properties", data: {name: "Assessed Properties", y: sum(all.surveyed)}});
                // seriesPrType.push({name: "Unassessed Properties", data: [sum(all.unassessed)]});
                generateBarChart('propertyTypeChart', all.name, seriesPrType, [SURVEYED, UNASSESSED], PIECHART, 'Assessed / Unassessed Properties',
                    note);

                // setTimeout(function () {
                //     var seriesOccStatus = [{
                //         name : "Occupation Status",
                //         colorByPoint: true,
                //         data : []
                //     }];
                //     seriesOccStatus[0].data.push({name: "Self", y: sum(all.self)});
                //     seriesOccStatus[0].data.push({name: "Rented", y: sum(all.rented)});
                //     seriesOccStatus[0].data.push({name: "Both", y: sum(all.both)});
                //     generateBarChart('propertyOccStatus', all.name, seriesOccStatus, [SURVEYED, TOTAL, UNASSESSED], PIECHART);
                //
                //     setTimeout(function () {
                //         var seriesLandUsage = [{
                //             name : "Land-use Status",
                //             colorByPoint: true,
                //             data : []
                //         }];
                //         seriesLandUsage[0].data.push({name: "Commercial", y: sum(all.commercial)});
                //         seriesLandUsage[0].data.push({name: "Residential", y: sum(all.residential)});
                //         seriesLandUsage[0].data.push({name: "Special", y: sum(all.special)});
                //         generateBarChart('landUsageChart', all.name, seriesLandUsage, [SURVEYED, TOTAL, UNASSESSED], PIECHART);
                //     }, interval);
                // }, interval);
            }, interval)
        }, 0);
    }

    function performInitials(data) {
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
        $scope.total = sum(all.total);
        // $scope.both = sum(all.both);
        // $scope.commercial = sum(all.commercial);
        // $scope.land = sum(all.land);
        // $scope.openplot = sum(all.openplot);
        // $scope.rented = sum(all.rented);
        // $scope.residential = sum(all.residential);
        // $scope.self = sum(all.self);
        // $scope.special = sum(all.special);
        $scope.surveyed = sum(all.surveyed);
        $scope.unassessed = sum(all.unassessed);
        $scope.unsurveyed = sum(all.unsurveyed);
        return all;
    }

    // loadScript("jslibs/Highcharts/highcharts.js", highchart, function () {});
    loadScript("https://code.highcharts.com/4.2.3/highcharts.js", highchart, function () {
        highchart = true;
        loadScript("https://code.highcharts.com/4.2.3/modules/exporting.js", exporting, function () {
            exporting = true;
            $scope.getAllStats();
        });
    });
    // loadScript("jslibs/Highcharts/exporting.js", exporting, function () {});


    function generateBarChart(div, xCats, series, colors, chartType, title, subtitle) {
        return new Highcharts.chart(div, {
            chart: {
                type: chartType,
                width: chartType === "column" ? xCats.length > 15 ? xCats.length * 70 < window.innerWidth ? window.innerWidth :xCats.length * 70 : null: null
            },
            title: {
                text: title
            },
            credits: false,
            subtitle: {
                text: subtitle
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
                    borderWidth: 0,
                    stacking: 'normal'
                },
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: false
                    },
                    showInLegend: true
                },
                series: {
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

function sum(arr) {
    var sum = 0;
    arr.forEach(function (elem, i) {
        sum += elem;
    });
    return sum;
}