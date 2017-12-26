<?php
/**
 * Created by PhpStorm.
 * User: vijay
 * Date: 26-Feb-17
 * Time: 5:02 PM
 */


class OTPMSG
{
    private $conn;
    function __construct() {
//        require_once 'DB_Connect.php';
//        require_once 'Bookings_functions.php';
        require_once '_otp/otpGent.php';
        // connecting to database
//        $db = new Db_Connect();
//        $this->conn = $db->connect();


    }
    function __destruct() {
        // TODO: Implement __destruct() method.
    }

    public function callingOTPFile($msg, $userEmail, $userMobile)
    {
        $_REQUEST["otp_msg"]=$msg;
        $_REQUEST["otp_email"]=$userEmail;
        $_REQUEST["otp_number"]=$userMobile;
        $otpResARet = require_once '_otp/submit-post.php';

        return $otpResARet;
    }

    public function sendBookingConfirmation($bookingAlDetRes) {
        $userEmail      = $bookingAlDetRes["Booking"]["email"] ;
        $userMobile     = $bookingAlDetRes["Booking"]["mobile"] ;
//        $City           = $bookingAlDetRes["Booking"]["city"] ;
//        $checkOut       = $bookingAlDetRes["Booking"]["check_out"] ;
        $userName       = $bookingAlDetRes["Booking"]["name"] ;
        $BookingID      = $bookingAlDetRes["Booking"]["booking_id"] ;
        $BookingTime    = $bookingAlDetRes["Booking"]["booking_time"] ;
        $room_name      = $bookingAlDetRes["Booking"]["room_name"];
        $checkIn        = $bookingAlDetRes["Booking"]["check_in"]  ;
        $no_persons     = $bookingAlDetRes["Booking"]["no_persons"] ;
        $Booking_status = $bookingAlDetRes["Booking"]["booking_status"] ;
        $room_no = $bookingAlDetRes["Booking"]["room_no"] ;

        $bk = new Bookings();
        $status = $bk->sendStatus($Booking_status);

        $msg = "Hey ".$userName.", Thank You for Booking ".$room_name.", room no - ".$room_no." on ".$BookingTime.". Your Booking ID is ".$BookingID.", check in date ".$checkIn." with ".$no_persons." persons. Booking status is ".$status.".";

        $otpResRet = $this->callingOTPFile($msg, $userEmail, $userMobile);
//        echo json_encode($bookingAlDetRes);
        return $otpResRet;
    }

    public function sendBookingCancellation($booking_det)
    {
        $userNameCncl       = $booking_det["name"] ;
        $userEmail      = $booking_det["email"] ;
        $userMobile     = $booking_det["mobile"] ;
//        $City           = $bookingAlDetRes["Booking"]["city"] ;
//        $checkOut       = $bookingAlDetRes["Booking"]["check_out"] ;
        $booking_id_cncl      = $booking_det["booking_id"] ;
        $room_name      = $booking_det["room_name"];
        $updated_at        = $booking_det["updated_at"]  ;
        $statuscncl = $booking_det["status"] ;

        $msgStCncl = "Hey ".$userNameCncl.", Your Booking of ".$room_name." with booking id ".$booking_id_cncl." is changed on ".$updated_at.". Booking status is ".$statuscncl.".";

        $otpResCRet = $this->callingOTPFile($msgStCncl, $userEmail, $userMobile);
//        echo json_encode($bookingAlDetRes);
        return $otpResCRet;
    }// sendBookingCancellation closes

    public function sendOTP($phoneNo, $userName ,$userEmail){
        $otpGent = new otpGent();
        $j=0;
        $newPhoneArr= array();
        $base32check = array('2', '3', '4', '5', '6', '7'); //base32 numbers
        //converting string phone to array then looping individual variables to get only desired numbers
        for ($phoneArr = str_split($phoneNo), $i = count($phoneArr)-1   ; $i >= 0 ; $i-- ) {
            if( in_array($phoneArr[$i], $base32check)) {
                $newPhoneArr[$j] = $phoneArr[$i];
                $j++;
            }
        }
        $newPhoneStr = implode($newPhoneArr);  //converting array to string
        $InitalizationKey = "MSKALGOEZ7VJ4LD".$newPhoneStr;

        $TimeStamp    = otpGent::get_timestamp();
        $secretkey    = $otpGent->base32_decode($InitalizationKey);
        $otp          = otpGent::oath_hotp($secretkey, $TimeStamp);

//        echo "</br>".$otp."</br>";
        $otpMsg = "Hey ".$userName.". Thank You For Registration for Jirawala Jain Tirth. Your OTP is ".$otp.". It is valid for 1 min.";
        $otpResRet = $this->callingOTPFile($otpMsg, $userEmail, $phoneNo);  //uncomment for production mode
//        return $otpResRet;
        return $otp;
    }

