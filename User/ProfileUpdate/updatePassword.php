<?php
/**
 * Created by PhpStorm.
 * User: Vijay Purohit
 * Date: 2/24/2017
 * Time: 5:31 PM
 */
require_once '../../include/Room_functions.php';
require_once '../../include/DB_Functions.php';
$db = new DB_Functions();
$rs = new RoomsSearch();

// json response array 
$response = array("error" => FALSE);
 
if ( isset($_POST['email']) && isset($_POST['oldPass']) && isset($_POST['newPass']) )
{
	$email = $_POST['email'];
    $oldPass = $_POST['oldPass'];
    $newPass = $_POST['newPass'];

    $user = $db->updatePassword($email, $oldPass, $newPass);
    //NUll --> doesn't found
    // false --> old password wrong
	    if ($user != false) {
	        // user is found
	        $response["error"] = FALSE;
	        $response["result"] 			= "successful";
	        $response["user"]["name"] 		= $user["name"];
	        $response["user"]["email"] 		= $user["email"];
			$response["user"]["mobile"] 	= $user["mobile"];
			$response["user"]["city"] 		= $user["city"];
            $response["user"]["addr"]       = $user["addr"];
	        $response["user"]["created_at"] = $user["created_at"];
	        $response["user"]["updated_at"] = $user["updated_at"];
	        echo json_encode($response);
	    } else {
	        // user is not found with the credentials
	        $response["error"] = TRUE;
	        $response["error_msg"] = "Credentials are wrong. Cannot Update Password!";
	        echo json_encode($response);
	    }
} else {
    // required post params is missing
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters email or  password is missing!";
    echo json_encode($response);
}// main if closed
?>