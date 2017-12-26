
<?php
session_start();
$time = $_SESSION['time'] = time();
  date_default_timezone_set('Asia/Kolkata');
  header('Content-Type:Application/json');
//
/**
 * Database config variables Config.php 
 */
define("DB_HOST", "localhost");
define("DB_USER", "root");
define("DB_PASSWORD", "vijaypurohit@123");
define("DB_DATABASE", "jk_api");
// define("DB_DATABASE", "jirawala");