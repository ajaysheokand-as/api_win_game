<?php
require_once('../config.php');
require_once('../functions.php');

$method = $_SERVER['REQUEST_METHOD'];
$response = "";
$err = "";
$pass = "";
$token = "";
$device_type = "";

try {
    switch ($method) {
        case "POST":
            header("Content-Type:application/json", "content-data: JSON");
            $data = json_decode(file_get_contents('php://input'), true);
            
            if (!(isset($data['user_id']) && isset($data['period_no']) && isset($data['number']) && isset($data['amount']))) {
                throw new Exception("Send All Field");
            }
            $user_id = $data['user_id'];
            $period_no = $data['period_no'];
            $number = $data['number'];
            $amount = $data['amount'];
            if ($result = mysqli_query($conn, "SELECT * FROM orders where user_id=$user_id AND period_no = $period_no AND number=$number AND amount=$amount")) {
                if (mysqli_num_rows($result) > 0) {
                    throw new Exception("You already placed this order. Try another one.");
                }
                $sql = "INSERT INTO `orders`(`user_id`,`period_no`, `number`, `amount`) VALUES ($user_id,$period_no,$number,$amount)";
                if ($result = mysqli_query($conn, $sql)) {
                    $response = array(
                        "success" => true,
                        "error" => ""
                    );
                } else {
                    throw new Exception(mysqli_error($conn));
                }
            }

            break;


        case "GET":
                $period_no = 3111;
                $order_date = Date('d-m-y');
                $sql = "SELECT * FROM orders WHERE order_date = $order_date";
                $result = mysqli_query($conn, $sql);
                print_r($result);
            break;
        case "PUT":
            
            break;
        default:
            break;
        }
        send_api_res($response, $err);
} catch (Exception $e) {
    send_api_res($response, $e->getMessage());
}