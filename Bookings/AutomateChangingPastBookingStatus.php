<?php
/**
 * Created by PhpStorm.
 * User: vijay
 * Date: 26-Feb-17
 * Time: 3:08 PM
 */
require_once '../include/Room_functions.php';
require_once '../include/Bookings_functions.php';
require_once '../include/DB_Functions.php';
$db = new DB_Functions();
$rs = new RoomsSearch();
$bk = new Bookings();

$currentDate = new DateTime();
$cDt = $currentDate->format('Y-m-d');

if ( isset($_POST['email']) )
{
    // receiving the post params
    $email = $_POST['email'];

    // declaration
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

    // check if user is already existed with the same email
    if ($db->isUserExisted($email))
    {
        $getUserDetails = $db->getUserDet($email);
            $user_id = $getUserDetails["id"];
        // checking total no of rooms available and then fetching unique room id
        $usBokADet = $bk->getAllBookingDetailsOfUser($user_id);
//         print json_encode($usBokADet);
            $no_of_bookings_done = count($usBokADet);
        for ( $loopVar = $no_of_bookings_done, $i=0 ; $loopVar > 0 ; $i++, $loopVar-- )
        {
            $rowValBookingId        =   $usBokADet[$i]["booking_id"];
            $rowValBookingTime      =   $usBokADet[$i]["booking_time"];
            $rowValCheckIn          =   $usBokADet[$i]["check_in"];
            $rowValCheckOut         =   $usBokADet[$i]["check_out"];
            $rowValBookingStatus    =   $usBokADet[$i]["booking_status"];
            $rowValBookingPersons   =   $usBokADet[$i]["no_persons"];

            if( $rowValCheckIn <= $cDt && $cDt <= $rowValCheckOut)
            {
//                $rowValCurrentBookingId[] = $rowValBookingId;
//                $rowValCurrentBookingTime[] = $rowValBookingTime;
//                $rowValCurrentBookingCheckIn[] = $rowValCheckIn;
//                $rowValCurrentBookingCheckOut[] = $rowValCheckOut;
//                $rowValCurrentBookingStatus[] = $rowValBookingStatus;
//                $rowValCurrentsBookingPersons[] = $rowValBookingPersons;
                $no_of_bookings_current++ ;
            } // current details if closed
            if( $rowValCheckOut < $cDt )
            {
//                $rowValPreviousBookingId[] = $rowValBookingId;
//                $rowValPreviousBookingTime[] = $rowValBookingTime;
//                $rowValPreviousBookingCheckIn[] = $rowValCheckIn;
//                $rowValPreviousBookingCheckOut[] = $rowValCheckOut;
//                $rowValPreviousBookingStatus[] = $rowValBookingStatus;
//                $rowValPreviousBookingPersons[] = $rowValBookingPersons;
                // changing all past bookings to 2
                $NewRowValBookingId =  $bk->changeBokDet($rowValBookingId, 2);
//                $rowValBookingId = $NewRowValBookingId;
                $no_of_bookings_previous++ ;
            } // past details if closed
            if(  $cDt <  $rowValCheckIn)
            {
//                $rowValFutureBookingId[] = $rowValBookingId;
//                $rowValFutureBookingTime[] = $rowValBookingTime;
//                $rowValFutureBookingCheckIn[] = $rowValCheckIn;
//                $rowValFutureBookingCheckOut[] = $rowValCheckOut;
//                $rowValFutureBookingStatus[] = $rowValBookingStatus;
//                $rowValFutureBookingPersons[] = $rowValBookingPersons;
                $no_of_bookings_future++ ;
            } // future details if closed
        } //for loop closes
//        $response["total_bookings_done"] = $no_of_bookings_done;
//
//        $response["no_of_bookings_current"]     = $no_of_bookings_current;
//        $response["no_of_bookings_previous"]    = $no_of_bookings_previous;
//        $response["no_of_bookings_future"]      = $no_of_bookings_future;
//
//        $response["bookings"]      = $usBokADet;
        //current
//            $response["current"]["bookingId"]       = $rowValCurrentBookingId;
//            $response["current"]["bookingTime"]     = $rowValCurrentBookingTime;
//            $response["current"]["bookingCheckIn"]  = $rowValCurrentBookingCheckIn;
//            $response["current"]["bookingCheckOut"] = $rowValCurrentBookingCheckOut;
//            $response["current"]["bookingStatus"]   = $rowValCurrentBookingStatus;
//            $response["current"]["bookingPersons"]  = $rowValCurrentsBookingPersons;
        //past
//            $response["past"]["bookingId"]       = $rowValPreviousBookingId;
//            $response["past"]["bookingTime"]     = $rowValPreviousBookingTime;
//            $response["past"]["bookingCheckIn"]  = $rowValPreviousBookingCheckIn;
//            $response["past"]["bookingCheckOut"] = $rowValPreviousBookingCheckOut;
//            $response["past"]["bookingStatus"]   = $rowValPreviousBookingStatus;
//            $response["past"]["bookingPersons"]  = $rowValPreviousBookingPersons;
        //future
//            $response["future"]["bookingId"]       = $rowValFutureBookingId;
//            $response["future"]["bookingTime"]     = $rowValFutureBookingTime;
//            $response["future"]["bookingCheckIn"]  = $rowValFutureBookingCheckIn;
//            $response["future"]["bookingCheckOut"] = $rowValFutureBookingCheckOut;
//            $response["future"]["bookingStatus"]   = $rowValFutureBookingStatus;
//            $response["future"]["bookingPersons"]  = $rowValFutureBookingPersons;
//        print json_encode($response);
    }
    else
    {
        $response["error"] = TRUE;
        $response["error_msg"] = "Email is incorrect !";
//        echo json_encode($response);
    }   //email checking if else closed
}
else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters of Bookings Details are missing!";
//    echo json_encode($response);
} // isset if else closed
