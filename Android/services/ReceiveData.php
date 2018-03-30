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

        try {
            error_reporting(0);
            $mcrypt = new MCrypt();
            $imei = $mcrypt->decrypt($_REQUEST['imei']);
            $surveyor_id = $mcrypt->decrypt($_REQUEST['surveyor_id']);
            $check = new CheckUser();
            $verified = $check->check($imei, $surveyor_id);
            if (!$verified) {
                echo json_encode(array("result" => "unauthorized"));
                return;
            }
//            else{
//                echo json_encode(array("result" => "Verified"));
//                return;
//            }


            $mobile_datetime = str_replace("_", ":", $_POST['mobile_datetime']);
            $date_reporting = $_POST['date_reporting'];
            $mine_id = $_POST['mine_id'];
            $quarry_id = $_POST['quarry_id'];
            $dth_holes = json_decode($_POST['dth_holes']);
            $diamond_wire_saw_cuts = json_decode($_POST['diamond_wire_saw_cuts']);
            $excavator_start_reading = $_POST['excavator_start_reading'];
            $excavator_stop_reading = $_POST['excavator_stop_reading'];
            $compressor_start_reading = $_POST['compressor_start_reading'];
            $compressor_stop_reading = $_POST['compressor_stop_reading'];
            $generator_start_reading = $_POST['generator_start_reading'];
            $generator_stop_reading = $_POST['generator_stop_reading'];
            $wheel_loader_start_reading = $_POST['wheel_loader_start_reading'];
            $wheel_loader_stop_reading = $_POST['wheel_loader_stop_reading'];
            $brief_progress = $_POST['brief_progress'];
            $problem_issue = $_POST['problem_issue'];
            $diesel_expense = (int)$_POST['diesel_expense'];
            $kitchen_expense = (int)$_POST['kitchen_expense'];
            $misc_description = $_POST['misc_description'];
            $misc_expense = (int)$_POST['misc_expense'];

            $pic1Paths = $this->getImage("picture1");
            $pic2Paths = $this->getImage("picture2");
            $pic3Paths = $this->getImage("picture3");


            if ($pic1Paths[0] == "" || $pic2Paths[0] == "" || $pic3Paths[0] == "") {
                echo json_encode(array("error" => "Problem in uploading pictures"));
                return;
            }

            $sql = "INSERT INTO public.tbl_mine_management_main(imei, mobile_datetime, mine_id, quarry_id, no_of_dth_holes, 
            number_of_diamond_wire_saw_cuts, excavator_start_reading, excavator_stop_reading, 
            compressor_start_reading, compressor_stop_reading, generator_start_reading, 
            generator_stop_reading, wheel_loader_start_reading, wheel_loader_stop_reading, 
            brief_progress, problem_issue, diesel_expense, 
            kitchen_expense, misc_description, misc_expense, pic1, pic2, 
            pic3, date_reporting, total_expense_quarry)
    VALUES ($1, $2, (select mine_id from tbl_mine where mine_name  = $3),
            (select id from tbl_quarry where quarry = $4), $5, $6, $7, $8, $9, $10,
            $11, $12, $13, $14, $15, $16, $17, $18, $19,
            $20, $21, $22, $23, $24, $25) returning id;";

            pg_query("BEGIN");

            $resource = pg_query_params($sql, array($imei, $mobile_datetime, $mine_id, $quarry_id, sizeof($dth_holes), sizeof($diamond_wire_saw_cuts),
                $excavator_start_reading, $excavator_stop_reading, $compressor_start_reading, $compressor_stop_reading, $generator_start_reading,
                $generator_stop_reading, $wheel_loader_start_reading, $wheel_loader_stop_reading,
                $brief_progress, $problem_issue, $diesel_expense,
                $kitchen_expense, $misc_description, $misc_expense, $pic1Paths[1], $pic2Paths[1], $pic3Paths[1], $date_reporting,
                $kitchen_expense + $diesel_expense + $misc_expense));

            if (!$resource) {
                pg_query("ROLLBACK");
                echo json_encode(array("error" => "Problem in Inserting Data to DB!"));
                $this->deleteImage($pic1Paths[0]);
                $this->deleteImage($pic2Paths[0]);
                $this->deleteImage($pic3Paths[0]);
                return;
            } else {
                $rowId = pg_fetch_row($resource)[0];

                if (sizeof($dth_holes) !== 0) {
                    $dthQuery = "INSERT INTO public.tbl_mine_management_dth_holes(mine_management_main_table_id, dth_hole_number_id, dth_hole_depth) 
VALUES ($1,$2,$3);";

                    foreach ($dth_holes as $index => $value) {
                        $dthRes = pg_query_params($dthQuery, array($rowId, $value->number, $value->depth));
                        if (!$dthRes) {
                            pg_query("ROLLBACK");
                            echo json_encode(array("error" => "Problem in Inserting Data to DB!"));
                            $this->deleteImage($pic1Paths[0]);
                            $this->deleteImage($pic2Paths[0]);
                            $this->deleteImage($pic3Paths[0]);
                            return;
                        }
                    }
                }

                if (sizeof($diamond_wire_saw_cuts) !== 0) {
                    $dwscQuery = "INSERT INTO public.tbl_mine_management_wire_saw_cuts(
            management_main_table_id, diamond_wire_saw_cut_number_id, diamond_wire_saw_cut_length, diamond_wire_saw_cut_height_width)
                    VALUES ($1, $2, $3, $4);";

                    foreach ($diamond_wire_saw_cuts as $index => $value) {
                        $dwscRes = pg_query_params($dwscQuery, array($rowId, $value->number, $value->length, $value->height));
                        if(!$dwscRes){
                            pg_query("ROLLBACK");
                            echo json_encode(array("error" => "Problem in Inserting Data to DB!"));
                            $this->deleteImage($pic1Paths[0]);
                            $this->deleteImage($pic2Paths[0]);
                            $this->deleteImage($pic3Paths[0]);
                            return;
                        }
                    }
                }

                pg_query("COMMIT");
                echo json_encode(array("server_id" => $rowId));

            }


        } catch (Exception $e) {
            echo json_encode(array("error" => "Unknown Server Error!"));
        }
    }

    function deleteImage($fn)
    {
        try {
            unlink($fn);
        } catch (Exception $e) {
        }
    }

    function getImage($pictureName)
    {
        $paths = array("", "");
        try {
            $ext = ".jpg";
            $onlinePath = "http://localhost:81/noblemms/Android/images/noblemms/";
            $now = DateTime::createFromFormat('U.u', microtime(true));
            $now = $now->format("Y-m-d_H_i_s.u");
            $fileName = $pictureName . "_" . $now . mt_rand() . $ext;
            $target_file = "../images/noblemms/" . $fileName;
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