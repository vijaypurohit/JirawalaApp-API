<?php

/**
 * Created by PhpStorm.
 * User: vijay
 * Date: 26-Feb-17
 * Time: 1:06 AM
 */
class Bookings
{
    private $conn;

    function __construct() {
        require_once 'DB_Connect.php';
        // connecting to database
        $db = new Db_Connect();
        $this->conn = $db->connect();
        //echo "database conntected";
    }
    /**
     *
     */
    function __destruct() {

    }

    /**
     * @return null
     */
    public function getAllBookingDetails() {
        $stmt = $this->conn->prepare("SELECT * FROM bookings ORDER BY booking_time DESC LIMIT 100");
        if ($stmt->execute()) {
            $userBookDet = $stmt->get_result() ; //->fetch_assoc();
            $userBookDetOutput = array();
            while($row = $userBookDet -> fetch_assoc()) {
                $userBookDetOutput[]= $row;
            }
            $stmt -> close();
//            print(json_encode($userBookDetOutput));
            return $userBookDetOutput;
        } else {
            $stmt->close();
            return NULL;
        }
    } //getAllBookingDetails() function closed

    /**
     * @param $booking_id
     * @return array|null
     */
    public function getBookingDetails($booking_id) {
        $stmt = $this->conn->prepare("SELECT * FROM bookings WHERE booking_id = ?");
        $stmt->bind_param("i", $booking_id);
        if ($stmt->execute()) {
            $roomsTypeA = $stmt->get_result()->fetch_assoc();
            // only one booking detail to be fetch, therefore no need of while.
            $stmt -> close();
            return $roomsTypeA;
        }
        else {
            $stmt->close();
            return NULL;
        }
    } //getBookingDetails() function closed

    //get Bookings
    /**
     * @param $user_id
     * @return array|bool
     */
    public function getAllBookingDetailsOfUser($user_id) {
        $stmt = $this->conn->prepare("SELECT * FROM bookings WHERE user_id = ? ORDER BY booking_time DESC ");
        $stmt->bind_param("i", $user_id);

        if ($stmt->execute()) {
            $userBookDetAll = $stmt->get_result() ; //->fetch_assoc();
            $usrBokDetOpAl = array() ;
            while($row = $userBookDetAll -> fetch_assoc()) {
                $usrBokDetOpAl[]= $row;
            }
            $stmt -> close();
            return $usrBokDetOpAl;
            // print(json_encode($usrBokDetOpAl));
        } else {
            $stmt->close();
            return false;
        }
    }


    /**  // function to store bookings details
     * @param $user_id
     * @param $check_in
     * @param $check_out
     * @param $t_cost
     * @param $no_persons
     * @return array|bool|null
     */
    public function storeBookingsDetails($user_id, $check_in, $check_out, $t_cost,  $no_persons) {

        $num_str = sprintf("%02d", mt_rand(1, 99));
        $booking_id= $user_id.date("ymdh").$num_str;
        $booking_status = 1;

        $stmt = $this->conn->prepare("INSERT INTO bookings( booking_id, user_id, booking_time, check_in, check_out, t_cost, no_persons, booking_status) VALUES(?, ?, NOW(), ?, ?, ?, ?, ? )");
        $stmt->bind_param("iissdii", $booking_id, $user_id, $check_in, $check_out, $t_cost, $no_persons, $booking_status);
        $BookingResult = $stmt->execute();
        $stmt->close();
        //check for successful store
        if ($BookingResult) {
            $bookingStoreAftResult = $this->getBookingDetails($booking_id);
            return $bookingStoreAftResult;
        } else {
            return false;
        }
    } //storeBookingsDetails function closed
    /**
     * @return null
     */

