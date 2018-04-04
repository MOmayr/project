<?php
header("Cache-Control: no-store");
include("../services/checkuser.php");
$checkUser = new CheckUser();
$check = $checkUser->check("%");
if($check) return;
?>
<div ng-controller="LoginController">
<md-content layout="row" layout-align="space-around" layout-padding="layout-padding" ng-cloak="" class="login-form">
    <md-card flex="flex" flex-gt-sm="50" flex-gt-md="33" class="md-whiteframe-6dp">
        <md-toolbar>
            <div class="md-toolbar-tools">
                <h1><b>Please Login!</b></h1>
            </div>
        </md-toolbar>
        <md-card-content>
            <form name="Form" action="services/login.php" method="post" role="form">
                <md-input-container class="md-block">
                    <label>Username</label>
                    <input type="text" name="username" ng-model="username" required=""/>
                    <div ng-messages="Form.username.$error" role="alert" ng-hide="username">
                        <div ng-message="required" class="my-message">Please enter your username.</div>
                    </div>
                </md-input-container>
                <md-input-container class="md-block">
                    <label>Password</label>
                    <input type="password" name="password" ng-model="password" required=""/>
                    <div ng-messages="Form.password.$error" role="alert" ng-hide="password">
                        <div ng-message="required" class="my-message">Please enter your password.</div>
                    </div>
                </md-input-container>
                <button ng-disabled="!Form.$valid" type="submit" class="md-button md-raised md-primary">&nbsp Login &nbsp</button>
            </form>
        </md-card-content>
    </md-card>
</md-content>
</div>