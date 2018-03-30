<?php
/**
 * Created by PhpStorm.
 * User: Muhammad Umair
 * Date: 2/22/2016
 * Time: 1:15 PM
 */
//header("Cache-Control: no-store; must-revalidate");
//header("Cache-Control: no-store, no-cache, must-revalidate");
include("connection.php");

class CheckUser extends Connection
{

    function check($imei, $user_id)
    {
        if ($imei) {
            $sqlCheck = "select * from tbl_android_users where imei = $1 and id = $2 and access is true;";
            $resultCheck = pg_fetch_array(pg_query_params($sqlCheck, array($imei, $user_id)));
            if ($resultCheck == null) {
                return false;
            } else {
                return $resultCheck;
            }
        } else {
            return false;
        }
    }
}

$log = new CheckUser();
//$log->closeConnection();