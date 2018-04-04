<?php
header("Cache-Control: no-store");
//echo $_SERVER['PHP_SELF'];
//return;
include("../../services/checkuser.php");
$checkUser = new CheckUser();
$check = $checkUser->check($_SERVER['PHP_SELF']);
if (!$check) return;
?>
<div ng-controller="AdminController" ng-cloak>

    <div flex="100" layout="row" layout-wrap>

        <div flex="15">
            <!--        <div flex="25" ng-if="!$mdMedia('sm')">-->
            <div layout="column" flex class="div-margins">
                <div layout="row">
                    <md-card class="toolbar" flex="100" layout="row" layout-align="center center"
                             md-colors="{background : 'primary'}">
                        TOC
                        <md-tooltip>Table of Contents</md-tooltip>
                    </md-card>
                </div>
                <md-whiteframe class="md-whiteframe-3dp top-div-size"
                               style="overflow-y: auto;padding-top: 1px;">
                    <md-content class="fade-in">
                        <div layout="column">
                            <div flex="100" layout="row" layout-align="center center">
                                <md-button class="md-accent md-raised" flex="50" ng-click="showUserDialog(undefined, 'Create ', $event)"
                                           style="text-transform: none">
                                    Create User
                                </md-button>
                            </div>
                            <div flex="100" layout="row" layout-align="center center">
                                <md-button flex="50" class="md-accent md-raised" ng-click="showUserDialog(currentUser, 'Update ' ,$event)"
                                           style="text-transform: none" ng-disabled="!currentUser">
                                    Update User
                                </md-button>
                            </div>

                            <div flex="100" layout="row" layout-align="center center">
                                <md-button flex="50" class="md-warn md-raised" ng-click="showUpdateUserDialog($event)"
                                           style="text-transform: none" ng-disabled="!currentUser">
                                    Delete User
                                </md-button>
                            </div>
                        </div>
                    </md-content>
                </md-whiteframe>
            </div>
        </div>

        <div flex>
            <div layout="column" flex class="div-margins">
                <div layout="row">
                    <md-card class="toolbar" flex="100" layout="row" layout-align="center center"
                             md-colors="{background : 'primary'}">
                        Users
                    </md-card>
                </div>
                <md-whiteframe id="users" class="md-whiteframe-3dp top-div-size">
                    <md-content style="margin: 5px" ng-init="getUsers()">
                        <div style="overflow-y: auto;" flex="100">
                            <table id="factorTable">
                                <tr>
                                    <th>Sr.</th>
                                    <th>User ID</th>
                                    <th>Password</th>
                                    <th>Name</th>
                                    <th>Mobile Number</th>
                                    <th>CNIC</th>
                                    <th>Address</th>
                                    <th>Access</th>
                                    <th>IMEI</th>
                                    <th>District</th>
                                    <th>Circle</th>
                                </tr>
                                <tr ng-repeat="u in users" style="cursor: pointer" ng-click="selectUser(u)"
                                    md-colors="u === currentUser? {background:  'primary'} : ''">
                                    <td>{{$index+1}}</td>
                                    <td>{{u.username}}</td>
                                    <td>{{u.password}}</td>
                                    <td>{{u.name}}</td>
                                    <td>{{u.mobile_number}}</td>
                                    <td>{{u.cnic}}</td>
                                    <td>{{u.address}}</td>
                                    <td>{{u.access ? 'Yes' : 'No'}}</td>
                                    <td>{{u.imei}}</td>
                                    <td>{{u.district}}</td>
                                    <td>{{u.circle}}</td>
                                </tr>
                            </table>
                        </div>
                    </md-content>
                </md-whiteframe>
            </div>
        </div>
    </div>

    <div style="height: 10px"></div>
</div>