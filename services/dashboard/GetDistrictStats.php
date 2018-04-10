<?php
/**
 * Created by PhpStorm.
 * User: Muhammad Umair
 * Date: 29/12/2017
 * Time: 12:35 AM
 */

include("../checkuser.php");

class GetDistrictStats extends Connection
{
    function __construct()
    {
        error_reporting(0);
        $check = new CheckUser();
        $verified = $check->check($_SERVER['PHP_SELF']);
        if (!$verified) {
            echo json_encode(array("error" => "cout"));
            return;
        }

        $district = $_REQUEST['district'];

        $sql = "with d as (select $1::text as district),
dap as (select distinct on (pin) * from base_android_data where pin is not null and district_name = (select d.district from d))
select f.circle_name as name, f.count as total, coalesce((g.count),null,0) as surveyed, f.count - coalesce(g.count, null,0)as unsurveyed, coalesce(un.count, null,0) as unassessed
,coalesce(l.count,null,0) as land, coalesce(g.count - l.count, null, 0) as openplot
,coalesce(self.count, null, 0) as self, coalesce(rented.count, null, 0) as rented, coalesce(oboth.count, null,0) as both
,coalesce(c.count, null,0) as commercial, coalesce(res.count, null,0) as residential, coalesce(special.count, null,0) as special
from (
    select circle_name, count(*) as count from tbl_raw_data where district_name = (select d.district from d) group by circle_name
) as f left outer join (
    select dap.circle_name, count(*) from dap group by dap.circle_name
) as g on f.circle_name = g.circle_name
left outer join (
    select circle_name, count(*) from base_android_data where pin is null and district_name = (select d.district from d) group by circle_name
)as un on f.circle_name = un.circle_name
left outer join (
    select dap.circle_name, count(*) from dap where dap.type_of_property = 1 group by dap.circle_name
) as l on f.circle_name = l.circle_name
left outer join (
    select dap.circle_name, count(*) from dap where dap.occupation_status = 1 group by dap.circle_name
) as self on f.circle_name = self.circle_name
left outer join (
    select dap.circle_name, count(*) from dap where dap.occupation_status = 2 group by dap.circle_name
) as rented on f.circle_name = rented.circle_name
left outer join (
    select dap.circle_name, count(*) from dap where dap.occupation_status = 3 group by dap.circle_name
) as oboth on f.circle_name = oboth.circle_name
left outer join (
    select dap.circle_name, count(*) from dap where dap.landuse_commercial = true group by dap.circle_name
) as c on f.circle_name = c.circle_name
left outer join (
    select dap.circle_name, count(*) from dap where dap.landuse_residential = true group by dap.circle_name
) as res on f.circle_name = res.circle_name
left outer join (
    select dap.circle_name, count(*) from dap where dap.landuse_special = true group by dap.circle_name
) as special on f.circle_name = special.circle_name;";

        $result = pg_query_params($sql, array($district));
        $stats = pg_fetch_all($result);
        echo json_encode($stats, JSON_NUMERIC_CHECK);
        pg_free_result($result);
    }
}

$info = new GetDistrictStats();
$info->closeConnection();