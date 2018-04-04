<?php
//session_set_cookie_params(0, '/brick_kiln_dashboard');
//session_start();
//session_unset();
//session_destroy();
//header("location: ../index.php");
session_start();
include("connection.php");
require_once 'utils.php';
class logout extends connection
{
    function __construct()
    {
        session_unset();
        session_destroy();
        if (isset($_COOKIE[utils::getCookieKey()])) {
            $date_of_expiry = time() - 60;
            $logout = "delete from web.tbl_sessions where session_id = $1;";
            pg_query_params($logout, array($_COOKIE[utils::getCookieKey()]));
            setcookie(utils::getCookieKey(), "anonymous", $date_of_expiry, utils::getPath());
            header("location:../");
            return;
        }
    }
}

$logout = new logout();
$logout->closeConnection();