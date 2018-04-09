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
        <div flex="60" flex-sm="100" flex-xs="100" layout="row" layout-wrap="">
            <div flex="100" layout="row">
                <md-button flex="100" class="md-primary md-raised progress-md-button">
                    Total Properties : {{}}
                </md-button>
            </div>
            <div flex="33" layout="row">
                <md-button flex="100" class="md-primary md-raised progress-md-button">
                    Surveyed :
                </md-button>
            </div>
            <div flex="33" layout="row">
                <md-button flex="100" class="md-primary md-raised progress-md-button">
                    Un-Surveyed :
                </md-button>
            </div>
            <div flex="33" layout="row">
                <md-button flex="100" class="md-accent md-raised progress-md-button">
                    Unassessed :
                </md-button>
            </div>
        </div>
        <div flex="40" flex-sm="100" flex-xs="100" layout="row" layout-wrap="">
            <div flex="100" layout="row">
                <md-button flex="100" class="md-primary md-raised progress-md-button">
                    Property Type Status
                </md-button>
            </div>
            <div flex="50" flex="100" layout="row">
                <md-button flex="100" class="md-primary md-raised progress-md-button">
                    Land :
                </md-button>
            </div>
            <div flex="50" flex="100" layout="row">
                <md-button flex="100" class="md-primary md-raised progress-md-button">
                    Open Plot :
                </md-button>
            </div>
        </div>
    </div>

    <div flex="100" layout="row" layout-wrap="" class="border div-margins">
        <div flex="50" flex-sm="100" flex-xs="100" layout="row" layout-wrap="">
            <div flex="100" layout="row">
                <md-button flex="100" class="md-primary md-raised progress-md-button">
                    Occupation Status
                </md-button>
            </div>
            <div flex="33" layout="row">
                <md-button flex="100" class="md-primary md-raised progress-md-button">
                    Self :
                </md-button>
            </div>
            <div flex="33" layout="row">
                <md-button flex="100" class="md-primary md-raised progress-md-button">
                    Rented :
                </md-button>
            </div>
            <div flex layout="row">
                <md-button flex="100" class="md-primary md-raised progress-md-button">
                    Both :
                </md-button>
            </div>
        </div>
        <div flex="50" flex-sm="100" flex-xs="100" layout="row" layout-wrap="">
            <div flex="100" layout="row">
                <md-button flex="100" class="md-primary md-raised progress-md-button">
                    Occupation Status
                </md-button>
            </div>
            <div flex="33" layout="row">
                <md-button flex="100" class="md-primary md-raised progress-md-button">
                    Self :
                </md-button>
            </div>
            <div flex="33" layout="row">
                <md-button flex="100" class="md-primary md-raised progress-md-button">
                    Rented :
                </md-button>
            </div>
            <div flex="33" layout="row">
                <md-button flex="100" class="md-primary md-raised progress-md-button">
                    Both :
                </md-button>
            </div>
        </div>
    </div>

    <div flex="100" layout="row" layout-wrap="" class="div-margins">
        <div flex="50" flex-sm="100" flex-xs="100" class="padding">
            <div layout="column">
                <div class="toolbar" layout="row">
                    <md-card flex="100" layout="row" layout-align="center center" md-colors="{background: 'primary'}">
                        Assessed Properties Statistics
                    </md-card>
                </div>
                <md-whiteframe id="propertyCountChart" class="md-whiteframe-3dp div-sizes "
                               style="padding-top: 1px; overflow-x: auto">
<!--                    <div id="propertyCountChart"></div>-->
                </md-whiteframe>
            </div>
        </div>

        <div flex="50" flex-sm="100" flex-xs="100" class="padding">
            <div layout="column">
                <div class="toolbar" layout="row">
                    <md-card flex="100" layout="row" layout-align="center center" md-colors="{background: 'primary'}">
                        Assessed Property Types
                    </md-card>
                </div>
                <md-whiteframe id="propertyTypeChart" class="md-whiteframe-3dp div-sizes "
                               style="padding-top: 1px;overflow-x: auto">
                    <div></div>
                </md-whiteframe>
            </div>
        </div>
    </div>

    <div flex="100" layout="row" layout-wrap="" class="div-margins">
        <div flex="50" flex-sm="100" flex-xs="100" class="padding">
            <div layout="column">
                <div class="toolbar" layout="row">
                    <md-card flex="100" layout="row" layout-align="center center" md-colors="{background: 'primary'}">
                        Assessed Properties Occupation Status
                    </md-card>
                </div>
                <md-whiteframe id="propertyOccStatus" class="md-whiteframe-3dp div-sizes "
                               style="padding-top: 1px; overflow-x: auto">
                    <!--                    <div id="propertyCountChart"></div>-->
                </md-whiteframe>
            </div>
        </div>

        <div flex="50" flex-sm="100" flex-xs="100" class="padding">
            <div layout="column">
                <div class="toolbar" layout="row">
                    <md-card flex="100" layout="row" layout-align="center center" md-colors="{background: 'primary'}">
                        Assessed Properties Land Usage
                    </md-card>
                </div>
                <md-whiteframe id="landUsageChart" class="md-whiteframe-3dp div-sizes "
                               style="padding-top: 1px;overflow-x: auto; ">
                    <div></div>
                </md-whiteframe>
            </div>
        </div>
    </div>
    <div style="height: 10px"></div>
</div>