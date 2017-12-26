<?php
/**
 * Created by PhpStorm.
 * User: vijay
 * Date: 26-Feb-17
 * Time: 1:04 AM
 */

require_once '../include/Bookings_functions.php';
require_once '../include/DB_Functions.php';
$db = new DB_Functions();
$bk = new Bookings();
// json response array
$response = array("error" => FALSE);

$totalBookings = array();
$roomsAvl = $bk->getAllBookingDetails();
print(json_encode($roomsAvl));