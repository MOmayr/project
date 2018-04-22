<?php

/**
 * Created by PhpStorm.
 * User: Muhammad Umair
 * Date: 11/14/2017
 * Time: 3:17 PM
 */

include("../checkuser.php");

class GetCircleData extends Connection
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

        $sql = "select * from base_android_view_conflict where \"District Name\" like $1 and \"Circle Name\" like  $2 and \"Survey Datetime\"::date >= $3
and \"Survey Datetime\"::date <= $4 order by \"Survey Datetime\" desc;";

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

        $sqlStats = "with data as (select $1::text as district, $2::text as circle)
select 
(select count(*) from base_android_data where district_name like (select district from data) and circle_name like (select circle from data)) as uploads, 
(select count(f.*) from(select distinct on (pin) pin from base_android_data where pin is not null and district_name like (select district from data) and circle_name like (select circle from data)) as f) as unique_pins, 
(select count(*) from base_android_data where pr_type = 2 and district_name like (select district from data) and circle_name like (select circle from data)) as unassessed;";
        $resultStats = pg_query_params($sqlStats, array($district, $circle));
        $rowStats = pg_fetch_assoc($resultStats);

        echo json_encode(array("stats"=>$rowStats, "vals" => $valsArray, "keys" => $colsArray));
        pg_free_result($result);
    }
}

$obj = new GetCircleData();
$obj->closeConnection();