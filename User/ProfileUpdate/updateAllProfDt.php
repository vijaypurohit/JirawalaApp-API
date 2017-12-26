<?php
/**
 * Created by PhpStorm.
 * User: Vijay Purohit
 * Date: 2/25/2017
 * Time: 7:45 PM
 */

require_once '../../include/Room_functions.php';
require_once '../../include/DB_Functions.php';
$db = new DB_Functions();
$rs = new RoomsSearch();

// json response array
$response = array("error" => FALSE);

if ( isset($_POST['email']) && isset($_POST['newName']) && isset($_POST['addr']) && isset($_POST['city']) )
{
    $email = $_POST['email'];
    $addr = $_POST['addr'];
    $city = $_POST['city'];
    $newName = $_POST['newName'];

    if ($db->isUserExisted($email)) {
        $user = $db->updateAddrCity($email, $addr, $city);
        $user = $db->updateName($email, $newName);
        if ($user != false) {
            // user is found
            $response["error"] = FALSE;
            $response["result"] = "successful";
            $response["user"]["name"] = $user["name"];
            $response["user"]["email"] = $user["email"];
            $response["user"]["mobile"] = $user["mobile"];
            $response["user"]["city"] = $user["city"];
            $response["user"]["addr"] = $user["addr"];
            $response["user"]["created_at"] = $user["created_at"];
            $response["user"]["updated_at"] = $user["updated_at"];
            echo json_encode($response);
        } else {
            // user is not found with the credentials
            $response["error"] = TRUE;
            $response["error_msg"] = "Error Updating Addr and City!";
            echo json_encode($response);
        }
    } //email checker if
    else
    {
        $response["error"] = TRUE;
        $response["error_msg"] = "Credentials are wrong. Cannot Update Name!";
        echo json_encode($response);
    }//email checker if else
} else {
    // required post params is missing
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters email or NewName is missing!";
    echo json_encode($response);
}// main if closed
