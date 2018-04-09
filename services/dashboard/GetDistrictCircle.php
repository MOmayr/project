<?php
/**
 * Created by PhpStorm.
 * User: Muhammad Umair
 * Date: 29/12/2017
 * Time: 12:35 AM
 */

include("../checkuser.php");
class GetDistrictCircle extends Connection
{
    function __construct(){
//        error_reporting(0);
        $check = new CheckUser();
        $verified = $check->check($_SERVER['PHP_SELF']);
        if (!$verified) {
            echo json_encode(array("error" => "cout"));
            return;
        }
        $arr = array();

        $sql = "select distinct district_name as name from tbl_circles order by district_name;";
        $result = pg_query($sql);
        $districts = pg_fetch_all($result);

        $sql = "select circle_name as name, district_name from tbl_circles order by circle_name;";
        $resultQ = pg_query($sql);
        $circles = pg_fetch_all($resultQ);
        echo json_encode(array("districts"=>$districts, "circles"=>$circles));

        pg_free_result($result);
        pg_free_result($resultQ);
    }
}
$info = new GetDistrictCircle();
$info->closeConnection();