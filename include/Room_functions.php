<?php
 //Get Rooms Functions
/**
 * @author Vijay Purohit
 * @link vijay.pu9@gmail.com_
 */
class RoomsSearch
{
	private $conn;
   /**
     * RoomsSearch constructor.
     */
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
     *
     */

    /**  Get Available Rooms
     * @param $roomTypeId
     * @param $roomTypeName
     * @param $mysqlCheckInDate
     * @param $mysqlCheckOutDate
     * @return array
     */
    public function getAvailableRooms($roomTypeId, $roomTypeName, $mysqlCheckInDate, $mysqlCheckOutDate){
		//Search Query to detect no of rooms available in a particular Date of check in and check outs
		$searchsql = "
		SELECT rm.room_id, rm.room_no
		  FROM rooms rm
		 WHERE rm.room_type_id = ".$roomTypeId."
			   AND rm.room_id NOT IN
					  (SELECT resv.room_id
						 FROM reservation resv, bookings boks
						WHERE  boks.booking_status = 1 AND resv.booking_id = boks.booking_id
							  AND resv.room_type_id = ".$roomTypeId."
							  AND (('".$mysqlCheckInDate."' BETWEEN boks.check_in AND DATE_SUB(boks.check_out, INTERVAL 1 DAY))
							   OR (DATE_SUB('".$mysqlCheckOutDate."', INTERVAL 1 DAY) BETWEEN boks.check_in AND DATE_SUB(boks.check_out, INTERVAL 1 DAY)) OR (boks.check_in BETWEEN '".$mysqlCheckInDate."' AND DATE_SUB('".$mysqlCheckOutDate."', INTERVAL 1 DAY)) OR (DATE_SUB(boks.check_out, INTERVAL 1 DAY) BETWEEN '".$mysqlCheckInDate."' AND DATE_SUB('".$mysqlCheckOutDate."', INTERVAL 1 DAY)))) " ;

        $getAvailRoomResArr = array();
		$getAvailRoomRes = mysqli_query( $this->conn  ,$searchsql);
		
		while ($row = mysqli_fetch_assoc($getAvailRoomRes)) {
			$getAvailRoomResArr[] = $row;
		}
		header('Content-Type:Application/json');
        return $getAvailRoomResArr;
 	} // getAvailableRooms() function closed

    /**
     * Get All Rooms does not depends upon Availability
     */
    public function getAllRooms() {
        $getAlRoomResArr = array();
        $AllRoomsQuery = "SELECT * FROM rooms";
        $getAlRoom = mysqli_query( $this->conn  ,$AllRoomsQuery);

        while ($row = mysqli_fetch_assoc($getAlRoom)) {
			$getAlRoomResArr[] = $row;
		}
        mysqli_free_result($getAlRoom);
        return $getAlRoomResArr;
//		echo json_encode($getAlRoomResArr);
    }  // getAllRooms() closed

    /** Get all Room Type Details through bind param method
     * @return array|null
     */
    public function getAllRoomsType() {
        $roomsTypeOutput = array();

        $stmt = $this->conn->prepare("SELECT * FROM room_type ");

        if ($stmt->execute()) {
            $roomsTypeA = $stmt->get_result()  ; //->fetch_assoc();

            while($row = $roomsTypeA -> fetch_assoc()) {
        		$roomsTypeOutput[]= $row;    
    		}
            $stmt -> close();
            // print(json_encode($roomsTypeOutput));
            return $roomsTypeOutput;
        }
         else {
            $stmt->close();
            return NULL;
         }
        // To Send Custom 2-D Array Json Format 
  		// $roomsTypeF['roomsTypeA'][] = array(
        //     	  'name' => $roomsTypeA,
        //    		  'data' => $roomsTypeOutput
  		// 	);
  		// print(json_encode($roomsTypeF));
  	} //getAllRoomsType() function closed

    /** // get all the details of any room type by its room_type_id
     * @param $room_t_id
     * @return array|null
     */
    public function getRoomTypeID($room_t_id) {
//        $roomsTypeOut = array();
        $stmt = $this->conn->prepare("SELECT * FROM room_type where room_type_id = ?");
        $stmt->bind_param("i", $room_t_id);

        if ($stmt->execute()) {
            $roomsType = $stmt->get_result()->fetch_assoc()  ;
//            while($row = $roomsType -> fetch_assoc()) {
//                $roomsTypeOut[]= $row;
//            }
            $stmt -> close();
//             print(json_encode($roomsTypeOut));
//             print_r($roomsTypeOut);
//              return $roomsTypeOut;
                return $roomsType;
         } else {
            $stmt->close();
            return NULL;
        }
    } //getRoomTypeID() function closed

    public function getRoomID($room_id) {
//        $roomsTypeOut = array();
        $stmt = $this->conn->prepare("SELECT * FROM rooms where room_id = ?");
        $stmt->bind_param("i", $room_id);

        if ($stmt->execute()) {
            $rooms = $stmt->get_result()->fetch_assoc()  ;
            $stmt -> close();
            return $rooms;
        } else {
            $stmt->close();
            return NULL;
        }
    } //getRoomID() function closed

    /**
     * @param $startDate
     * @param $endDate
     * @param bool $nightAdjustment
     * @return array
     */
    public function getDateRangeArray($startDate, $endDate, $nightAdjustment = true) {
        $date_arr = array();
        $day_array=array();
        $time_from = mktime(1,0,0,substr($startDate,5,2), substr($startDate,8,2),substr($startDate,0,4));
        $time_to = mktime(1,0,0,substr($endDate,5,2), substr($endDate,8,2),substr($endDate,0,4));

        if ($time_to >= $time_from) {
            if($nightAdjustment){
                while ($time_from < $time_to) {
                    $date_arr[] = date('Y-m-d',$time_from);
                    $day_array[]  = date('D',$time_from);
                    $time_from+= 86400; // add 24 hours
                }
            }else{
                while($time_from <= $time_to) {
                    $date_arr[] = date('Y-m-d',$time_from);
                    $day_array[]  = $time_from;
                    $time_from+= 86400; // add 24 hours
                }
            }
        }
        $total_array = array(
          "date"  => $date_arr ,
          "day" =>   $day_array
        );
//         print(json_encode($total_array));
        return $total_array;
    }// date array range function closed

} //RoomsSearch Class Closed