<?php
/**
 * Created by PhpStorm.
 * User: vijay
 * Date: 26-Feb-17
 * Time: 1:53 PM
 */
require_once '../include/Room_functions.php';
require_once '../include/Bookings_functions.php';
require_once '../include/DB_Functions.php';
$db = new DB_Functions();
$rs = new RoomsSearch();
$bk = new Bookings();

$currentDate = new DateTime();
$cDt = $currentDate->format('Y-m-d');
// json response array
$response = array("error" => FALSE);
$UsrBookDet = array();

if ( isset($_POST['booking_id']) )
{   // receiving the post params
        $booking_id = $_POST['booking_id'];
    $UsrBookDet_ID = $bk->getBookingDetails($booking_id);
        $user_id = $UsrBookDet_ID["user_id"];
    $reservationDet  = $bk->getAllReserDetails_byBkID($booking_id);
    $Rooms = $rs->getRoomID($reservationDet["room_id"]);
    $RoomType = $rs->getRoomTypeID($reservationDet["room_type_id"]);
    if ($user_id)
    {
        $getUserDetailsID = $db->getUserDet_byID($user_id);
        $response["error"] = FALSE;
        $response["uid"] = $getUserDetailsID["unique_id"];
        $response["Booking"]["name"]        = $getUserDetailsID["name"];
        $response["Booking"]["email"]       = $getUserDetailsID["email"];
        $response["Booking"]["mobile"]      = $getUserDetailsID["mobile"];
        $response["Booking"]["addr"]        = $getUserDetailsID["addr"];
        $response["Booking"]["city"]        = $getUserDetailsID["city"];
        $response["Booking"]["img_path"]    = $getUserDetailsID["img_path_u"];
        $response["Booking"]["booking_id"]  = $UsrBookDet_ID["booking_id"];
        $response["Booking"]["booking_time"] = $UsrBookDet_ID["booking_time"];
        $response["Booking"]["check_in"]    = $UsrBookDet_ID["check_in"];
        $response["Booking"]["check_out"]    = $UsrBookDet_ID["check_out"];
        $response["Booking"]["RoomNo"]      = $Rooms["room_no"];
        $response["Booking"]["RoomName"]      = $RoomType["name"];
        $response["Booking"]["t_cost"]      = $UsrBookDet_ID["t_cost"];
        $response["Booking"]["no_persons"]  = $UsrBookDet_ID["no_persons"];
        $response["Booking"]["booking_status"] = $bk->sendStatus($UsrBookDet_ID["booking_status"]);
        echo json_encode($response);
    } else {
        $response["error"] = TRUE;
        $response["error_msg"] = "Unknown error occurred in Getting Booking Details !";
        echo json_encode($response);
    }
}
else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters of Booking are missing!";
    echo json_encode($response);
} // isset if else closed
