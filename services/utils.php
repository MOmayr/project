<?php
/**
 * Created by PhpStorm.
 * User: umair
 * Date: 3/5/18
 * Time: 4:09 PM
 */

class utils {

    public static function getCookieKey(){
        return "uhy_auth_token";
    }

    public static function getPath(){
        return str_replace('/services', '', dirname($_SERVER['PHP_SELF']));
    }
}

//$u = new utils();
//$u->test();