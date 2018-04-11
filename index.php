<?php
header("Cache-Control: no-store");
include("services/checkuser.php");
$checkUser = new CheckUser();
$check = $checkUser->check("%");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>UHY - UIPT TPV</title>
    <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0">
    <!--    <link rel="stylesheet" href="jslibs/AngularJS/css/angular-material.min.css">-->
    <link rel="stylesheet" href="http://ajax.googleapis.com/ajax/libs/angular_material/1.1.7/angular-material.min.css">

    <script>
        var roles = <?php echo json_encode($check);?>;
    </script>
    <link rel="stylesheet" href="styles/index.css">

    <script src="https://code.jquery.com/jquery-1.12.3.min.js"
            integrity="sha256-aaODHAgvwQW1bFOGXMeX+pC4PZIPsvn2h1sArYOhgXQ=" crossorigin="anonymous"></script>

    <!--    <script src="jslibs/jquery-1.12.3.min.js"></script>-->

    <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.6.6/angular.min.js"></script>
    <!--    <script src="jslibs/AngularJS/angular.min.js"></script>-->
    <!---->
    <script src="jslibs/AngularJS/angular-ui-router.js"></script>

    <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.6.6/angular-animate.min.js"></script>
    <!--    <script src="jslibs/AngularJS/angular-animate.min.js"></script>-->
    <!---->
    <!--            <script src="jslibs/AngularJS/angular-messages.min.js"></script>-->
    <!---->
    <script src="http://ajax.googleapis.com/ajax/libs/angularjs/1.6.6/angular-aria.min.js"></script>
    <!--    <script src="jslibs/AngularJS/angular-aria.min.js"></script>-->

    <!--     Angular Material Library -->
    <script src="http://ajax.googleapis.com/ajax/libs/angular_material/1.1.7/angular-material.min.js"></script>
    <!--    <script src="jslibs/AngularJS/angular-material.min.js"></script>-->

    <script type="text/javascript" src="scripts/index.js"></script>

    <?php if ($check) {
        echo '<script src="scripts/controllers/controllers.php"></script>';

    } else {

        echo '<script type="text/javascript" src="scripts/controllers/LoginController.js" ></script>';
    }
    ?>

</head>
<body ng-app="App" ng-cloak>

<md-button class="md-icon-button" aria-label="Scroll" onclick="topFunction()" id="scrollToTopBtn">
    <md-tooltip>
        Scroll To Top
    </md-tooltip>
    <!--    <md-icon md-svg-icon="icons/keyboard_arrow_up.svg" md-colors="{color : 'primary'}" style="width: 48px"></md-icon>-->
    <!--    <i class="material-icons" style="font-size: 48px"  md-colors="{color : 'primary'}">keyboard_arrow_up</i>-->
    <svg height="48" viewBox="0 0 24 24" width="48" xmlns="http://www.w3.org/2000/svg" class="material-icons">
        <path d="M7.41 15.41L12 10.83l4.59 4.58L18 14l-6-6-6 6z"/>
        <path d="M0 0h24v24H0z" fill="none"/>
    </svg>
</md-button>


<md-card class="div-margins border" id="header"
         layout="row" flex="100" layout-wrap layout-align="center center">
    <div flex="30" flex-xs="33" flex-sm="33">
        <img src="images/uhy.jpg" class="header-image">
    </div>

    <div flex="40" flex-xs="100" flex-sm="100" layout="row" layout-align="center center" flex-order-xs="4"
         flex-order-sm="3">
        <div style="font-size: 2.1vw">UHY</div>
    </div>
    <div flex="30" flex-xs="33" flex-sm="33" layout="row" layout-align="center right">
    </div>
</md-card>
<?php if ($check) {

    echo '
    <a style="position: absolute; right: 20px; top: 10px" href="services/logout.php">
        <img src="icons/logout.png" height="24" width="24">
        <md-tooltip><?php echo $check[\'user_name\']?> (Logout)</md-tooltip>
    </a>
    ';
    echo '
<div ng-controller="MainController">
<md-tabs md-dynamic-height md-border-bottom md-selected="$root.selectedTab">
    <md-tab ui-sref="{{r.role_name}}" label="{{r.role_name}}" ng-repeat="r in roles">
    </md-tab>
</md-tabs>
</div>';
} ?>
<div ui-view></div>

</body>
</html>