<?php 
require_once('../config.php');
require_once('../functions.php');

$method = $_SERVER['REQUEST_METHOD'];
$response = "";
$err = "";
$pass = "";
$token = "";
$device_type = "";


function create_transaction($conn,$data){
    global $err, $response;
    if (isset($data['tid']) && isset($data['userid']) && isset($data['amount'])) {
        $tid = $data['tid'];
        $userid = $data['userid'];
        $amount = $data['amount'];

                $sql = "INSERT INTO `transaction`(`tid`, `userid`, `amount`) VALUES ('$tid','$userid','$amount')";
                if ($result = mysqli_query($conn, $sql)) {
                    $response = array(
                        "success" => true,
                        "error" => ""
                    );
                } else
                    $err = mysqli_error($conn);
    }
    $err = "Enter Data in all field";
}

function update_transaction($conn, $id){
    global $err, $response;
    $sql = "UPDATE `transaction` SET `status`=1 WHERE id=$id";
    if ($update_result = mysqli_query($conn, $sql)) {
        $response = array(
            "success" => true,
            "error" => ""
        );
    } else
        $err = mysqli_error($conn);
}

switch($method){
    case "POST":{
            $data = json_decode(file_get_contents('php://input'), true);
            create_transaction($conn, $data);
            break;
        }
    case "PUT":{
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['id'])) {
            $id = $data['id'];
            update_transaction($conn,$id);
        }
    }
}

send_api_res($response, $err);

?>
