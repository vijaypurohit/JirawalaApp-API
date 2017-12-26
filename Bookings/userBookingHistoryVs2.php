<?php
/**
 * Created by PhpStorm.
 * User: vijay
 * Date: 05-Mar-17
 * Time: 11:08 AM
 */

require_once 'AutomateChangingPastBookingStatus.php';
require_once '../include/Room_functions.php';
require_once '../include/Bookings_functions.php';
require_once '../include/DB_Functions.php';
$db = new DB_Functions();
$rs = new RoomsSearch();
$bk = new Bookings();

$currentDate = new DateTime();
$cDt = $currentDate->format('Y-m-d');
// json response array
$response = array();

if ( isset($_POST['email']) )
{   // receiving the post params
    $email = $_POST['email'];

    $usBokADet = array();
    $rowValBookingPersons = array();
    $no_of_bookings_current = 0;
    $no_of_bookings_previous = 0;
    $no_of_bookings_future = 0;

//Current
    $rowValCurrentBookingId         = array();
    $rowValCurrentBookingTime       = array();
    $rowValCurrentBookingCheckIn    = array();
    $rowValCurrentBookingCheckOut   = array();
    $rowValCurrentBookingStatus     = array();
    $rowValCurrentsBookingPersons   = array();
//Previous
    $rowValPreviousBookingId        = array();
    $rowValPreviousBookingTime      = array();
    $rowValPreviousBookingCheckIn   = array();
    $rowValPreviousBookingCheckOut  = array();
    $rowValPreviousBookingStatus    = array();
    $rowValPreviousBookingPersons   = array();
//Future
    $rowValFutureBookingId        = array();
    $rowValFutureBookingTime      = array();
    $rowValFutureBookingCheckIn   = array();
    $rowValFutureBookingCheckOut  = array();
    $rowValFutureBookingStatus    = array();
    $rowValFutureBookingPersons   = array();

    $rowValBookings        = array();

    // check if user is already existed with the same email
    if ($db->isUserExisted($email))
    {
        $getUserDetails = $db->getUserDet($email);
        $user_id = $getUserDetails["id"];
        // checking total no of rooms available and then fetching unique room id
        $usBokADet = $bk->getAllBookingDetailsOfUser($user_id);
//         print json_encode($usBokADet);
        $no_of_bookings_done = count($usBokADet);
        for ( $loopVar = $no_of_bookings_done, $i=0 ; $loopVar > 0 ; $i++, $loopVar-- ) {

            $rowValBookingId            = $usBokADet[$i]["booking_id"];
            $rowValBookingTime          = $usBokADet[$i]["booking_time"];
            $rowValCheckIn              = $usBokADet[$i]["check_in"];
            $rowValCheckOut             = $usBokADet[$i]["check_out"];
            $rowValTcost             = $usBokADet[$i]["t_cost"];
            $rowValBookingPersons       = $usBokADet[$i]["no_persons"];
            $rowValBookingStatus        = $bk->sendStatus($usBokADet[$i]["booking_status"]);



            $rowValBookings[$i] = array(
                "booking_id"=> $rowValBookingId,
                "booking_time"=> $rowValBookingTime,
                "check_in"=> $rowValCheckIn,
                "check_out"=> $rowValCheckOut,
                "t_cost"=> $rowValTcost,
                "no_persons"=> $rowValBookingPersons,
                "booking_status"=> $rowValBookingStatus
            );

        }
//                $response["booking"]     = $usBokADet;
        $response["result"]     = $rowValBookings;
        print json_encode($response);

    }
    else
    {
        $response["error"] = TRUE;
        $response["error_msg"] = "Email is incorrect !";
        echo json_encode($response);
    }   //email checking if else closed
}//isset param email checking if
else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters of Bookings Details are missing!";
    echo json_encode($response);
} // isset if else closed
