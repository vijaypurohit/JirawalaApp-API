<?php
header('Content-Type:Application/json');

require_once '../include/Room_functions.php';
require_once '../include/DB_Functions.php';
$db = new DB_Functions();
$rs = new RoomsSearch();
 
$currentDate = new DateTime();
$cDt = $currentDate->format('Y-m-d'); 

// json response array
$response = array("error" => FALSE);
$roomsAvail = array();

if (isset($_POST['room_type_id']) && isset($_POST['room_type_name']) && $_POST['check_in_date'] && isset($_POST['check_out_date'])) 
{   // receiving the post params
    $roomTypeId = $_POST['room_type_id'];
	$roomTypeName = $_POST['room_type_name'];
    $checkIn = $_POST['check_in_date'];
    $checkOut = $_POST['check_out_date'];

    // get the roomsAvail by check validation of checkIn and checkOut
    if($checkIn != $checkOut && $checkOut > $checkIn  && $checkIn >= $cDt)
    {    $roomsAvail = $rs->getAvailableRooms($roomTypeId, $roomTypeName, $checkIn, $checkOut);

        $dateRange =  $rs->getDateRangeArray($checkIn, $checkOut);

         $room_type_det = $rs->getRoomTypeID($roomTypeId);
            $rt_amt =  $room_type_det["rt_amt"];
            $total_dates = count($dateRange["date"]);
            $totalCost = $rt_amt*$total_dates;

        if ($roomsAvail != false) {
    				// roomsAvail is found
    		$response["error"] = FALSE;
    		$response["total_rooms"] = count($roomsAvail);
            $response["room_tPrc"] = $rt_amt;
            $response["tot_dats"] = $total_dates;
            $response["tot_cst"] = $totalCost;
    		$response["roomsAvail"] = $roomsAvail;
    		echo json_encode($response);
        } else {
            // roomsAvail is not found with the credentials
            $response["error"] = TRUE;
            $response["error_msg"] = "Rooms does not found. Please try again later after some time!";
            echo json_encode($response);
        }
    }
    else{
            $response["error"] = TRUE;
            $response["error_msg"] = "Hey Take care of Dates! check In and check Out date should not be same and checkOut date should be greater";
            echo json_encode($response);
        }

}   
else 
{
    // required post params is missing
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters of rooms id and dates are missing!";
    echo json_encode($response);
}
