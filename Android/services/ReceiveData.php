<?php
/**
 * Created by PhpStorm.
 * User: Muhammad Umair
 * Date: 13/12/2017
 * Time: 11:40 PM
 */
include("checkuser.php");
include 'Mcrypt.php';

class ReceiveData extends Connection
{
    function __construct()
    {

        $extraImgFolder = "/images/extras/";
        try {
//            error_reporting(E_ALL);
            $mcrypt = new MCrypt();
            $imei = $mcrypt->decrypt($_REQUEST['imei']);
            $surveyor_id = $mcrypt->decrypt($_REQUEST['surveyor_id']);
            $check = new CheckUser();
            $verified = $check->check($imei, $surveyor_id);
            if (!$verified) {
                echo json_encode(array("result" => "error", "msg" => "You are Unauthorized to send Data!"));
                return;
            }
//            else{
//                echo json_encode(array("result" => "Verified"));
//                return;
//            }


            $survey_datetime = $mcrypt->decrypt($_REQUEST['survey_datetime']);
            $lat = $mcrypt->decrypt($_REQUEST['lat']);
            $lng = $mcrypt->decrypt($_REQUEST['lng']);
            $respondant_name = $mcrypt->decrypt($_REQUEST['respondant_name']);
            $resp_rel_with_pr_owner = $mcrypt->decrypt($_REQUEST['resp_rel_with_pr_owner']);
            $pr_type = $mcrypt->decrypt($_REQUEST['pr_type']);
            $pin = $mcrypt->decrypt($_REQUEST['pin']);
            $district_name = $mcrypt->decrypt($_REQUEST['district_name']);
            $ratingarea_name = $mcrypt->decrypt($_REQUEST['ratingarea_name']);
            $tehsil_name = $mcrypt->decrypt($_REQUEST['tehsil_name']);
            $circle_name = $mcrypt->decrypt($_REQUEST['circle_name']);
            $locality_name = $mcrypt->decrypt($_REQUEST['locality_name']);
            $ward_name = $mcrypt->decrypt($_REQUEST['ward_name']);
            $block_name = $mcrypt->decrypt($_REQUEST['block_name']);
            $mohallah = $mcrypt->decrypt($_REQUEST['mohallah']);
            $punumber = $mcrypt->decrypt($_REQUEST['punumber']);
            $existing_serial = $mcrypt->decrypt($_REQUEST['existing_serial']);
            $owner_name = $mcrypt->decrypt($_REQUEST['owner_name']);
            $owner_cnic = $mcrypt->decrypt($_REQUEST['owner_cnic']);
            $type_of_property = $mcrypt->decrypt($_REQUEST['type_of_property']);
            $occupation_status = $mcrypt->decrypt($_REQUEST['occupation_status']);
            $tenate_name = $mcrypt->decrypt($_REQUEST['tenate_name']);
            $tenate_cnic = $mcrypt->decrypt($_REQUEST['tenate_cnic']);
            $rental_amount = $mcrypt->decrypt($_REQUEST['rental_amount']);
            $rented_since = $mcrypt->decrypt($_REQUEST['rented_since']);
            $date_of_construction = $mcrypt->decrypt($_REQUEST['date_of_construction']);
            $date_of_last_remodeling = $mcrypt->decrypt($_REQUEST['date_of_last_remodeling']);
            $landuse_commercial = $mcrypt->decrypt($_REQUEST['landuse_commercial']);
            $landuse_residential = $mcrypt->decrypt($_REQUEST['landuse_residential']);
            $landuse_special = $mcrypt->decrypt($_REQUEST['landuse_special']);
            $landuse_special_desc = $mcrypt->decrypt($_REQUEST['landuse_special_desc']);
            $landuse_other = $mcrypt->decrypt($_REQUEST['landuse_other']);
            $landuse_other_desc = $mcrypt->decrypt($_REQUEST['landuse_other_desc']);
            $electricity = $mcrypt->decrypt($_REQUEST['electricity']);
            $gas = $mcrypt->decrypt($_REQUEST['gas']);
            $telephone = $mcrypt->decrypt($_REQUEST['telephone']);
            $sewarage = $mcrypt->decrypt($_REQUEST['sewarage']);
            $sanitation = $mcrypt->decrypt($_REQUEST['sanitation']);
            $water_supply = $mcrypt->decrypt($_REQUEST['water_supply']);
            $road_street = $mcrypt->decrypt($_REQUEST['road_street']);
            $public_transport = $mcrypt->decrypt($_REQUEST['public_transport']);
            $front_of_property = $mcrypt->decrypt($_REQUEST['front_of_property']);
            $land_area = $mcrypt->decrypt($_REQUEST['land_area']);
            $building_area = $mcrypt->decrypt($_REQUEST['building_area']);
            $g_floor_self_area = $mcrypt->decrypt($_REQUEST['g_floor_self_area']);
            $g_floor_rented_area = $mcrypt->decrypt($_REQUEST['g_floor_rented_area']);
            $g_floor_occupation_status = $mcrypt->decrypt($_REQUEST['g_floor_occupation_status']);
            $excise_officer_name = $mcrypt->decrypt($_REQUEST['excise_officer_name']);
            $excise_officer_cnic = $mcrypt->decrypt($_REQUEST['excise_officer_cnic']);
            $condition_of_building = $mcrypt->decrypt($_REQUEST['condition_of_building']);
            $storeys = json_decode($mcrypt->decrypt($_REQUEST['storeys']));
            $extra_pictures = json_decode($mcrypt->decrypt($_REQUEST['extra_pictures']));
            $is_mock = $mcrypt->decrypt($_REQUEST['is_mock']);
            $basements = json_decode($mcrypt->decrypt($_REQUEST['basements']));


            $pictures = array();
            $picturePaths = $this->getImage("picture");


            if ($picturePaths[0] == "") {
                echo json_encode(array("result" => "error", "msg" => "Property Image does not exist!"));
                return;
            } else {
                array_push($pictures, $picturePaths[0]);
            }

            $sql = "INSERT INTO public.base_android_data(
	survey_datetime, surveyor_id, imei, lat,
    lng, img_path, respondant_name, resp_rel_with_pr_owner,
    pr_type, pin, district_name, ratingarea_name,
    tehsil_name, circle_name, locality_name, ward_name,
    block_name, mohallah, punumber, existing_serial,
    owner_name, owner_cnic, type_of_property,
    occupation_status, tenate_name, tenate_cnic,
    rental_amount, rented_since, date_of_construction, date_of_last_remodeling,
    landuse_commercial, landuse_residential, landuse_special, landuse_special_desc,
    landuse_other, landuse_other_desc, electricity, gas,
    telephone, sewarage, sanitation, water_supply,
    road_street, public_transport, front_of_property,
    land_area, building_area, g_floor_self_area,
    g_floor_occupation_status, g_floor_rented_area, condition_of_building, excise_officer_name,
    excise_officer_cnic, is_mock, geom)
	VALUES ((select substring ($1, 1,19))::timestamp , $2, $3, $4,
            $5, $6, $7, $8,
            (select id from tbl_pr_type where type = $9), $10, $11, $12,
            $13, $14, $15, $16,
            $17, $18, $19, $20,
            $21, $22, (select id from tbl_property_type where type = $23),
            (select id from tbl_occupation_type where occupation_type = $24), $25, $26,
            $27, $28, $29, $30,
            $31, $32, $33, $34,
            $35, $36, $37, $38,
            $39, $40, $41, $42,
            $43, $44, (select id from tbl_property_front where front_type = $45),
            $46, $47, $48,
            (select id from tbl_occupation_type where occupation_type = $49), $50, (select id from tbl_building_condition where condition = $51), $52,
            $53, $54, st_setsrid($55::geometry,4326))
            returning id;";

            pg_query("BEGIN");

            $resource = pg_query_params($sql, array(
                $survey_datetime, $surveyor_id, $imei, $lat,
                $lng, $picturePaths[1], $respondant_name, $resp_rel_with_pr_owner,
                $pr_type, $pin, $district_name, $ratingarea_name,
                $tehsil_name, $circle_name, $locality_name, $ward_name,
                $block_name, $mohallah, $punumber, $existing_serial,
                $owner_name, $owner_cnic, $type_of_property,
                $occupation_status, $tenate_name, $tenate_cnic,
                $rental_amount, $rented_since, $date_of_construction, $date_of_last_remodeling,
                $landuse_commercial, $landuse_residential, $landuse_special, $landuse_special_desc,
                $landuse_other, $landuse_other_desc, $electricity, $gas,
                $telephone, $sewarage, $sanitation, $water_supply,
                $road_street, $public_transport, $front_of_property,
                $land_area, $building_area, $g_floor_self_area,
                $g_floor_occupation_status, $g_floor_rented_area, $condition_of_building, $excise_officer_name,
                $excise_officer_cnic, $is_mock, "Point($lng $lat)"));

            if (!$resource) {
                pg_query("ROLLBACK");
                echo json_encode(array("result" => "error", "msg" => "Problem in Inserting Data to DB!"));
                $this->deleteImages($pictures);
                return;
            } else {
                $rowId = pg_fetch_row($resource)[0];

                if (sizeof($extra_pictures) !== 0) {
                    $extraPicturesQuery = "INSERT INTO public.tbl_extra_pictures(
	base_android_data_key, datetime, pic_path, description, index)
	VALUES ($1, $2, $3, $4, $5);";

                    foreach ($extra_pictures as $index => $value) {
                        $extraPicPaths = $this->getImage("image" . $index, $extraImgFolder);
                        if ($extraPicPaths[0] == "") {
                            echo json_encode(array("result" => "error", "msg" => "Image " . $index . " does not exist!"));
                            $this->deleteImages($pictures);
                            return;
                        } else {
                            array_push($pictures, $extraPicPaths[0]);
                        }

                        $extraPictureRes = pg_query_params($extraPicturesQuery, array($rowId, $value->datetime, $extraPicPaths[1], $value->desc, $value->index));
                        if (!$extraPictureRes) {
                            pg_query("ROLLBACK");
                            echo json_encode(array("result" => "error", "msg" => "Problem in Inserting Data to DB!"));
                            $this->deleteImages($pictures);
                            return;
                        }
                    }
                }

                if (sizeof($basements) !== 0) {
                    $extraBasementsQuery = "INSERT INTO public.tbl_extra_basements(
	base_android_data_key, occ_status, self_area, rented_area, index)
	VALUES ($1, (select id from tbl_occupation_type where occupation_type = $2), $3, $4, $5);";

                    foreach ($basements as $index => $value){
                        $extraBasementsRes = pg_query_params($extraBasementsQuery, array($rowId, $value->occ_status, (double)$value->self_area,
                            (double)$value->rented_area, $value->index));
                        if(!$extraBasementsRes){
                            pg_query("ROLLBACK");
                            echo json_encode(array("result" => "error", "msg" => "Problem in Inserting Data to DB!"));
                            $this->deleteImages($pictures);
                            return;
                        }
                    }

                }


