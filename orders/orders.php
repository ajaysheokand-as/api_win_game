<?php
require_once('./config.php');
// require_once('./functions.php');

// $host = "localhost";
// $user = "root";
// $password = "";
// $db = "win";

// $conn = mysqli_connect($host, $user, $password, $db);

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
        if (isset($data['user_id']) && isset($data['period_no']) && isset($data['number']) && isset($data['amount']) && isset($data['order_date'])) {
            $user_id = $data['user_id'];
            $period_no = $data['period_no'];
            $number = $data['number'];
            $amount = $data['amount'];
            $order_date = $data['order_date'];
            if ($result = mysqli_query($conn, "SELECT * FROM orders where user_id=$user_id AND period_no = $period_no AND number=$number AND amount=$amount AND order_date = $order_date")) {
                if (mysqli_num_rows($result) < 1) {
                    $sql = "INSERT INTO `orders`(`user_id`,`period_no`, `number`, `amount`, `order_date`) VALUES ($user_id,$period_no,$number,$amount,$order_date)";
                    if ($result = mysqli_query($conn, $sql)) {
                        $response = array(
                            "success" => true,
                            "error" => ""
                        );
                    } else {
                        $err = mysqli_error($conn);
                    }
                } else {
                    $err = "This Order Aready Exist";
                }
            }
        } else {
            $err = "set key as -> 'user_id', 'number', 'amount', `order_date` ";
        }

        break;
    case "GET":

        break;
}

send_api_res($response, $err);
