<?php
/**
 * Created by PhpStorm.
 * User: Vijay Purohit
 * Date: 2/25/2017
 * Time: 6:32 PM
 */
class DB_Functions {
    private $conn;

    /**
     * DB_Functions constructor.
     */
    function __construct() {
        require_once 'DB_Connect.php';
        // connecting to database
        $db = new Db_Connect();
        $this->conn = $db->connect();
    }

    /**
     * destructor
     */
    function __destruct() {
//        session_destroy();
    }

    /**
     * Storing new user   -- registration Function
     * returns user details
     * @param $name
     * @param $email
     * @param $mobile
     * @param $city
     * @param $password
     * @return array|bool
     */
    public function storeUser($name, $email, $mobile, $city, $password) {
        $uuid = uniqid('', true);
        $hash = $this->hashSSHA($password);
        $encrypted_password = $hash["encrypted"]; // encrypted password
        $salt = $hash["salt"]; // salt

        $stmt = $this->conn->prepare("INSERT INTO users(unique_id, name, email, mobile, city, encrypted_password, salt, created_at) VALUES(?, ?, ?, ?, ?, ?, ?, NOW())");
        $stmt->bind_param("sssssss", $uuid, $name, $email, $mobile, $city, $encrypted_password, $salt);
        $result = $stmt->execute();
        $stmt->close();

        // check for successful store
        if ($result) {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            return $user;
        } else {
            return false;
        }
    }

