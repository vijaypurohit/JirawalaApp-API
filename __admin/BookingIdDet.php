<?php
/**
 * Created by PhpStorm.
 * User: vijay
 * Date: 23-Mar-17
 * Time: 9:26 PM
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

//echo $_GET["booking_id"];

if ( isset($_GET["booking_id"]) )
{   // receiving the post params
    $booking_id = $_GET["booking_id"];
    $UsrBookDet_ID = $bk->getBookingDetails($booking_id);
    $user_id = $UsrBookDet_ID["user_id"];
    $reservationDet  = $bk->getAllReserDetails_byBkID($booking_id);
    $Rooms = $rs->getRoomID($reservationDet["room_id"]);
    $RoomType = $rs->getRoomTypeID($reservationDet["room_type_id"]);
    if ($user_id)
    {
        header('Content-Type:text/html; charset=UTF-8');
        $getUserDetailsID = $db->getUserDet_byID($user_id);
        echo "<table>";
        echo '<tr>  <th> User Name </th> <th> Email </th> <th> Mobile </th> <th> City </th> <th> Room Name </th> </tr>';
            echo '<tr>';
            echo '<td>'. $getUserDetailsID["name"] .'</td>';
            echo '<td>'.  $getUserDetailsID["email"] .'</td>';
            echo '<td>'.  $getUserDetailsID["mobile"]  .'</a></td>';
            echo '<td>'.  $getUserDetailsID["city"]  .'</td>';
            echo '<td>'. $RoomType["name"]  .'</td>';
            echo '</tr>';
        echo "</table>";

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