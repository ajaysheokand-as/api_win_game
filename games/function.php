<?php
function fetch_game($conn,$returnResponse = false)
{
    global $err, $response;
    $sql = "SELECT * FROM `games` ORDER BY create_at DESC";
    if ($game = mysqli_query($conn, $sql)) {
        if($returnResponse === true){
            return $game;
        }
        $game_data = mysqli_fetch_array($game);
        $response = array(
            "success" => true,
            "data" => $game_data,
            "error" => ""
        );
    } else
        $err = mysqli_error($conn);
        if($returnResponse){
            return $err;
        }
}

function createNewMatch($conn){
    $currentTime = date('Y-m-d H:i:s');
    $futureDateTime = date('Y-m-d H:i:s', strtotime($currentTime . '+30 minutes'));
    $new_period_number = sprintf("%06d", mt_rand(1, 999999));

    $sql = "UPDATE `games` SET is_active = 0 where is_active = 1";
    if(mysqli_query($conn, $sql) === FALSE){
        return FALSE;
    }

    $sql = "INSERT INTO `games`(`period_no`, `start_time`, `end_time`, `is_active`) VALUES ('$new_period_number','$currentTime', '$futureDateTime',1)";
    if(mysqli_query($conn, $sql) === FALSE){
        return mysqli_error($conn);
    }

     $sql = "SELECT * FROM `games` WHERE is_active = 1 AND start_time = '$currentTime'";
    if(($res = mysqli_query($conn, $sql)) === FALSE){
        return mysqli_error($conn);
    }

    $result = [];
    while($row = mysqli_fetch_assoc($res)){
        array_push($result, $row);
    }
    echo '49';

    return $result;

}