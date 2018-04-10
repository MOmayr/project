<?php
/**
 * Created by PhpStorm.
 * User: Muhammad Umair
 * Date: 29/12/2017
 * Time: 12:35 AM
 */

include("../checkuser.php");

class GetAllStats extends Connection
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

        $sql = "with dap as (select distinct on (pin) * from base_android_data where pin is not null)
select f.district_name as name, f.count as total, coalesce((g.count),null,0) as surveyed, f.count - coalesce(g.count, null,0)as unsurveyed, coalesce(un.count, null,0) as unassessed
,coalesce(l.count,null,0) as land, coalesce(g.count - l.count, null, 0) as openplot,
coalesce(self.count, null, 0) as self, coalesce(rented.count, null, 0) as rented, coalesce(oboth.count, null,0) as both,
coalesce(c.count, null,0) as commercial, coalesce(res.count, null,0) as residential, coalesce(special.count, null,0) as special
from (
    select district_name, count(*) as count from tbl_raw_data group by district_name
) as f left outer join (
    select dap.district_name, count(*) from dap group by dap.district_name
) as g on f.district_name = g.district_name
left outer join (
    select district_name, count(*) from base_android_data where pin is null group by district_name
)as un on f.district_name = un.district_name
left outer join (
    select dap.district_name, count(*) from dap where dap.type_of_property = 1 group by dap.district_name
) as l on f.district_name = l.district_name
left outer join (
    select dap.district_name, count(*) from dap where dap.occupation_status = 1 group by dap.district_name
) as self on f.district_name = self.district_name
left outer join (
    select dap.district_name, count(*) from dap where dap.occupation_status = 2 group by dap.district_name
) as rented on f.district_name = rented.district_name
left outer join (
    select dap.district_name, count(*) from dap where dap.occupation_status = 3 group by dap.district_name
) as oboth on f.district_name = oboth.district_name
left outer join (
    select dap.district_name, count(*) from dap where dap.landuse_commercial = true group by dap.district_name
) as c on f.district_name = c.district_name
left outer join (
    select dap.district_name, count(*) from dap where dap.landuse_residential = true group by dap.district_name
) as res on f.district_name = res.district_name
left outer join (
    select dap.district_name, count(*) from dap where dap.landuse_special = true group by dap.district_name
) as special on f.district_name = special.district_name;";
        $result = pg_query($sql);
        $stats = pg_fetch_all($result);
        echo json_encode($stats, JSON_NUMERIC_CHECK);
        pg_free_result($result);
    }
}

$info = new GetAllStats();
$info->closeConnection();