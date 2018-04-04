<?php
/**
 * Created by PhpStorm.
 * User: Muhammad Umair
 * Date: 2/22/2016
 * Time: 1:15 PM
 */
//header("Cache-Control: no-store; must-revalidate");
//header("Cache-Control: no-store, no-cache, must-revalidate");

session_set_cookie_params(0, dirname($_SERVER['PHP_SELF']));
session_start();

include("connection.php");
require_once 'utils.php';

class CheckUser extends Connection
{

    function check($permUrl)
    {
//        if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 3600)) {
//            session_unset();
//            session_destroy();
//        }
        $path = utils::getPath();
        $cookieKey = utils::getCookieKey();
        $varName = $path;
//        if (isset($_SESSION[$varName])) {
//            return $_SESSION[$varName];
//        }
        if (isset($_COOKIE[$cookieKey])) {
            $sqlCheck = "select distinct on(p.perm_url) u.username, r.role_name, p.perm_desc, p.perm_url, p.role_desc from web.tbl_sessions s
left outer join web.tbl_users u on s.user_id = u.id
left outer join web.tbl_user_role ur on s.user_id = ur.user_id
left outer join web.tbl_roles r on ur.role_id = r.role_id
left outer join web.role_perm rp on ur.role_id = rp.role_id
left outer join web.tbl_permission p on rp.perm_id = p.perm_id
where s.session_id = $1 and p.perm_url like $2;";
            $resultCheck = pg_fetch_all(pg_query_params($sqlCheck, array($_COOKIE[$cookieKey], $permUrl)));
            if ($resultCheck == null) {
                $date_of_expiry = time() - 6000;
                setcookie($cookieKey, "anonymous", $date_of_expiry, $path);
//                $logout = new logout();
//                header("location: index.php");
                return false;
            } else {
//                $_SESSION['LAST_ACTIVITY'] = time();
//                $_SESSION[$varName] = $resultCheck;
//                return $_SESSION[$varName];
                return $resultCheck;
            }
        } else {
            return false;
        }
    }
}

//$log = new CheckUser();