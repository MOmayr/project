<?php
?>
<md-dialog aria-label="User Dialog">
    <form ng-cloak name="classesForm">
        <md-toolbar>
            <div class="md-toolbar-tools">
                <h2>{{mode}} User</h2>
                <span flex></span>
                <md-button class="md-icon-button" ng-click="cancel()">
                    <md-icon md-svg-src="icons/close.svg" aria-label="Close dialog"></md-icon>
                </md-button>
            </div>
        </md-toolbar>

        <md-dialog-content style="min-width: 500px">
            <div class="md-dialog-content">
                <table id="factorTable">
                    <tr>
                        <th>User ID</th>
                        <td>
                            <md-input-container style="margin-bottom: -30px">
                                <input ng-model="user.username" name="username" type="text" required="">
                            </md-input-container>
                        </td>
                    </tr>
                    <tr>
                        <th>Password</th>
                        <td>
                            <md-input-container style="margin-bottom: -30px">
                                <input ng-model="user.password" name="password" type="text" required="">
                            </md-input-container>
                        </td>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <td>
                            <md-input-container style="margin-bottom: -30px">
                                <input ng-model="user.name" name="name" type="text" required="">
                            </md-input-container>
                        </td>
                    </tr>
                    <tr>
                        <th>Mobile Number</th>
                        <td>
                            <md-input-container style="margin-bottom: -30px">
                                <input ng-model="user.mobile_number" name="mobile_number" type="tel" required>
                            </md-input-container>
                        </td>
                    </tr>
                    <tr>
                        <th>CNIC</th>
                        <td>
                            <md-input-container style="margin-bottom: -30px">
                                <input ng-model="user.cnic" name="cnic" type="text" maxlength="15" required>
                            </md-input-container>
                        </td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td>
                            <md-input-container style="margin-bottom: -30px">
                                <input ng-model="user.address" name="address" type="text" required>
                            </md-input-container>
                        </td>
                    </tr>
                    <tr>
                        <th>Access</th>
                        <td>
                            <md-checkbox ng-model="user.access">
                                {{user.access ? 'Allowed' : 'Blocked'}}
                            </md-checkbox>
                        </td>
                    </tr>
                    <tr>
                        <th>IMEI</th>
                        <td>
                            <md-input-container style="margin-bottom: -30px">
                                <input ng-model="user.imei" name="imei" type="number" required="" maxlength="15"
                                       minlength="15" required>
                            </md-input-container>
                        </td>
                    </tr>
                    <tr>
                        <th>District</th>
                        <td>
                            <md-input-container style="margin-bottom: -30px">
                                <input ng-model="user.district" name="district" type="text" required="">
                            </md-input-container>
                        </td>
                    </tr>
                    <tr>
                        <th>Circle</th>
                        <td>
                            <md-input-container style="margin-bottom: -30px">
                                <input ng-model="user.circle" name="circle" type="text" required="">
                            </md-input-container>
                        </td>
                    </tr>
                </table>
            </div>
        </md-dialog-content>

        <md-dialog-actions layout="row">

            <md-button ng-click="createUpdateUser()" class="md-primary" ng-disabled="classesForm.$invalid">
                OK
            </md-button>
        </md-dialog-actions>
    </form>
</md-dialog>
