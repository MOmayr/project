var gmapLib;
var currentMarker;
app.controller('DataViewerController', function ($scope, $http, $mdDialog, $rootScope, $state) {

    var map;
    $.each(roles, function (i, obj) {
        // console.log(obj);
        if (obj.role_name === $state.current.name) $rootScope.selectedTab = i;
    });

    loadScript("https://maps.googleapis.com/maps/api/js?key=AIzaSyCpbQx_9w8XP1avJPlsiexDl_AGrk1FBYs", gmapLib, function () {
        gmapLib = true;
        initMap();
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
        enableRowSelection: true,
        exporterExcelFilename: 'Android Data.xlsx',
        exporterExcelSheetName: 'Data',
        minimumColumnSize: 150,
        rowTemplate: '<div ng-style="{\'background-color\': row.entity.backColor}" ng-click="grid.appScope.tableRowClick(row.entity)" ng-repeat="col in colContainer.renderedColumns track by col.colDef.name" class="ui-grid-cell" ui-grid-cell></div>',
        onRegisterApi: function (gridApi) {
            $scope.gridApi = gridApi;
        }

    };

    var prevRowIndex;
    $scope.tableRowClick = function (obj) {
        $scope.prData = {};
        var index = $scope.gridOptions.data.indexOf(obj);
        $scope.gridOptions.data[index].backColor = 'orange';
        if (prevRowIndex !== undefined && prevRowIndex !== index) {
            $scope.gridOptions.data[prevRowIndex].backColor = undefined;
        }
        $scope.prData.prImage = obj['Picture Url'];
        if(obj.Basements !== null){
            $scope.prData.basements = $.parseJSON(obj.Basements.replace(/'/g, '"'));
        }else $scope.prData.basements = null;

        if(obj.Floors !== null){
            $scope.prData.floors = $.parseJSON(obj.Floors.replace(/'/g, '"'));
        }else $scope.prData.floors = null;

        if(obj['Extra Pictures'] !== null){
            $scope.prData.ep = $.parseJSON(obj['Extra Pictures'].replace(/'/g, '"'));
        }else $scope.prData.ep = null;

        if(currentMarker) currentMarker.setMap(null);
        var position = {lat: parseFloat(obj['Latitude']), lng: parseFloat(obj['Longitude'])};
        currentMarker = new google.maps.Marker({
            position: position,
            map: map,
            title: obj['Pin']
        });
        map.panTo(position);
        map.setZoom(14);


        // console.log(obj);
        prevRowIndex = index;
    };

    $scope.dropdownChange = function (id, obj) {
        if (id === 'district') {
            $scope.circle = undefined;
        }
        // console.log($scope.district, $scope.circle);
    };

    $scope.getData = function () {
        $scope.inProgress = true;
        var circle = $scope.circle === "All" ? "%" : $scope.circle.name;
        // var circle = "%";
        var district = $scope.district === "All" ? "%" : $scope.district;
        $http({
            method: 'GET',
            url: "services/viewer/GetCircleData.php?district=" + district + "&circle=" + circle +
            "&startDate=" + $scope.selectedDateStart + "&endDate=" + $scope.selectedDateEnd
            // url: "services/viewer/GetCircleData.php?district=Lahore&circle=Abbot Road"
        }).then(function (value) {
            $scope.inProgress = false;
            if (value.data.error === "cout") {
                location.reload();
                return;
            }
            $scope.prData = undefined;
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
            $scope.inProgress = false;
            alert("Something is Wrong!");
        });
    };

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
            // $scope.getData();
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

    $scope.enlarge = function (path) {
        $mdDialog.show({
            clickOutsideToClose: true,
            template: '<md-dialog aria-label="Image">' +
            '  <md-dialog-content>' +
            '    <img src="' + path + '" height=100% width=100%>' +
            '  </md-dialog-content>' +
            '  <md-dialog-actions>' +
            '    <md-button ng-click="closeDialog()" class="md-primary md-raised">' +
            '      Close' +
            '    </md-button>' +
            '  </md-dialog-actions>' +
            '</md-dialog>',
            controller: function DialogController($scope, $mdDialog) {
                $scope.closeDialog = function () {
                    $mdDialog.hide();
                }
            }
        });
    };

    function initMap() {
        setTimeout(function () {
            console.log("map init");
            map = new google.maps.Map(document.getElementById('map'), {
                zoom: 6,
                center: new google.maps.LatLng(29.5, 71.617)
            });
        },500);
    }
});