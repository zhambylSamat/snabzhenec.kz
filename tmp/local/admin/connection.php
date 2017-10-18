<?php
// $servername = "srv-db-plesk09.ps.kz:3306";
// $usernm = "woodl_service";
// $password = "Zxmz02&9";
// $dbname = "woodland_service";

// $servername = "srv-pleskdb21.ps.kz:3306";
// $username = "altyn_bilim";
// $password = "glkR283*";
// $dbname = "altynbil_db";

$servername = "localhost";
$usernm = "root";
$password = "";
$dbname = "service";

// $servername = "srv-pleskdb19.ps.kz:3306";
// $username = "byoth_admin";
// $password = "1Ox#zu58";
// $dbname = "byotheak_cosmetic";

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $usernm, $password);
// $mysqli = new mysqli("localhost", "root", "", $dbname);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$conn->exec("set names utf8");
session_set_cookie_params(0);
date_default_timezone_set("Asia/Almaty");
if(!isset($_SESSION)) 
{ 
    session_start();
}
?>