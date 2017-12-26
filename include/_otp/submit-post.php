<?php
include "getpost-lib.php";
if ( isset($_REQUEST["otp_msg"]) && isset($_REQUEST["otp_email"]) && $_REQUEST["otp_number"]) {

    $message            = $_REQUEST["otp_msg"];
    $email              = $_REQUEST["otp_email"];
    $mobileNumber       = $_REQUEST["otp_number"];

    $senderId = "JJTRTH";
    $routeId = "1";
    $serverUrl = "mysms.sms7.biz";
    $authKey = "";

    $otpOutput =  sendsmsPOST($mobileNumber, $senderId, $routeId, $message, $serverUrl, $authKey);
    //to be store in session variable and then showing status is Booking details
    return $otpOutput;
//echo sendsmsGET($mobileNumber,$senderId,$routeId,$message,$serverUrl,$authKey);
} //Main if Closes
/**
 *AUTH_KEY*	        Alphanumeric	Login Authentication Key(This key is unique for every user)
 *smsContent*	    Text	        Enter your message
 *senderId*	        Text	        Enter senderId it should be less then 6 character
 * routeId*	        Integer	        Which route you want use for sending sms enter routeId for particular route.use given Id for route.
 *                                  Transactional Route=1, Promotinol=2, Promotional SenderId=3
 * groupId*	        Integer	        For sending sms to particular group enter groupId for particular group
 * mobileNos*	    Integer	        Mobile number can be entered with country code or without country code Multiple mobile no. should be separated by comma
 * smsContentType*	Text	        "English" for text sms and "Unicode" for Unicode sms
 * */
/**
 *
3001 *	for Success
3002 *	Invalid URL
3003 *	Invalid User/Password
3004 *	Invalid Message Type
3005 *	Invalid Message
3006 *	Invalid Destination
3007 *	Invalid Source
3008 *	Invalid DLR Field
3009 *	Authentication Failed
3010 *	Internal Error
3011 *	Insufficient Balance
3012 *	Responce Time Out
3013 *	Invalid Request Content Type
3014 *	Missing Mobile Number
3015 *	SMs Content for Approval
3016 *	Missing Required Parameter
3017 *	FAIL
3018 *	Expired Account
3019 *	Null Pointer Exception code
3020 *	Empty User Name
3021 *	Empty Password
3022 *	User Name not Available
3023 *	Session Expired
3024 *	Already Exist
3025 *	Empty Group Name
3026 *	Empty Contact Name
3027 *	Empty Mobile Number
3028 *	Empty Group Id
3029 *	Empty City Name
3030 *	Empty Occupation
3041 *	Empty E-Mail Id
3042 *	Group not Exist
3043 *	Empty Message
3044 *	Empty Route Id
3045 *	Empty Sender Id
3047 *	Empty Draft Id
3048 *	Empty First Name
3049 *	Empty Last Name
3050 *	Empty Balance
3051 *	Empty Role Id
3052*	For User Name Length
3053 *	Empty Expiry Date
3054 *	Invalid Date
3055 *	Empty Client Id
3056 *	Invalid Status
3057 *	Empty Country Name
3058 *	Empty State Name
3059 *	Empty Address
3060 *	Empty Date
3061 *	Access Denied
3062 *	Empty Old Password
3063 *	Password not Maching
3064 *	Empty SMS
3065 *	Empty Amount
3066 *	Empty Address
3067*	Empty RequestId
3068 *	Empty Group and MobileNo
3069 *	Empty Description
3070 *	Fail to Change RoleId
3071 *	Empty CallerId
3072 *	Empty File URL and Draft
3073 *	Empty Duration
3074 *	Invalid Request
3075 *	Invalid Aoudio file
3076 *	Missing Upload File
 *
 *
 */