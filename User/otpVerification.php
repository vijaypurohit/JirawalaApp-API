<?php
/**
 * Created by PhpStorm.
 * User: IT Infra
 * Date: 09-Mar-17
 * Time: 6:10 PM
 */
require_once '../include/DB_Functions.php';
require_once '../include/otpmsg.php';

$db = new DB_Functions();
$op = new OTPMSG();

$user = array();

if ( isset($_POST['otp_received']) && isset($_POST['mobile']) ) {

    $otp_received = $_POST['otp_received'];
    $mob = $_POST['mobile'];

    $otpResult = $op->sendOTPconf($mob, $otp_received);
    if($otpResult){
        $response["error"] = false;
        $response["otp_verified"] = $otpResult;
        echo json_encode($response);
    }else{
        $response["error"] = TRUE;
        $response["error_msg"] = "Verification Failed";
        echo json_encode($response);
    }

} else {
    // required post params is missing
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters are missing!";
    echo json_encode($response);
}
