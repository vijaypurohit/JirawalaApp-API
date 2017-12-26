<?php
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

    // check if user is already existed with the same email
    if ($db->isUserExisted($email))
    {
        $getUserDetails = $db->getUserDet($email);
        $user_id = $getUserDetails["id"];
        // checking total no of rooms available and then fetching unique room id
        $usBokADet = $bk->getAllBookingDetailsOfUser($user_id);
//         print json_encode($usBokADet);
         $no_of_bookings_done = count($usBokADet);
//                $response["booking"]     = $usBokADet;
                $response["result"]     = $usBokADet;
            print json_encode($response);
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
    $response["error_msg"] = "Required parameters of Bookings Details are missing!";
    echo json_encode($response);
} // isset if else closed
