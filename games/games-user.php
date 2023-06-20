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





function fetch_games_user($conn, $period_no){
   $sql = "SELECT * FROM `orders` a, `users` b where a.user_id = b.id and a.period_no = '$period_no' ORDER BY a.order_date DESC";
    if ($game = mysqli_query($conn, $sql)) {
        $games =  mysqli_fetch_all($game,MYSQLI_ASSOC);
        $res = [];
        foreach ($games as $game) {
            unset($game['password']);
            array_push($res,$game);
        }
        return $res;

    } else
        return mysqli_error($conn);
}

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
            $fetched_games = fetch_game($conn,TRUE);
            // print_r($fetched_games);
            $res = [];
            while($row = mysqli_fetch_assoc($fetched_games)){
                    array_push($res,
                  [
                    "game" =>$row ,
                    "user" => fetch_games_user($conn, $row['period_no'] )
                    ]
                );
            }
            // foreach($fetched_games as $game) {
            //    array_push($res,
            //       [fetch_games_user($conn, $game )]
            //     );
            //     // echo "Game => ".$game;
            // }
           $response = $res;
        }
}

send_api_res($response, $err);