    /**
     * Get user by email and password -- Login Function
     * @param $email
     * @param $password
     * @param $token
     * @return array|null
     */
    public function getUserByEmailAndPassword($email, $password, $token) {
        //firebase
         $stmt = $this->conn->prepare("UPDATE users SET token=? WHERE email= ?");
         $stmt->bind_param("ss",$token,$email);
         $stmt->execute();
        //firebase
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            // verifying user password
            $salt = $user['salt'];
            $encrypted_password = $user['encrypted_password'];
            $hash = $this->checkhashSSHA($salt, $password);
            // check for password equality
            if ($encrypted_password == $hash) {
                // user authentication details are correct
                return $user;
            }
        } else {
            return NULL;
        }
        return NULL;
    }

    /**
     * Check user is existed or not
     * @param $email
     * @return bool
     */
    public function isUserExisted($email) {
        $stmt = $this->conn->prepare("SELECT email from users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            // user existed 
            $stmt->close();
            return true;
        } else {
            // user not existed
            $stmt->close();
            return false;
        }
    }

    public function isUserExistedByPhone($phone) {
        $stmt = $this->conn->prepare("SELECT mobile from users WHERE mobile = ?");
        $stmt->bind_param("s", $phone);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            // user existed
            $stmt->close();
            return true;
        } else {
            // user not existed
            $stmt->close();
            return false;
        }
    }

    /**
     * get user details from its email
     * @param $email
     * @return array|null
     */
    public function getUserDet($email) {
         $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
         $stmt->bind_param("s", $email);
            if ($stmt->execute()) {
                $userDet = $stmt->get_result()->fetch_assoc(); ; //->fetch_assoc();
                $stmt -> close();
                return $userDet;
                //  print(json_encode($userDet));
             } else {
                $stmt->close();
                return NULL;
            }
    }

    /**
     * @param $userId
     * @return array|null
     */
    public function getUserDet_byID($userId) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE id = ?");
          $stmt->bind_param("i", $userId);
        if ($stmt->execute()) {
            $userDet_byID = $stmt->get_result()->fetch_assoc(); ; //->fetch_assoc();
            $stmt -> close();
            return $userDet_byID;
            //  print(json_encode($userDet_byID));
        } else {
            $stmt->close();
            return NULL;
        }
    }

    /**
     * Encrypting password
     * @param password
     * returns salt and encrypted password
     * @return array
     */
    public function hashSSHA($password) {

        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }

    /**
     * Decrypting password
     * @param $salt
     * @param $password
     * @return string
     * @internal param $salt , password
     * returns hash string
     */
    public function checkhashSSHA($salt, $password) {
        $hash = base64_encode(sha1($password . $salt, true) . $salt);
        return $hash;
    }


    /** // email and old password and new one
     * @param $email
     * @param $oldPass
     * @param $newPass
     * @return array|bool|null
     */
    public function updatePassword($email, $oldPass, $newPass) {
        // selecting user from his email
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);

        if ( $stmt->execute() )
        {
            $userID = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            $salt = $userID['salt'];
            $encrypted_password = $userID['encrypted_password'];
            $hash = $this->checkhashSSHA($salt, $oldPass);
             // check for old password verifications
            if ($encrypted_password == $hash)
            {
                $hash = $this->hashSSHA($newPass);
                $encrypt_NewPass = $hash["encrypted"]; // encrypted password
                $newSalt = $hash["salt"];               // salt

                $stmt = $this->conn->prepare("UPDATE users SET encrypted_password = ? ,  salt = ?, updated_at = NOW() WHERE email = ?");
                $stmt->bind_param("sss", $encrypt_NewPass, $newSalt, $email);
                $updPassRes = $stmt->execute();
                $stmt->close();

                // check for successful store
                if ($updPassRes) {
                    $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
                    $stmt->bind_param("s", $email);
                    $stmt->execute();
                    $user = $stmt->get_result()->fetch_assoc();
                    $stmt->close();
                    return $user;
                }
            }
            else
                {   //old password does not match up
                    return false;
                }
        }
        else
        {   // query email does not found
            return NULL;
        }
    } // upPassword Closed


    /** Update Name
     * @param $email
     * @param $nwName
     * @return array|bool
     */
    public function updateName($email, $nwName){
            $stmt = $this->conn->prepare("UPDATE users SET name = ? ,  updated_at = NOW() WHERE email = ?");
            $stmt->bind_param("ss", $nwName, $email);
            $updNewNmRes = $stmt->execute();
            $stmt->close();
            // check for successful store
            if ($updNewNmRes) {
                $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
                $stmt->bind_param("s", $email);
                $stmt->execute();
                $user = $stmt->get_result()->fetch_assoc();
                $stmt->close();
                return $user;
            }
             else {   //email does not match up
                return false;
            }
    } // updateName Closed

    /**
     * @param $email
     * @param $nwAddr
     * @param $nwCity
     * @return array|bool
     */
    public function updateAddrCity($email, $nwAddr, $nwCity){

        $stmt = $this->conn->prepare("UPDATE users SET addr = ? , city = ?,  updated_at = NOW() WHERE email = ?");
        $stmt->bind_param("sss", $nwAddr, $nwCity, $email);
        $updNwAcRes = $stmt->execute();
        $stmt->close();
        // check for successful store
        if ($updNwAcRes) {
            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            return $user;
        }
        else {   //email does not match up
            return false;
        }
    }// updateAddrCity Closed

//    public function OTP_verification($email, $otpReceived) {
//        $stmt = $this->conn->prepare("SELECT m_otp from users WHERE email = ?");
//        $stmt->bind_param("s", $email);
//        $dbOTP = $stmt->execute();
//        if ($dbOTP == $otpReceived) {
//            $stmt->close();
//            return true;
//        } else {
//            $stmt->close();
//            return false;
//        }
//    }
//
//    public function updateOTP($email, $sendOTP){
//        $stmt = $this->conn->prepare("UPDATE users SET m_otp = ? ,  updated_at = NOW() WHERE email = ?");
//        $stmt->bind_param("ss", $sendOTP, $email);
//        $updateOTP = $stmt->execute();
//        $stmt->close();
//        // check for successful store
//        if ($updateOTP) {
//            $stmt = $this->conn->prepare("SELECT * FROM users WHERE email = ?");
//            $stmt->bind_param("s", $email);
//            $stmt->execute();
//            $user = $stmt->get_result()->fetch_assoc();
//            $stmt->close();
//            return $user;
//        }
//        else {   //email does not match up
//            return false;
//        }
//    } // updateName Closed


}//Class DB_Functions Closed

 
 //  $rs = new DB_Functions();
 // $rs->getUserDet("vijay.pu9@gmail.com");
