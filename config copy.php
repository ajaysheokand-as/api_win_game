<?php
$host = "localhost";
$user = "root";
$password = "";
$db = "win";

$conn = mysqli_connect($host, $user, $password, $db);

// if($conn){
//     echo("Conn Successfull");
// }
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
header('Access-Control-Allow-Headers: token, Content-Type');
header('Access-Control-Max-Age: 1728000');

?>