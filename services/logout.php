<?php
//session_set_cookie_params(0, '/brick_kiln_dashboard');
//session_start();
//session_unset();
//session_destroy();
//header("location: ../index.php");
include("connection.php");
class logout extends connection
{
    function __construct()
    {
        $path = '/noblemms';
        if (isset($_COOKIE['auth_token'])) {
            $date_of_expiry = time() - 60;
            $logout = "delete from tbl_sessions where session_id = $1;";
            pg_query_params($logout, array($_COOKIE['auth_token']));
            setcookie("auth_token", "anonymous", $date_of_expiry, $path);
            header("location:../index.php");
            exit;
        }
        exit;
    }
}

$logout = new logout();
$logout->closeConnection();