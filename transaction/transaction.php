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
                        "message" => "Amount Added Successfully",
                        "error" => ""
                    );
                } else
                    $err = mysqli_error($conn);
    }
    $err = "Enter Data in all field";
}

function update_transaction($conn, $id,$status){
    global $err, $response;
    $sql = "UPDATE `transaction` SET `status`= $status WHERE id= $id";
    if ($update_result = mysqli_query($conn, $sql)) {
        $response = array(
            "success" => true,
            "error" => ""
        );
    } else
        $err = mysqli_error($conn);
}

function get_transaction($conn,$status){
    global $err, $response;
    if($status == -1){
        $sql = "SELECT `transaction`.*, `users`.`name` FROM `transaction` , `users` WHERE users.id = transaction.userid;";
    }else{
        $sql = "SELECT `transaction`.*, `users`.`name` FROM `transaction` , `users` WHERE transaction.status = $status AND users.id = transaction.userid;";
    }
    if ($all_transaction = mysqli_query($conn, $sql)) {
        $data = [];
        while ($row = mysqli_fetch_assoc($all_transaction)) {
            $data[] = $row;
        }
        if(!empty($data)){
            $response = array(
                "success" => true,
                "data" => $data,
                "error" => ""
            );
        }else{
            $response = array(
                "success" => true,
                "data" => "No Data Found",
                "error" => ""
            );
        }
        
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
            $status = $data['status'];
            update_transaction($conn, $id,$status);
        }
        break;
    }
    case "GET":{
        $status = $_GET["status"];
        get_transaction($conn,$status);
    }
}

send_api_res($response, $err);

?>
