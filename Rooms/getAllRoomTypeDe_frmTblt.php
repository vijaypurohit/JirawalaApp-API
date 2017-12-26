<?php
require_once '../include/Room_functions.php';
require_once '../include/DB_Functions.php';
$db = new DB_Functions();
$rs = new RoomsSearch();

// json response array
$response = array("error" => FALSE);

$romTypeAll = array();

$romTypeAll = $rs->getAllRoomsType();
print(json_encode($romTypeAll));