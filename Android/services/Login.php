<?php
/**
 * Created by PhpStorm.
 * User: Muhammad Umair
 * Date: 02/12/2017
 * Time: 5:52 PM
 */


include ("connection.php");
class Login extends Connection
{
    function __construct()
    {

        $username = $_REQUEST['username'];
        $password = $_REQUEST['password'];

        $sql = "select id as user_id from tbl_android_users where username = $1 and password = $2;";

        $result = pg_query_params($sql, array($username, $password));
        if($result){
            $row = pg_fetch_object($result);
           echo json_encode($row);
        }

        pg_free_result($result);
    }
}

$data = new Login();
$data->closeConnection();