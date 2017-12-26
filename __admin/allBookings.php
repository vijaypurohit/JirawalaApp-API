<?php
/**
 * Created by PhpStorm.
 * User: vijay
 * Date: 23-Mar-17
 * Time: 8:36 PM
 */
require_once '../include/Bookings_functions.php';
require_once '../include/DB_Functions.php';
$db = new DB_Functions();
$bk = new Bookings();
$totalBookings = array();
$roomsAvl = $bk->getAllBookingDetails();
//print(json_encode($roomsAvl));
//print_r($roomsAvl);

header('Content-Type:text/html; charset=UTF-8');
echo "<table>";
echo '<tr><th> S.NO </th> <th> User ID </th> <th> Booking ID </th> <th> booking time </th> <th> check_in </th> <th> check_out </th>  <th> t cost </th> <th> no persons </th>  <th> booking status </th> <th>updated at</th> </tr>';
foreach ($roomsAvl as $item) {
    echo '<tr>';
    echo '<td>'.  $item["id"]  .'</td>';
    echo '<td>'.  $item["user_id"]  .'</td>';
    echo '<td><a href="BookingIdDet.php?booking_id='.$item["booking_id"].'">'.  $item["booking_id"]  .'</a></td>';
    echo '<td>'.  $item["booking_time"]  .'</td>';
    echo '<td>'.  $item["check_in"]  .'</td>';
    echo '<td>'.  $item["check_out"]   .'</td>';
    echo '<td>'.  $item["t_cost"]   .'</td>';
    echo '<td>'.  $item["no_persons"]  .'</td>';
    echo '<td>'.  $bk->sendStatus($item["booking_status"])  .'</td>';
    echo '<td>'.  $item["updated_at"]  .'</td>';
    echo '</tr>';
}
echo "</table>";


