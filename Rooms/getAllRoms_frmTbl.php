<?php
/**
 * Created by PhpStorm.
 * User: vijay
 * Date: 26-Feb-17
 * Time: 12:10 AM
 */
require_once '../include/Room_functions.php';
require_once '../include/DB_Functions.php';
$db = new DB_Functions();
$rs = new RoomsSearch();

// json response array
$response = array("error" => FALSE);

$roomsAvl = array();

$roomsAvl = $rs->getAllRooms();
print(json_encode($roomsAvl));