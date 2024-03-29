<?php
require_once('../config.php');
require_once('../functions.php');
require_once('./function.php');

$method = $_SERVER['REQUEST_METHOD'];
$response = "";
$err = "";
$pass = "";
$token = "";
$device_type = "";


function create_game($conn, $data)
{
    global $err, $response;
    if (isset($data['period_no']) && isset($data['is_active'])) {
        $period_no = $data['period_no'];
        $start_time = $data['start_time'];
        $end_time = $data['end_time'];
        $is_active = $data['is_active'];

        $sql = "INSERT INTO `games`(`period_no`, `start_time`, `end_time`, `is_active`) VALUES ('$period_no','$start_time','$end_time','$is_active')";
        if ($result = mysqli_query($conn, $sql)) {
            $response = array(
                "success" => true,
                "error" => ""
            );
        } else
            $err = mysqli_error($conn);
    } else{
    $response = createNewMatch($conn);}
}

// function update_game($conn, $id){
//     global $err, $response;
//     $sql = "UPDATE `transaction` SET `status`=1 WHERE id=$id";
//     if ($update_result = mysqli_query($conn, $sql)) {
//         $response = array(
//             "success" => true,
//             "error" => ""
//         );
//     } else
//         $err = mysqli_error($conn);
// }



switch ($method) {
    case "POST": {
            $data = json_decode(file_get_contents('php://input'), true);
            create_game($conn, $data);
            break;
        }
    // case "PUT":{
    //     $data = json_decode(file_get_contents('php://input'), true);
    //     if (isset($data['id'])) {
    //         $id = $data['id'];
    //         update_game($conn,$id);
    //     }
    // }
    case "GET": {
            fetch_game($conn);
        }
}

send_api_res($response, $err);