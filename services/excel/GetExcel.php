<?php

/**
 * Created by PhpStorm.
 * User: Muhammad Umair
 * Date: 11/14/2017
 * Time: 3:17 PM
 */

include("../checkuser.php");

class GetExcel extends Connection
{

    function __construct()
    {
        $checkUser = new CheckUser();
        $check = $checkUser->check($_SERVER['PHP_SELF']);
        if (!$check) {
            echo json_encode(array("error" => "cout"));
            return;
        }

        $district = $_REQUEST['district'];
        $circle = $_REQUEST['circle'];
        $startDate = $_REQUEST['startDate'];
        $endDate = $_REQUEST['endDate'];

        $sql = "select * from base_android_view where \"District Name\" like $1 and \"Circle Name\" like $2 and \"Survey Datetime\"::date >= $3
and \"Survey Datetime\"::date <= $4;";

        $result = pg_query_params($sql, array($district, $circle, $startDate, $endDate));
        $all = pg_fetch_all($result);
        $valsArray = array();
        $colsArray = array();


        for ($i = 0; $i < pg_num_fields($result); $i++) {
            array_push($colsArray, pg_field_name($result, $i));
        }

        if ($all) {
            foreach ($all as $row) {
                array_push($valsArray, array_values($row));
            }
        }
        echo json_encode(array("vals" => $valsArray, "keys" => $colsArray));
        pg_free_result($result);
    }
}

$obj = new GetExcel();
$obj->closeConnection();