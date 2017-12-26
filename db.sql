#creating database
create database jk_api ;
#selecting database
use jk_api;

#users table
create table users(
   id int(11) primary key auto_increment,
   unique_id varchar(23) not null unique,
   name varchar(50) not null,
   email varchar(100) not null unique,
   mobile varchar(11) not null unique,
   addr   varchar(500) default null ,
   city   varchar(30) not null, 
   img_path_u varchar(255) null,
   encrypted_password varchar(80) not null,
   salt varchar(10) not null,
   token text null,
   m_otp int(11),
  `isVerified` tinyint(1) DEFAULT NULL,
   created_at datetime,
   updated_at datetime null
); 

#roooms table
CREATE TABLE IF NOT EXISTS  rooms(
room_id int(11) unsigned primary key  auto_increment,
room_type_id int(11) unsigned not null,
room_no varchar(255),
Key `room_type_id` (`room_type_id`)
)ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

#beds table
CREATE TABLE IF NOT EXISTS  beds(
bed_id      int(11)     primary key auto_increment,
room_type_id  int(11)   unsigned  not null,
beds_no     varchar(255),
Key `room_type_id` (`room_type_id`)
);

#roomt Types table
CREATE TABLE IF NOT EXISTS room_type(
room_type_id  int(11)   unsigned  not null  primary key   auto_increment,
name            varchar(255) not null,
rt_amt          DECIMAL(10,2) UNSIGNED NOT NULL ,
description     text      ,
img_path        text      ,
capacity        int(11)
);

#reservation table
CREATE TABLE IF NOT EXISTS reservation(
id        int(11)     not null  primary key   auto_increment,
booking_id    bigint(20)  unsigned  not null,
room_id     int(11)   unsigned  not null,
room_type_id  int(11)   unsigned  not null,
bed_reserved      int(11)       null   ,
  KEY `booking_id` (`booking_id`),
  KEY `room_id` (`room_id`),
  KEY `room_type_id` (`room_type_id`)
);

#bookings table
CREATE TABLE IF NOT EXISTS bookings(
id        int(11)     not null  primary key   auto_increment,
user_id     int(11)     not null,
booking_id    bigint(20)    unsigned not null,
booking_time  datetime    not null,
check_in    date      not null,           
check_out   date      not null,
t_cost      DECIMAL(10,2)   UNSIGNED NOT NULL ,
no_persons      int(3)  ,
booking_status  tinyint(1)    not null,
updated_at datetime null,
  KEY `user_id` (`user_id`),
  KEY `booking_id` (`booking_id`)
);


