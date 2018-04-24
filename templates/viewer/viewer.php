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
                <md-button ng-disabled="myForm.$invalid || inProgress" class="md-primary md-raised"
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
                    <div ui-grid="gridOptions" ui-grid-exporter ui-grid-resize-columns style="height: 400px"
                         ng-if="gridOptions.columnDefs"></div>
                </md-whiteframe>
            </div>
        </div>

        <div flex="50" flex-sm="100" flex-xs="100" class="padding">
            <div layout="column">
                <div class="toolbar" layout="row">
                    <md-card flex="100" layout="row" layout-align="center center" md-colors="{background: 'primary'}">
                        Property's Data
                    </md-card>
                </div>
                <md-whiteframe id="propertyCountChart" class="md-whiteframe-3dp"
                               style="padding-top: 1px; overflow-y: auto; height: 400px">
                    <div ng-if="prData">
                        <h3 flex layout="row" layout-align="center center">Basements Info</h3>
                        <table id="factorTable">
                            <tr>
                                <th>Basement No</th>
                                <th>Occupation Type</th>
                                <th>Rented Area</th>
                                <th>Self Area</th>
                            </tr>
                            <tr ng-repeat="b in prData.basements">
                                <td>{{b['Basement No']}}</td>
                                <td>{{b['Occupation Type']}}</td>
                                <td>{{b['Rented Area']}}</td>
                                <td>{{b['Self Area']}}</td>
                            </tr>
                        </table>

                        <h3 flex layout="row" layout-align="center center">Floors Info</h3>
                        <table id="factorTable">
                            <tr>
                                <th>Floor No</th>
                                <th>Occupation Type</th>
                                <th>Rented Area</th>
                                <th>Self Area</th>
                            </tr>
                            <tr ng-repeat="f in prData.floors">
                                <td>{{f['Floor No']}}</td>
                                <td>{{f['Occupation Type']}}</td>
                                <td>{{f['Rented Area']}}</td>
                                <td>{{f['Self Area']}}</td>
                            </tr>
                        </table>

                        <h3 flex layout="row" layout-align="center center">Pictures</h3>
                        <table id="factorTable">
                            <tr>
                                <th>Picture Type</th>
                                <th>Picture</th>
                                <th>Description</th>
                            </tr>
                            <tr>
                                <td>Property Image</td>
                                <td>
                                    <md-tooltip md-direction="top">Click image to Enlarge</md-tooltip>
                                    <img src="{{prData.prImage}}" height="100"
                                         ng-click="enlarge(prData.prImage)"/>
                                </td>
                                <td></td>
                            </tr>
                            <tr ng-repeat="p in prData.ep">
                                <td>Extra Image {{p['number']}}</td>
                                <td>
                                    <md-tooltip md-direction="top">Click image to Enlarge</md-tooltip>
                                    <img src="{{p['Pic Url']}}" height="100"
                                         ng-click="enlarge(p['Pic Url'])"/>
                                </td>
                                <td>{{p['description']}}</td>
                            </tr>
                        </table>
                    </div>
                </md-whiteframe>
            </div>
        </div>

        <div flex="50" flex-sm="100" flex-xs="100" class="padding">
            <div layout="column">
                <div class="toolbar" layout="row">
                    <md-card flex="100" layout="row" layout-align="center center" md-colors="{background: 'primary'}">
                        Map
                    </md-card>
                </div>
                <md-whiteframe id="map" class="md-whiteframe-3dp"
                               style="padding-top: 1px; overflow: hidden; height: 400px">
                </md-whiteframe>
            </div>
        </div>
    </div>
    <div style="height: 10px"></div>
</div>