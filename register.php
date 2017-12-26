
<?php
 //register.php
require_once 'include/DB_Functions.php';
require_once 'include/otpmsg.php';
require_once 'include/DB_Connect.php';
$db = new DB_Functions();
$op = new OTPMSG();
$ct = new DB_Connect();
// json response array 
$response = array("error" => FALSE);
 
if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['mobile']) && isset($_POST['city']) && isset($_POST['password'])) {
 
    // receiving the post params
    $name = stripslashes($_POST['name']);
    $email = stripslashes($_POST['email']);
	$mobile = stripslashes($_POST['mobile']);
	$city = stripslashes($_POST['city']);
    $password = stripslashes($_POST['password']);

    $name = mysqli_real_escape_string($ct->connect(), $name);
    $email = mysqli_real_escape_string($ct->connect(), $email);
    $mobile = mysqli_real_escape_string($ct->connect(), $mobile);
    $city = mysqli_real_escape_string($ct->connect(), $city);
    $password = mysqli_real_escape_string($ct->connect(), $password);

    if($db->isUserExistedByPhone($mobile)){
        $response["error"] = TRUE;
        $response["error_msg"] = "User already existed with " . $mobile;
        echo json_encode($response);
    }else{
        // check if user is already existed with the same email
        if ($db->isUserExisted($email)) {
            // user already existed
            $response["error"] = TRUE;
            $response["error_msg"] = "User already existed with " . $email;
            echo json_encode($response);
        } else {
            $user = $db->storeUser($name, $email, $mobile, $city,  $password);
            // create a new user
            if ($user) {
                // user stored successfully
                $response["error"] = FALSE;
                $response["uid"] = $user["unique_id"];
                $response["user"]["name"] = $user["name"];
                $response["user"]["email"] = $user["email"];
                $response["user"]["mobile"] = $user["mobile"];
                $response["user"]["city"] = $user["city"];
                $response["user"]["created_at"] = $user["created_at"];
                $response["user"]["updated_at"] = $user["updated_at"];
                echo json_encode($response);
            } else {
                // user failed to store
                $response["error"] = TRUE;
                $response["error_msg"] = "Unknown error occurred in registration!";
                echo json_encode($response);
            }
        }
    }

} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters (name, email, mobile, city or password) is missing!";
    echo json_encode($response);
}