#creating foreign key
ALTER TABLE `rooms`
  ADD CONSTRAINT `roomroom_ibfk_2` FOREIGN KEY (`room_type_id`) REFERENCES `room_type` (`room_type_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `beds`
  ADD CONSTRAINT `bedsroom_ibfk_2` FOREIGN KEY (`room_type_id`) REFERENCES `room_type` (`room_type_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

ALTER TABLE `reservation`
  ADD CONSTRAINT `reservation_bookin` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`booking_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `reservation_rooms` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`room_id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `reservation_roomtype` FOREIGN KEY (`room_type_id`) REFERENCES `room_type` (`room_type_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

 ALTER TABLE `bookings`
  ADD CONSTRAINT `bookinguser` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

#---------------------------------------------INSERT QUERY------------------------

INSERT INTO `room_type` (`room_type_id`,  `name`, `rt_amt`, `description`, `img_path`, `capacity`)
VALUES
('1', 'Non-Ac Room', '400' , 'This is Non-AC Room', '/img/1.jpg', '5'),
('2', 'AC Room','700' , 'This is AC Room', 'http://10.0.0.25/jk_api/img/1.jpg', '5'),
('3', 'Vishist Atithiti', '1100' ,'This is VIP_SUIT', 'http://10.0.0.25/jk_api/img/3.jpg', '5'),
('4', 'Small Hall', '600' , 'This is Small Hall', 'http://10.0.0.25/jk_api/img/8.jpg', '8'),
('5', 'Big Hall','1000' , 'This is Dormitory (25) with facilities', '/img/4.jpg', '15')
;

INSERT INTO `users` (`unique_id`, `name`, `email`, `mobile`,`addr`,`city`, `img_path_u`, `encrypted_password`, `salt`,`token`, `created_at`)
VALUES
('58aab427e1a8b0.31722705', 'Vijay Purohit', 'vijay.pu9@gmail.com', '7568300515', 'Rathore Lane', 'Sirohi', 'http://localhost/jk_api/%23all_img/_userImage/58aab427e1a8b0.31722705.jpeg', 'VZ8upYjvDEp0muTtELUgE0pTLxA1YzE1YTYzNjYy', '5c15a63662', '1234567989' ,NOW()) ,
('58aab427e1a8b0.39728706', 'AlogMsk', 'algomsk@gmail.com', '9549199160', 'Housing Board', 'Aburoad', 'http://localhost/jk_api/%23all_img/_userImage/58aab427e1a8b0.39728706.jpeg', 'VZ8upYjvDEp0muTtELUgE0pTLxA1YzE1YTYzNjYy', '5c15a63662','1234527122' ,NOW());

INSERT INTO `rooms` (`room_id`, `room_type_id`, `room_no`)
VALUES
('1', '1', '101'), ('2', '1', '102'), ('3', '1', '103'), ('4', '1', '104'), ('5', '1', '105'),
('6', '1', '106'), ('7', '1', '107'), ('8', '1', '108'), ('9', '1', '109'), ('10', '1', '110'),
('11', '1', '111'), ('12', '1', '112'), ('13', '1', '113'), ('14', '1', '114'), ('15', '1', '115'),
('16', '1', '116'), ('17', '1', '117'), ('18', '1', '118'), ('19', '1', '119'), ('20', '1', '120'),

('21', '2', '201'), ('22', '2', '202'), ('23', '2', '203'), ('24', '2', '204'), ('25', '2', '205'),
('26', '2', '206'), ('27', '2', '207'), ('28', '2', '208'), ('29', '2', '209'), ('30', '2', '210'),

('31', '3', '301'), ('32', '3', '302'), ('33', '3', '303'), ('34', '3', '304'), ('35', '3', '305'),
('36', '3', '306'), ('37', '3', '307'), ('38', '3', '308'), ('39', '3', '309'), ('40', '3', '310'),

('41', '4', '401'), ('42', '4', '402'), ('43', '4', '403'),

('44', '5', '501'), ('45', '5', '502')
;

# INSERT INTO `beds` (`bed_id`, `room_type_id`, `beds_no`)
# VALUES
# (NULL, '5', '25'), (NULL, '5', '25'), (NULL, '5', '25'),
# (NULL, '6', '50'), (NULL, '6', '50'), (NULL, '6', '50'),
# (NULL, '7', '100'), (NULL, '7', '100'), (NULL, '7', '100'), (NULL, '7', '100');

INSERT INTO `bookings` (`id`, `booking_id`, `user_id`, `booking_time`, `check_in`, `check_out`, `t_cost`, `no_persons`, `booking_status`, `updated_at`)
VALUES
  (1, 11702270981, 1, '2017-02-27 09:28:12', '2017-02-27', '2017-02-28', '0.000', 4, 2, '2017-03-05 11:29:26'),
  (2, 11702271183, 1, '2017-02-27 11:15:54', '2017-02-27', '2017-02-28', '0.000', 4, 2, '2017-03-05 11:29:26'),
  (3, 11702271190, 1, '2017-02-27 11:16:15', '2017-02-27', '2017-02-28', '0.000', 4, 2, '2017-03-05 11:29:26'),
  (4, 11702271175, 1, '2017-02-27 11:16:17', '2017-02-27', '2017-02-28', '0.000', 4, 2, '2017-03-05 11:29:26'),
  (5, 11702271171, 1, '2017-02-27 11:16:19', '2017-02-27', '2017-02-28', '0.000', 4, 2, '2017-03-05 11:29:26')

;


INSERT INTO `reservation` (`id`, `booking_id`, `room_id`, `room_type_id`, `bed_reserved`)
VALUES
  (1, 11702270981, 1, 1, 4),
  (2, 11702271183, 1, 1, 4),
  (3, 11702271190, 2, 1, 4),
  (4, 11702271175, 3, 1, 4),
  (5, 11702271171, 4, 1, 4)
;

#---------------------------------------------ROOMS AVAILABILITY QUERY________________________
#SELECT rm.room_id, rm.room_no FROM rooms rm WHERE rm.room_type_id = 1 AND rm.room_id NOT IN (SELECT resv.room_id FROM reservation resv, bookings boks WHERE resv.booking_id = boks.booking_id AND (resv.room_type_id = 1) AND (('2017-02-20' BETWEEN boks.check_in AND DATE_SUB(boks.check_out, INTERVAL 1 DAY)) OR (DATE_SUB('2017-02-23', INTERVAL 1 DAY) BETWEEN boks.check_in AND DATE_SUB(boks.check_out, INTERVAL 1 DAY)) OR (boks.check_in BETWEEN '2017-02-20' AND DATE_SUB('2017-02-23', INTERVAL 1 DAY)) OR (DATE_SUB(boks.check_out, INTERVAL 1 DAY) BETWEEN '2017-02-20' AND DATE_SUB('2017-02-23', INTERVAL 1 DAY))))

/*
############
register
-Full Name
-email
-mobile No
-city
-password

----------------------------------
----------------------------------

rooms
showing total no of rooms of each type
-------
-room_id			int(10)							// unique room id
-room_type_id		int(10) 						// getting id from room_type table
-room_no			varchar(255) latin1_swedish_ci	// 6,7,8,9,.... no_of rooms of that type (not total no but in seq 1-5)

--------------------------------------
-----------------------------------

beds
total no of beds
-------------------
-bed_id
-room_type_id		-->room
-no_beds										// total no of beds in particular rooms

----------------------------------------
----------------------------------------

room_type
showing total no of types and description
------------
-room_type_id		int(10)						//unique id
-name				varchar(255)				// unique name with ac/nonac/suit
-Dhramshala Non-Ac
-Dhramshala Ac
-Dhramshala VIP Suit
-Dormetry
-Hall
-description    	text						//description rooms available, facilities
-image_path			text						// image for particular room type
-capacity			int(11)						// rough value for total no of persons that can stay in room

---------------------------------
reservation
showing which room and type and booking dates
------------
-id					int(11)
-booking_id			int(11)			-->booking
-room_id			int(11)			-->room
-room_type_id		int(11)			-->room_type
-bed_id				int(11)			-->beds

----------------------------------
booking
-------
-id int(10)
-user_id 		int(10) 				--> user_id				// user_details
-booking_id  	int(10)											// booking id
-booking_time	datetime										// time of booking
-check_in		date
-check_out		date
-booking_status	tinyint(1)										// pending(0) or confirmned (1)
-no_persons     int(3)											// total no of persons booked

-----------------------------------

For checking the availability
first select the check_in check_out
find reservation table then from it booking_id, room_id and room_type_id that is already registered
then display rooms which are available on that date

total no of rooms available are
all rooms according to their respective type
minus
rooms reserved on that date from reservation table

vip_suit
---no_rooms
---capacity
---no_rooms_occupied

dhramshala
---no_rooms
---type
---capacity
---no_rooms_occupied

dormetery
-no_room // total no of dormetery
-no_beds
-no_beds_occupied
-capacity

Hall
-no_room (used as no_hall)
-0
-0

-------------------------
*/