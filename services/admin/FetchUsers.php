<?php

/**
 * Created by PhpStorm.
 * User: Muhammad Umair
 * Date: 11/14/2017
 * Time: 3:17 PM
 */

include("../checkuser.php");
class FetchUsers extends Connection
{
    function __construct()
    {
        $checkUser = new CheckUser();
        $check = $checkUser->check($_SERVER['PHP_SELF']);
        if(!$check){
            echo json_encode(array("error"=>"cout"));
            return;
        }

        $sql = "select * from tbl_android_users order by mobile_number;";

        $result = pg_query_params($sql, array());
        $all = pg_fetch_all($result);
        if($all){
            echo json_encode($all, JSON_NUMERIC_CHECK);
        }else echo json_encode(array());
        pg_free_result($result);
    }
}

$obj = new FetchUsers();
$obj->closeConnection();