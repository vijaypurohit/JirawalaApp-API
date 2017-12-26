<?php
require_once '../include/DB_Functions.php';
require_once '../include/Room_functions.php';
require_once '../include/Bookings_functions.php';
require_once '../include/otpmsg.php';
$db = new DB_Functions();
$rs = new RoomsSearch();
$bk = new Bookings();
$otp = new OTPMSG();

$currentDate = new DateTime();
$cDt = $currentDate->format('Y-m-d'); 

// json response array 
$response = array("error" => FALSE);
 
if ( isset($_POST['email']) && isset($_POST['no_persons']) && $_POST['check_in'] && isset($_POST['check_out']) && isset($_POST['room_type_id']))
{
    // receiving the post params
    $email              = $_POST['email'];
    $no_persons         = $_POST['no_persons'];
    $check_in_date      = $_POST['check_in'];
    $check_out_date     = $_POST['check_out'];
    $room_type_id       = $_POST['room_type_id'];

    $booking_id = null;
    $userReservDetails = false  ;
    $room_avail = array();
    $user_id = '';
    $room_id = 0;
    // check if user is already existed with the same email
    if ($db->isUserExisted($email)) 
    {
          $getUserDetails = $db->getUserDet($email);
            $user_id = $getUserDetails["id"];
          // checking total no of rooms available and then fetching unique room id
          $room_avail = $rs->getAvailableRooms( $room_type_id,"", $check_in_date, $check_out_date );
                $no_of_room_avail = count($room_avail);

          $room_type_det = $rs->getRoomTypeID($room_type_id);
             $bed_reserved =  $room_type_det["capacity"];
             $rt_amt =  $room_type_det["rt_amt"];

          $dateRange =  $rs->getDateRangeArray($check_in_date, $check_out_date);
            $total_dates = count($dateRange["date"]);
            $totalCost = $rt_amt*$total_dates;

        if($no_of_room_avail > 0)
        {
            $room_id = $room_avail[0]["room_id"];
            // checking dates conditions
            if($check_in_date != $check_out_date && $check_out_date > $check_in_date  && $check_in_date >= $cDt)
            {
                if( $no_persons <= $bed_reserved) {         //Storing Booking Details and Storing it to Reservations
                    $userBookingDetails = $bk->storeBookingsDetails($user_id, $check_in_date, $check_out_date, $totalCost,  $no_persons);
                        $booking_id = $userBookingDetails["booking_id"];
//                    print(json_encode($userBookingDetails));
                    $userReservDetails = $bk->strReserDet($booking_id , $room_id, $room_type_id, $bed_reserved);

                    $rooms = $rs->getRoomID($room_id);

                    if ($userBookingDetails && $userReservDetails) {
                        // user stored successfully
                        $response["error"] = FALSE;
                        $response["result"] = "Booking Active";
                        $response["uid"] = $getUserDetails["unique_id"];
                        $response["Booking"]["name"]    = $getUserDetails["name"];
                        $response["Booking"]["email"]   = $getUserDetails["email"];
                        $response["Booking"]["mobile"]  = $getUserDetails["mobile"];
                        $response["Booking"]["city"]    = $getUserDetails["city"];
                        $response["Booking"]["booking_id"]   = $userBookingDetails["booking_id"];
                        $response["Booking"]["booking_time"] = $userBookingDetails["booking_time"];
                        $response["Booking"]["check_in"]    = $userBookingDetails["check_in"];
                        $response["Booking"]["check_out"]    = $userBookingDetails["check_out"];
                        $response["Booking"]["cost"]    = $userBookingDetails["t_cost"];
                        $response["Booking"]["room_no"]   = $rooms["room_no"];
                        $response["Booking"]["room_name"]   = $room_type_det["name"];
                        $response["Booking"]["no_persons"]  = $userBookingDetails["no_persons"];
                        $response["Booking"]["booking_status"] = $userBookingDetails["booking_status"];
                        // Sending Booking Confirmation
//                        $otpResponse = $otp->sendBookingConfirmation($response);
//                        $mailResponse = $otp->sendMail($response);
//                        $response["otp"] = $otpResponse;
//                        $response["mail_send"] = $mailResponse;
                        echo json_encode($response);
                    } else {
                        // user booking failed to store
                        $bk->delBokngsDet($booking_id);
                        $response["error"] = TRUE;
                        $response["error_msg"] = "Unknown error occurred in Booking!";
                        echo json_encode($response);
                    }
                }
                 else{
                         $response["error"] = TRUE;
                        $response["error_msg"] = "Hey number of Persons are greater than capacity!";
                        echo json_encode($response);
                 }
            }else{

                    $response["error"] = TRUE;    
                    $response["error_msg"] = "checkIn and checkOut date should not be same and take care of dates";
                    echo json_encode($response);
                 }
        }else {
                     $response["error"] = TRUE;
                     $response["error_msg"] = "Rooms are Not Available !";
                     echo json_encode($response);
                }    // no of rooms available checking if else closed
    }
    else
        {
            $response["error"] = TRUE;
            $response["error_msg"] = "Email is incorrect !";
            echo json_encode($response);
        }   //email checking if else closed
} 
else {
        $response["error"] = TRUE;
        $response["error_msg"] = "Required parameters of Bookings are missing!";
        echo json_encode($response);
     } // isset if else closed
