<?php
header("Cache-Control: no-store");
//echo $_SERVER['PHP_SELF'];
//return;
include("../../services/checkuser.php");
$checkUser = new CheckUser();
$check = $checkUser->check($_SERVER['PHP_SELF']);
if (!$check) return;
?>
<div ng-controller="ExcelController" ng-cloak>

    <div flex="100" layout="row" layout-wrap layout-align="center center">

        <div flex="40" flex-sm="100" flex-xs="100">
            <!--        <div flex="25" ng-if="!$mdMedia('sm')">-->
            <div layout="column" flex class="div-margins">
                <div layout="row">
                    <md-card class="toolbar" flex="100" layout="row" layout-align="center center"
                             md-colors="{background : 'primary'}">
                        Selection Panel
                        <!--                        <md-tooltip>Selection Panel</md-tooltip>-->
                    </md-card>
                </div>
                <md-whiteframe class="md-whiteframe-3dp top-div-size"
                               style="overflow-y: auto;padding-top: 1px;">
                    <md-content class="fade-in" ng-init="getDistrictCircle()">
                        <form name="myForm">
<!--                            <div my-dropdown="district" callback="dropdownChange('district', district)"-->
<!--                                 array="obj in districts"-->
<!--                                 flex="100"-->
<!--                                 layout="row" class="md-margin" label="Select District">-->
<!--                            </div>-->

                            <div flex="100" layout="row" class="md-margin">
                                <md-input-container flex="100">
                                    <label>Select District</label>
                                    <md-select ng-model="district" ng-change="dropdownChange('district', district)" required>
<!--                                        <md-option value="All"><em>All</em></md-option>-->
                                        <md-option ng-repeat="obj in districts" ng-value="obj">
                                            {{obj.name}}
                                        </md-option>
                                    </md-select>
                                </md-input-container>
                            </div>

                            <div my-dropdown="circle" callback="dropdownChange('circle', circle)"
                                 array="obj in circles|filter:{district_name:district.name}" flex="100"
                                 layout="row" class="md-margin" label="Select Circle" ng-disabled="true">
                            </div>


                            <div layout="row" flex="100" layout-align="center center">
                                <h4>Starting Date: </h4>
                                <md-datepicker ng-model="startDate" md-max-date="maxDate"
                                               md-placeholder="Enter date" name="dateField"
                                               md-open-on-focus>
                                </md-datepicker>
                                <h5>{{selectedDateStart}}</h5>
                            </div>

                            <div layout="row" flex="100" layout-align="center center">
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
                        </form>
                    </md-content>
                </md-whiteframe>
            </div>
        </div>
    </div>

    <div style="height: 10px"></div>
</div>