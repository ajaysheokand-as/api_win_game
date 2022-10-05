<?php
// require_once('./config.php');
// require_once('./functions.php');

$host = "localhost";
$user = "root";
$password = "";
$db = "win";

$conn = mysqli_connect($host, $user, $password, $db);

function send_api_res($data, $err){
    if($err != ""){
        $err = array(
            'success' => false,
            'error' => $err
        );
        echo json_encode($err);
    }
    echo json_encode($data);
}

$method = $_SERVER['REQUEST_METHOD'];
$response = "";
$err = "";
$pass = "";
$token = "";
$device_type = "";

switch ($method) {
    case "POST":
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['name']) && isset($data['mobile_no']) && isset($data['email'])) {
            $name = $data['name'];
            $mobile_no = $data['mobile_no'];
            $email = $data['email'];
            $reg_date = date("d/m/Y");

            if ($result = mysqli_query($conn, "SELECT * FROM `users` WHERE mobile_no='$mobile_no' OR email = '$email'")) {
                if (mysqli_num_rows($result) < 1) {
                    $sql = "INSERT INTO `users`(`name`, `mobile_no`, `email`,`reg_date`) VALUES ('$name','$mobile_no','$email',$reg_date)";
                    if ($result = mysqli_query($conn, $sql)) {
                        $response = array(
                            "success" => true,
                            "error" => ""
                        );
                    } else {
                        $err = mysqli_error($conn);
                    }
                } else {
                    $err = "This User Aready Exist";
                }
            }
        } else {
            $err = "set key as -> 'name', 'mobile_no', 'email' ";
        }

        break;
    case "GET":

        break;
}

send_api_res($response, $err);