    /**
     * @param $booking_id
     * @param $status
     * @return bool
     */
    public function changeBokDet($booking_id, $status) {
        // $status = 1 Active
        // $status = 0 Pending
        // $status = -1 canceled by User
        // $status = -2 canceled by Admin
        $stmt = $this->conn->prepare("UPDATE bookings SET booking_status = ?, updated_at = NOW() WHERE booking_id = ?");
        $stmt->bind_param("ii", $status, $booking_id);
        //check for successful store
        if ($stmt->execute() ) {
            $stmt->close();
            return $cnclBokDetRslt;
        } else {
            return false;
        }
    } //changeBokDet function closed

    public function sendStatus($Bkstatus) {
        // $status = 1 Active
        // $status = 0 Pending
        // $status = -1 canceled by User
        // $status = -2 canceled by Admin
        switch ($Bkstatus) {
            case -2:   $status = "Canceled by Admin";           break;
            case -1:   $status = "Canceled by You";             break;
            case  0:   $status = "Pending";                     break;
            case  1:   $status = "Active";                      break;
            case  2:   $status = "Completed";                   break;
            default:    $status = "Unknown";
        }
        return $status;
    } //changeBokDet function closed

    public function delBokngsDet($booking_id) {
        // first deleting reservation one so that avoid foreign key constraint error
        $this->delResvsDet($booking_id);
        $stmt = $this->conn->prepare("DELETE FROM bookings WHERE booking_id=?");
        $stmt->bind_param("i", $booking_id);
        $delBoResult = $stmt->execute();
        $stmt->close();
        //check for successful store
        if ($delBoResult) {
            // print(json_encode($delBoResult));
            return $delBoResult;
        }
        else {
            return false;
        }
    } //delBokngsDet function closed

    /**  //getting bookings details with booking id
     * @param $booking_id
     * @param $room_id
     * @param $room_type_id
     * @param $bed_reserved
     * @return bool|string
     */
    public function strReserDet($booking_id, $room_id, $room_type_id, $bed_reserved) {
        $stmt = $this->conn->prepare("INSERT INTO reservation(booking_id, room_id, room_type_id, bed_reserved) VALUES(?, ?, ?, ? )");
        $stmt->bind_param("iiii", $booking_id, $room_id, $room_type_id, $bed_reserved);
        $ReservResult = $stmt->execute();
        $stmt->close();
        //check for successful store
        if ($ReservResult) {
//            print(json_encode($ReservResult));
            // return $ReservResult;
            return ($ReservResult);
        } else {
            return false;
        }
    }//strReserDet function closed

    /**
     * @return null
     */
    public function getAllReserDetails() {
        $stmt = $this->conn->prepare("SELECT * FROM reservation");

        if ($stmt->execute()) {
            $reservDet = $stmt->get_result() ; //->fetch_assoc();
            $reservDetOutput = array();
            while($row = $reservDet -> fetch_assoc()) {
                $reservDetOutput[]= $row;
            }
            $stmt -> close();
            print(json_encode($reservDetOutput));
        } else {
            $stmt->close();
            return NULL;
        }
    } //getAllReserDetails() function closed

    public function getAllReserDetails_byBkID($booking_id) {
        $stmt = $this->conn->prepare("SELECT * FROM reservation WHERE booking_id = ?");
        $stmt->bind_param("i", $booking_id);
        if ($stmt->execute()) {
            $reservDeto = $stmt->get_result()->fetch_assoc();
            $stmt -> close();
            return $reservDeto;
        } else {
            $stmt->close();
            return NULL;
        }
    } //getAllReserDetails() function closed

    /**
     * @param $booking_id
     * @return bool
     */
    public function delResvsDet($booking_id) {
        $stmt = $this->conn->prepare("DELETE FROM reservation WHERE booking_id=?");
        $stmt->bind_param("i", $booking_id);
        $delRsvResult = $stmt->execute();
        $stmt->close();
        //check for successful store
        if ($delRsvResult) {
            // print(json_encode($delRsvResult));
            return $delRsvResult;      // return true
        } else {
            return false;
        }
    } //delResvsDet function closed


}//Class Bookings closed