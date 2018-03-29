<?php
/**
 * Created by PhpStorm.
 * User: Muhammad Umair
 * Date: 02/12/2017
 * Time: 5:52 PM
 */


include("connection.php");
include 'Mcrypt.php';

class Login extends Connection
{
    function __construct()
    {


        $mcrypt = new MCrypt();
        $username = $mcrypt->decrypt($_REQUEST['username']);
        $password = $mcrypt->decrypt($_REQUEST['password']);

        $sql = "select id as user_id from tbl_android_users where username = $1 and password = $2 limit 1;";

        $result = pg_query_params($sql, array($username, $password));
        $row = pg_fetch_row($result);
        if ($row) {
            echo json_encode(array("user_id" => $row[0], "result" => "success"));
        } else {
            echo json_encode(array("result" => "invalid"));
        }

        pg_free_result($result);
    }
}

$data = new Login();
$data->closeConnection();