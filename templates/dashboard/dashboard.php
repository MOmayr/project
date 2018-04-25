<?php
header("Cache-Control: no-store");
//echo $_SERVER['PHP_SELF'];
//return;
include("../../services/checkuser.php");
$checkUser = new CheckUser();
$check = $checkUser->check($_SERVER['PHP_SELF']);
if (!$check) return;
?>
<div ng-controller="DashboardController" ng-cloak>
    <md-card flex="100" layout="row" layout-wrap layout-align="center center" class="div-margins"
             ng-init="getDistrictCircle()">
        <div flex="33" flex-xs="50" layout-align="center center" layout="row">
            <md-input-container flex="100">
                <label>Select District</label>
                <md-select ng-model="district" ng-change="dropdownChange('district', district)">
                    <md-option ng-repeat="obj in districts" ng-value="obj.name">
                        {{obj.name}}
                    </md-option>
                </md-select>
            </md-input-container>
        </div>

        <div flex="33" flex-xs="50" layout-align="center center" layout="row">
            <md-input-container flex="100">
                <label>Select Circle</label>
                <md-select ng-model="circle" ng-change="dropdownChange('circle', circle)">
                    <md-option value="All">All</md-option>
                    <md-option ng-repeat="obj in circles|filter:{district_name: district}" ng-value="obj">
                        {{obj.name}}
                    </md-option>
                </md-select>
            </md-input-container>
        </div>
    </md-card>

    <div flex="100" layout="row" layout-wrap="" class="border div-margins">
        <div flex="50" flex-sm="100" flex-xs="100" layout="row" layout-wrap="">
            <div flex="100" layout="row">
                <md-button flex="100" class="md-primary md-raised progress-md-button">
                    Total Properties : {{total}}
                </md-button>
            </div>
            <div flex="50" layout="row">
                <md-button flex="100" class="md-primary md-raised progress-md-button">
                    Surveyed : {{surveyed + unassessed}}
                </md-button>
            </div>
            <div flex="50" layout="row">
                <md-button flex="100" class="md-primary md-raised progress-md-button">
                    Un-Surveyed : {{unsurveyed}}
                </md-button>
            </div>
            <!--            <div flex="33" layout="row">-->
            <!--                <md-button flex="100" class="md-accent md-raised progress-md-button">-->
            <!--                    Unassessed : {{unassessed}}-->
            <!--                </md-button>-->
            <!--            </div>-->
        </div>
        <div flex="50" flex-sm="100" flex-xs="100" layout="row" layout-wrap="">
            <div flex="100" layout="row">
                <md-button flex="100" class="md-primary md-raised progress-md-button">
                    Properties Assessment Status
                </md-button>
            </div>
            <div flex="50" flex="100" layout="row">
                <md-button flex="100" class="md-primary md-raised progress-md-button">
                    Assessed : {{surveyed}}
                </md-button>
            </div>
            <div flex="50" flex="100" layout="row">
                <md-button flex="100" class="md-primary md-raised progress-md-button">
                    Unassessed : {{unassessed}}*
                </md-button>
            </div>
        </div>
    </div>

    <!--    <div flex="100" layout="row" layout-wrap="" class="border div-margins">-->
    <!--        <div flex="50" flex-sm="100" flex-xs="100" layout="row" layout-wrap="">-->
    <!--            <div flex="100" layout="row">-->
    <!--                <md-button flex="100" class="md-primary md-raised progress-md-button">-->
    <!--                    Occupation Status-->
    <!--                </md-button>-->
    <!--            </div>-->
    <!--            <div flex="33" layout="row">-->
    <!--                <md-button flex="100" class="md-primary md-raised progress-md-button">-->
    <!--                    Self : {{self}}-->
    <!--                </md-button>-->
    <!--            </div>-->
    <!--            <div flex="33" layout="row">-->
    <!--                <md-button flex="100" class="md-primary md-raised progress-md-button">-->
    <!--                    Rented : {{rented}}-->
    <!--                </md-button>-->
    <!--            </div>-->
    <!--            <div flex layout="row">-->
    <!--                <md-button flex="100" class="md-primary md-raised progress-md-button">-->
    <!--                    Both : {{both}}-->
    <!--                </md-button>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--        <div flex="50" flex-sm="100" flex-xs="100" layout="row" layout-wrap="">-->
    <!--            <div flex="100" layout="row">-->
    <!--                <md-button flex="100" class="md-primary md-raised progress-md-button">-->
    <!--                    Land-use Status-->
    <!--                </md-button>-->
    <!--            </div>-->
    <!--            <div flex="33" layout="row">-->
    <!--                <md-button flex="100" class="md-primary md-raised progress-md-button">-->
    <!--                    Commercial : {{commercial}}-->
    <!--                </md-button>-->
    <!--            </div>-->
    <!--            <div flex="33" layout="row">-->
    <!--                <md-button flex="100" class="md-primary md-raised progress-md-button">-->
    <!--                    Residential : {{residential}}-->
    <!--                </md-button>-->
    <!--            </div>-->
    <!--            <div flex="33" layout="row">-->
    <!--                <md-button flex="100" class="md-primary md-raised progress-md-button">-->
    <!--                    Special Properties : {{special}}-->
    <!--                </md-button>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!---->
    <div flex="100" layout="row" layout-wrap="" class="div-margins">
        <div flex="100" flex-sm="100" flex-xs="100" class="padding">
            <div layout="column">
                <!--                <div class="toolbar" layout="row">-->
                <!--                    <md-card flex="100" layout="row" layout-align="center center" md-colors="{background: 'primary'}">-->
                <!--                        Assessed Properties Statistics-->
                <!--                    </md-card>-->
                <!--                </div>-->
                <md-whiteframe id="propertyCountChart" class="md-whiteframe-3dp div-sizes"
                               style="padding-top: 1px; overflow-x: auto; overflow-y: hidden">
                    <!--                    <div id="propertyCountChart"></div>-->
                </md-whiteframe>
            </div>
        </div>

        <div flex="50" flex-sm="100" flex-xs="100" class="padding">
            <div layout="column">
                <!--                <div class="toolbar" layout="row">-->
                <!--                    <md-card flex="100" layout="row" layout-align="center center" md-colors="{background: 'primary'}">-->
                <!--                        Assessed / Unassessed Properties-->
                <!--                    </md-card>-->
                <!--                </div>-->
                <md-whiteframe id="propertyTypeChart" class="md-whiteframe-3dp div-sizes"
                               style="padding-top: 1px;overflow-x: auto;overflow-y: hidden">
                    <div></div>
                </md-whiteframe>
            </div>
        </div>

        <div flex="50" flex-sm="100" flex-xs="100" class="padding">
            <div layout="column">
                <md-whiteframe id="timelineChart" class="md-whiteframe-3dp div-sizes "
                               style="padding-top: 1px; overflow-y: auto">
                    <div flex="100" layout="row" layout-align="center center"
                         style="font-size: 18px; font-family: Lucida Grande, Lucida Sans Unicode, Arial, Helvetica, sans-serif; margin: 4px">
                        Timeline
                    </div>
                    <div style="overflow: auto">
                        <table id="factorTable">
                            <tr>
                                <th>Name</th>
                                <th>Date</th>
                                <th>Uploads</th>
                            </tr>
                            <tr ng-repeat="e in tl">
                                <td>{{e.name}}</td>
                                <td>{{e.date}}</td>
                                <td>{{e.count}}</td>
                            </tr>
                        </table>
                    </div>
                </md-whiteframe>
            </div>
        </div>
    </div>

    <!--    <div flex="100" layout="row" layout-wrap="" class="div-margins">-->
    <!--        <div flex="50" flex-sm="100" flex-xs="100" class="padding">-->
    <!--            <div layout="column">-->
    <!--                <div class="toolbar" layout="row">-->
    <!--                    <md-card flex="100" layout="row" layout-align="center center" md-colors="{background: 'primary'}">-->
    <!--                        Assessed Properties Occupation Status-->
    <!--                    </md-card>-->
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!---->
    <!--        <div flex="50" flex-sm="100" flex-xs="100" class="padding">-->
    <!--            <div layout="column">-->
    <!--                <div class="toolbar" layout="row">-->
    <!--                    <md-card flex="100" layout="row" layout-align="center center" md-colors="{background: 'primary'}">-->
    <!--                        Assessed Properties Land Usage-->
    <!--                    </md-card>-->
    <!--                </div>-->
    <!--                <md-whiteframe id="landUsageChart" class="md-whiteframe-3dp div-sizes "-->
    <!--                               style="padding-top: 1px;overflow-x: auto; overflow-y: hidden">-->
    <!--                    <div></div>-->
    <!--                </md-whiteframe>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <div style="height: 10px"></div>
</div>