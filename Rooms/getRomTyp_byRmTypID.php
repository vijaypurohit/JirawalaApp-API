<?php
/**
 * Created by PhpStorm.
 * User: vijay
 * Date: 26-Feb-17
 * Time: 12:35 AM
 */
// get all the details of any room type by its room_type_id
require_once '../include/Room_functions.php';
require_once '../include/DB_Functions.php';
$db = new DB_Functions();
$rs = new RoomsSearch();

// json response array
$response = array("error" => FALSE);
$romTypeID = array();

if (isset($_POST['room_type_id']))
{   // receiving the post params
    $roomTypeId = $_POST['room_type_id'];
    $romTypeID = $rs->getRoomTypeID($roomTypeId);

        if ($romTypeID != false) {
            // roomsAvail is found
            $response["error"]          = FALSE;
            $response["room_type_id"]   = $romTypeID["room_type_id"];
            $response["name"]           = $romTypeID["name"];
            $response["amt"]            = $romTypeID["rt_amt"];
            $response["description"]    = $romTypeID["description"];
            $response["img_path"]       = $romTypeID["img_path"];
            $response["capacity"]       = $romTypeID["capacity"];
            echo json_encode($response);
        } else {
            // roomsAvail is not found with the credentials
            $response["error"] = TRUE;
            $response["error_msg"] = "Rooms does not found. Please try again later after some time!";
            echo json_encode($response);
        }
}
else
{
    // required post params is missing
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters of rooms id is missing!";
    echo json_encode($response);
}