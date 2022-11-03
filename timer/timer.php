<?php 

include("../config.php");
include("../functions.php");
$err = "";
$response = "";
session_start();

// function startCountDown(){
date_default_timezone_set('Asia/Calcutta');

$durationMin = 2;
$durationiSec = 30;

$_SESSION["duration"] = $durationMin;
$_SESSION["seconds"] = $durationiSec;
$_SESSION['start_time'] = date('Y-m-d H:i:s');

$end_time = date("Y-m-d H:i:s", strtotime('+'.$_SESSION["duration"].'minutes' .$_SESSION["seconds"].'seconds' ,strtotime($_SESSION["start_time"])));

// echo date('H:i:s'."\n"); 
// echo $end_time;

$_SESSION["end_time"] = $end_time;

$response = array(
    "success" => true,
    "error" => ""
);

send_api_res($response, $err);

?>