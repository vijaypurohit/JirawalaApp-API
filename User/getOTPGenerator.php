<?php
/**
 * Created by PhpStorm.
 * User: IT Infra
 * Date: 09-Mar-17
 * Time: 5:01 PM
 */

require_once '../include/DB_Functions.php';
require_once '../include/otpmsg.php';

$db = new DB_Functions();
$op = new OTPMSG();

$user = array();

if ( isset($_POST['mobile']) ) {
    // receiving the post params
    $mob = $_POST['mobile'];
    if($db->isUserExistedByPhone($mob)){
        $response["error"] = TRUE;
        $response["error_msg"] = "User already existed with " . $mob;
        echo json_encode($response);
    }else {
        // get the user by email and password
        $otpResult = $op->sendOTP($mob, "User", "");
        $response["error"] = false;
        $response["otp_code"] = $otpResult;
        echo json_encode($response);
    }
} else {
    // required post params is missing
    $response["error"] = TRUE;
    $response["error_msg"] = "Required mobile no is missing!!";
    echo json_encode($response);
}