    public function sendMail($bookingAlDetRes)
    {
        $bk = new Bookings();
        $userEmail      = $bookingAlDetRes["Booking"]["email"] ;
        $userMobile     = $bookingAlDetRes["Booking"]["mobile"] ;
        $City           = $bookingAlDetRes["Booking"]["city"] ;
        $checkOut       = $bookingAlDetRes["Booking"]["check_out"] ;
        $userName       = $bookingAlDetRes["Booking"]["name"] ;
        $BookingID      = $bookingAlDetRes["Booking"]["booking_id"] ;
        $BookingTime    = $bookingAlDetRes["Booking"]["booking_time"] ;
        $room_name      = $bookingAlDetRes["Booking"]["room_name"];
        $checkIn        = $bookingAlDetRes["Booking"]["check_in"]  ;
        $no_persons     = $bookingAlDetRes["Booking"]["no_persons"] ;
//        $Booking_status = $bk->sendStatus($bookingAlDetRes["Booking"]["booking_status"]);
//        $room_no = $bookingAlDetRes["Booking"]["room_no"] ;


        $to = 'info@jirawalajaintirth.org';
        $subject = 'Jirawala Jain Tirth Room Booking';
        $from = $userEmail ;
//        $to = $user["email"];
////        $to = 'vijay.pu9@gmail.com';
//        $subject = 'CIT Training Letter';
//        $from = 'tpo@citabu.ac.in';
        $CC = 'sjpjpj@gmailcom';
//        $BCc = 'priyanka@citabu.ac.in ';
//        $CC = 'info.ezindagi@gmail.com';

        // To send HTML mail, the Content-type header must be set
        $headers  = 'MIME-Version: 1.0' . "\r\n";
        $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
        // Create email headers
        $headers .= 'From: '.$from."\r\n".
            'Reply-To: '.$from."\r\n" .
            'cc: '.$CC."\r\n" .
//            'Bcc: '.$BCc."\r\n" .
            'X-Mailer: PHP/' . phpversion();

        // Compose a simple HTML email message
        $message = '<html><body>';
        $message .= '<h1 style="color:#e91e63;"> Booking has been confirmed with the following details </h1>';

        $message .= '<p style="color:#097cd4;font-size:18px;">Name - ' .$userName.' </p>';
        $message .= '<p style="color:#097cd4;font-size:18px;">Mobile - ' .$userMobile.' </p>';
        $message .= '<p style="color:#097cd4;font-size:18px;">Email - ' .$userEmail.' </p>';
        $message .= '<p style="color:#097CD4;font-size:18px;">City - '.$City.' </p>';
        $message .= '<p style="color:#097cd4;font-size:18px;">Booking ID - ' .$BookingID.' </p>';
        $message .= '<p style="color:#097cd4;font-size:18px;">Booking Time - ' .$BookingTime.' </p>';
        $message .= '<p style="color:#097CD4;font-size:18px;">Room Name - '.$room_name.' </p>';
        $message .= '<p style="color:#097CD4;font-size:18px;">Check In Date - '.$checkIn.' ';
        $message .= '<p style="color:#097CD4;font-size:18px;">Check Out Date- '.$checkOut.' </p>';
        $message .= '<p style="color:#097CD4;font-size:18px;">No of Persons - '.$no_persons.' </p>';
//        $message .= '<p style="color:#e91e63;font-size:18px;">Booking Status - '.$Booking_status.' </p>';
        $message .= '</body></html>';

            // Sending email
            if(mail($to, $subject, $message, $headers))
            {
                return true;
//                echo 'Your mail has been sent successfully.';
            } else{
                return false;
//                echo 'Unable to send email. Please try again.';
            }
    }

    public function sendOTPconf($phoneNo, $usOTPCode){
        $j=0;
        $newPhoneArr= array();
        $base32check = array('2', '3', '4', '5', '6', '7'); //base32 numbers
        //converting string phone to array then looping individual variables to get only desired numbers
        for ($phoneArr = str_split($phoneNo), $i = count($phoneArr)-1   ; $i >= 0 ; $i-- ) {
            if( in_array($phoneArr[$i], $base32check)) {
                $newPhoneArr[$j] = $phoneArr[$i];
                $j++;
            }
        }
        $newPhoneStr = implode($newPhoneArr);  //converting array to string
        $InitalizationKey = "MSKALGOEZ7VJ4LD".$newPhoneStr;
        $result       = otpGent::verify_key($InitalizationKey, $usOTPCode);
//        echo "</br>".$otp."</br>";
//        echo $result."</br>";
        return $result;
    }

} //class otpmsg closes
