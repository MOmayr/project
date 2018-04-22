<?php
header("Cache-Control: no-store");
//echo $_SERVER['PHP_SELF'];
//return;
include("../../services/checkuser.php");
$checkUser = new CheckUser();
$check = $checkUser->check($_SERVER['PHP_SELF']);
if (!$check) return;
?>
<div ng-controller="DataViewerController" ng-cloak>
    <form name="myForm">
        <md-card flex="100" layout="row" layout-wrap layout-align="center center" class="div-margins"
                 ng-init="getDistrictCircle()">
            <div flex="20" flex-xs="50" layout-align="center center" layout="row">
                <md-input-container flex="100">
                    <label>Select District</label>
                    <md-select ng-model="district" ng-change="dropdownChange('district', district)" required>
                        <md-option ng-repeat="obj in districts" ng-value="obj.name">
                            {{obj.name}}
                        </md-option>
                    </md-select>
                </md-input-container>
            </div>

            <div flex="20" flex-xs="50" layout-align="center center" layout="row">
                <md-input-container flex="100">
                    <label>Select Circle</label>
                    <md-select ng-model="circle" ng-change="dropdownChange('circle', circle)" required>
                        <md-option value="All">All</md-option>
                        <md-option ng-repeat="obj in circles|filter:{district_name: district}" ng-value="obj">
                            {{obj.name}}
                        </md-option>
                    </md-select>
                </md-input-container>
            </div>

            <div layout="row" flex="20" layout-align="center center">
                <h4>Starting Date: </h4>
                <md-datepicker ng-model="startDate" md-max-date="maxDate"
                               md-placeholder="Enter date" name="dateField"
                               md-open-on-focus>
                </md-datepicker>
                <h5>{{selectedDateStart}}</h5>
            </div>

            <div layout="row" flex="20" layout-align="center center">
                <h4>End Date: </h4>
                <md-datepicker ng-model="endDate" md-max-date="maxDate"
                               md-placeholder="Enter date" name="dateField"
                               md-open-on-focus>
                </md-datepicker>
                <h5>{{selectedDateEnd}}</h5>
            </div>
            <div flex layout="row" layout-align="center center">
                <md-button ng-disabled="myForm.$invalid" class="md-primary md-raised"
                           ng-click="getData()">
                    Get Excel
                </md-button>
            </div>
        </md-card>
    </form>

    <div flex="100" layout="row" layout-wrap="" class="border div-margins">
        <div flex="33" layout="row">
            <md-button flex="100" class="md-primary md-raised progress-md-button">
                Total Uploads : {{stats.uploads}}
            </md-button>
        </div>
        <div flex="33" layout="row">
            <md-button flex="100" class="md-primary md-raised progress-md-button">
                Unique Pin Numbers : {{stats.unique_pins}}
            </md-button>
        </div>
        <div flex="33" layout="row">
            <md-button flex="100" class="md-accent md-raised progress-md-button">
                Unassessed : {{stats.unassessed}}
            </md-button>
        </div>
        <div style="height: 10px"></div>
    </div>

    <div flex="100" layout="row" layout-wrap="" class="div-margins">
        <div flex="100" flex-sm="100" flex-xs="100" class="padding">
            <div layout="column">
                <div class="toolbar" layout="row">
                    <md-card flex="100" layout="row" layout-align="center center" md-colors="{background: 'primary'}">
                        Data Grid
                    </md-card>
                </div>
                <md-whiteframe id="propertyCountChart" class="md-whiteframe-3dp"
                               style="padding-top: 1px; overflow: hidden; height: 400px">
                    <div ui-grid="gridOptions" ui-grid-resize-columns ui-grid-exporter style="height: 400px"
                         ng-if="gridOptions.columnDefs"></div>
                </md-whiteframe>
            </div>
        </div>
    </div>
</div>