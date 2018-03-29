<?php
/**
 * Created by PhpStorm.
 * User: Muhammad Umair
 * Date: 2/22/2016
 * Time: 1:15 PM
 */
//header("Cache-Control: no-store; must-revalidate");
//header("Cache-Control: no-store, no-cache, must-revalidate");
include("connection.php");
class CheckUser extends Connection
{

    function check($permUrl)
    {
        $path = '/noblemms';
        if (isset($_COOKIE['auth_token'])) {
            $sqlCheck = "select distinct on(p.perm_url) u.username, r.role_name, p.perm_desc, p.perm_url, p.role_desc from tbl_sessions s
left outer join tbl_users u on s.user_id = u.id
left outer join tbl_user_role ur on s.user_id = ur.user_id
left outer join tbl_roles r on ur.role_id = r.role_id
left outer join role_perm rp on ur.role_id = rp.role_id
left outer join tbl_permission p on rp.perm_id = p.perm_id
where s.session_id = $1 and p.perm_url like $2;";
            $resultCheck = pg_fetch_all(pg_query_params($sqlCheck, array($_COOKIE['auth_token'], $permUrl)));
            if ($resultCheck == null) {
                $date_of_expiry = time() - 6000;
                setcookie("auth_token", "anonymous", $date_of_expiry, $path);
//                $logout = new logout();
//                header("location: index.php");
                return false;
            } else {
                return $resultCheck;
            }
        } else {
            return false;
        }
    }
}

$log = new CheckUser();