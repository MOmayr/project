<?php
/**
 * Created by PhpStorm.
 * User: Muhammad Umair
 * Date: 02/12/2017
 * Time: 5:52 PM
 */

include("checkuser.php");

class SyncData extends Connection
{
    function __construct()
    {
        $imei = $_REQUEST['imei'];
        $user_id = $_REQUEST['user_id'];
        $checkUser = new CheckUser();
        $check = $checkUser->check($imei, $user_id);
        if (!$check) {
            echo "invalid";
            return;
        }

        $sql = "select pin,district_name,tehsil_name,ratingarea_name,locality_name,ward_name,block_name,circle_name from tbl_raw_data  
where district_name = $1 and circle_name = $2;";

        $result = pg_query_params($sql, array($check['district'], $check['circle']));
        $all = pg_fetch_all($result);
        $valsArray = array();
        $colsArray = array();

        for($i = 0; $i< pg_num_fields($result); $i++) {
            array_push($colsArray, pg_field_name($result, $i));
        }

        foreach ($all as $row) {
            array_push($valsArray, array_values($row));
        }
        echo json_encode(array("vals"=>$valsArray, "keys"=>$colsArray), JSON_NUMERIC_CHECK);
        pg_free_result($result);
    }
}

$data = new SyncData();
$data->closeConnection();