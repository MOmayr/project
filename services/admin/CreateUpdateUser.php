<?php

/**
 * Created by PhpStorm.
 * User: Muhammad Umair
 * Date: 11/14/2017
 * Time: 3:17 PM
 */

include("../checkuser.php");

class CreateUpdateUser extends Connection
{
    function __construct()
    {
        $checkUser = new CheckUser();
        $check = $checkUser->check($_SERVER['PHP_SELF']);
        if (!$check) {
            echo json_encode(array("error" => "cout"));
            return;
        }

        $mode = $_REQUEST['mode'];
        $user = json_decode($_REQUEST['user']);

        if ($mode == "Create ") {
            $sql = "INSERT INTO public.tbl_android_users(
	username, password, name, mobile_number, cnic, address, access, imei, district, circle)
	VALUES ($1, $2, $3, $4, $5, $6, $7, $8, $9, $10) returning id;";
            $result = pg_query_params($sql, array($user->username, $user->password, $user->name, $user->mobile_number, $user->cnic,
                $user->address, $user->access, $user->imei, $user->district, $user->circle));
            $row = pg_fetch_row($result);
            echo ($row[0]);
            pg_free_result($result);
        } elseif ($mode == "Update ") {
            $sql = "UPDATE public.tbl_android_users
	SET username=$1, password=$2, name=$3, mobile_number=$4, cnic=$5, address=$6, access=$7, imei=$8, district=$9, circle=$10
	WHERE id = $11 returning id;";

            $result = pg_query_params($sql, array($user->username, $user->password, $user->name, $user->mobile_number, $user->cnic,
                $user->address, $user->access, $user->imei, $user->district, $user->circle, $user->id));
            $row = pg_fetch_row($result);
            echo ($row[0]);
            pg_free_result($result);
        }
    }
}

$obj = new CreateUpdateUser();
$obj->closeConnection();