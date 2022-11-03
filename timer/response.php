<?php
include("../config.php");
include("../functions.php");
$err = "";
$response = "";

session_start();
date_default_timezone_set('Asia/Calcutta');

$from_time1 = date('Y-m-d H:i:s');
$to_time1 = $_SESSION["end_time"];

$timefirst = strtotime($from_time1);
$timesecond = strtotime($to_time1);

$diffinseconds = $timesecond - $timefirst;

$response = array(
    "success" => gmdate("i:s", $diffinseconds),
    "error" => ""
);

send_api_res($response, $err);

// echo gmdate("i:s", $diffinseconds);

?>