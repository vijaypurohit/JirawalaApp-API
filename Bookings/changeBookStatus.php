<?php

header('Content-Type:Application/json');

require_once '../include/Bookings_functions.php';
require_once '../include/Room_functions.php'    ;
require_once '../include/DB_Functions.php'      ;
require_once '../include/otpmsg.php'            ;

$db = new DB_Functions();
$bk = new Bookings()    ;
$rs = new RoomsSearch() ;
$otp = new OTPMSG()     ;
 
// json response array
$response = array("error" => FALSE);
$romTypeAvl = array();

		// $status = 2 Booking Completed and Done Outdated
		// $status = 1 Active 
        // $status = 0 Pending
        // $status = -1 canceled by User
        // $status = -2 canceled by Admin

if ( isset($_POST['booking_id']) && isset($_POST['status']) )
{
	$booking_id = $_POST['booking_id'];
	$status = $_POST['status'];
	$chngBkSt = $bk->changeBokDet($booking_id, $status);

    if ($chngBkSt != false) {
        $statusBookingDet   =   $bk->getBookingDetails($booking_id);
        $statusUserDet      =   $db->getUserDet_byID($statusBookingDet["user_id"]);
        $statusReservDet    =   $bk->getAllReserDetails_byBkID($booking_id);
        $statusRoomDet      =   $rs->getRoomTypeID($statusReservDet["room_type_id"]);
        $status             =   $bk->sendStatus($status);
        // naming the status
        // chngBkSt is found
        $response["error"] = FALSE;
        $response["status"] = $status;
        $response["BkChngeRslt"] = $chngBkSt;
        $response["name"] = $statusUserDet["name"];
        $response["mobile"] = $statusUserDet["mobile"];
        $response["email"] = $statusUserDet["email"];
        $response["booking_id"] = $booking_id;
        $response["booking_time"] = $statusBookingDet["booking_time"];
        $response["room_name"] = $statusRoomDet["name"];
        $response["updated_at"] = $statusBookingDet["updated_at"];

//        $otpResAnc = $otp->sendBookingCancellation($response);
//            $response["otp"] = $otpResAnc;
    		echo json_encode($response);
        } else {
            // chngBkSt is not found with the credentials
            $response["error"] = TRUE;
            $response["error_msg"] = "Error Changing Booking Status !";
            echo json_encode($response);
        }
}