                if (sizeof($storeys) !== 0) {
                    $extraStoreysQuery = "INSERT INTO public.tbl_extra_storeys(
	base_android_data_key, occ_status, self_area, rented_area, index)
	VALUES ($1, (select id from tbl_occupation_type where occupation_type = $2), $3, $4, $5);";

                    foreach ($storeys as $index => $value){
                        $extraStoreysRes = pg_query_params($extraStoreysQuery, array($rowId, $value->occ_status, (double)$value->self_area,
                            (double)$value->rented_area, $value->index));
                        if(!$extraStoreysRes){
                            pg_query("ROLLBACK");
                            echo json_encode(array("result" => "error", "msg" => "Problem in Inserting Data to DB!"));
                            $this->deleteImages($pictures);
                            return;
                        }
                    }

                }



                pg_query("COMMIT");
                echo json_encode(array("result" => "success", "server_id" => $rowId));
            }
        } catch (Exception $e) {
            echo json_encode(array("result" => "error", "msg" => "Unknown Server Error!"));
        }
    }

    function deleteImages($pics)
    {
        foreach ($pics as $pic) {
            try {
                unlink($pic);
            } catch (Exception $e) {
            }
        }
    }

    function getImage($pictureName, $folder = "/images/")
    {
        $paths = array("", "");
        try {
            $ext = ".jpg";
            $onlinePath = "http://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF'], 2) . $folder;
            $now = DateTime::createFromFormat('U.u', microtime(true));
            $now = $now->format("Y-m-d_H_i_s.u");
            $fileName = $pictureName . "_" . $now . mt_rand() . $ext;
            $target_file = ".." . $folder . $fileName;
            $onlinePath .= $fileName;
            if (move_uploaded_file($_FILES[$pictureName]["tmp_name"], $target_file)) {
                $paths[0] = $target_file;
                $paths[1] = $onlinePath;
            }
        } catch (Exception $e) {

        } finally {
            return $paths;
        }
    }
}

$data = new ReceiveData();
$data->closeConnection();