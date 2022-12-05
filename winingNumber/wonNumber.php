<?php 
require_once('../config.php');
require_once('../functions.php');

$method = $_SERVER['REQUEST_METHOD'];
$response = "";
$err = "";
$pass = "";
$token = "";
$device_type = "";

    $zero = 7000;
    $one = 500;
    $two = 60000;
    $three = 0;
    $four = 0;
    $five = 0;
    $six = 0;
    $seven = 0;
    $eight = 0;
    $nine = 0;

    // $smallestNumber[10] = array();

function cal_price($number, $amt){

    switch($number){
        case 0:
            $GLOBALS['zero'] += $amt*9;
        break;
        case 1:
             $GLOBALS['one'] += $amt*9;
        break;
        case 2:
            $GLOBALS['two'] += $amt*9;
        break;
        case 3:
            $GLOBALS['three'] += $amt*9;
        break;
        case 4:
            $GLOBALS['four'] += $amt*9;
        break;
        case 5:
            $GLOBALS['five'] += $amt*9;
        break;
        case 6:
            $GLOBALS['six'] += $amt*9;
        break;
        case 7:
            $GLOBALS['seven'] += $amt*9;
        break;
        case 8:
            $GLOBALS['eight'] += $amt*9;
        break;
        case 9:
            $GLOBALS['nine'] += $amt*9;
        break;
        case 10:
            $GLOBALS['five'] = $amt*4.5;
            $GLOBALS['zero'] = $amt*4.5;
        break;
        case 11:
            $GLOBALS['one'] = $amt*2;
            $GLOBALS['three'] = $amt*2;
            $GLOBALS['seven'] = $amt*2;
            $GLOBALS['nine'] = $amt*2;
            $GLOBALS['five'] = $amt*1.5;

        break;
        case 12:
            $GLOBALS['two'] = $amt*2;
            $GLOBALS['four'] = $amt*2;
            $GLOBALS['six'] = $amt*2;
            $GLOBALS['eight'] = $amt*2;
            $GLOBALS['zero'] = $amt*1.5;
        break;
    }
}

function update_won_amount($user_id, $won_amount,$conn, $won_no){
    $user = "SELECT amount FROM users WHERE id = $user_id";
    if($balance = mysqli_query($conn, $user)){
        while ($row = mysqli_fetch_assoc($balance)){
            $balance_amount = $row['amount'];
        }
        $final_amount = $balance_amount + $won_amount;
    }
    $sql = "UPDATE `users` SET `amount`=$final_amount WHERE id = $user_id";
                        if(mysqli_query($conn, $sql)){
                            return array( "won_number" => $won_no,
                                          "won_amount" => $won_amount,
                                          "final_amount"=> $final_amount);
                            }   
}

function insert_amt(array $numbers, $won_no, $period_no, $conn){

    foreach($numbers as $number){
        if($number == 11){
            $sql = "SELECT * FROM orders WHERE number = $number OR number = 5 and period_no = $period_no";
            $result = mysqli_query($conn, $sql);
        }elseif($number == 12){
            $sql = "SELECT * FROM orders WHERE number = $number OR number = 0 and period_no = $period_no";
            $result = mysqli_query($conn, $sql);
        }else{
            $sql = "SELECT * FROM orders WHERE number = $number and period_no = $period_no";
            $result = mysqli_query($conn, $sql);
        }
        
        if($result){
            while ($row = mysqli_fetch_assoc($result)) {
                $user_id = $row['user_id'];
                $amount = $row['amount'];
                if($row == 1){
                    if($number == 10){
                        $won_amount = $amount * 4.5;
                        update_won_amount($user_id, $won_amount,$conn, $won_no);
                    }elseif($number == 11){
                        $won_amount = $amount * 2;
                        return update_won_amount($user_id, $won_amount,$conn, $won_no);    
                    }elseif($number == 12){
                        $won_amount = $amount * 2;
                        return update_won_amount($user_id, $won_amount,$conn, $won_no);   
                    }else{
                        $won_amount = $amount * 9;
                        return update_won_amount($user_id, $won_amount,$conn, $won_no); 
                    }
                }else{
                    if($number == 11){
                        $won_amount = $amount * 2;
                        return update_won_amount($user_id, $won_amount,$conn, $won_no);
                    }elseif($number == 12){
                        $won_amount = $amount * 2;
                        return update_won_amount($user_id, $won_amount,$conn, $won_no);
                    }elseif($number == 5){
                        $won_amount = $amount * 1.5;
                        return update_won_amount($user_id, $won_amount,$conn, $won_no);
                    }elseif($number == 0){
                        $won_amount = $amount * 1.5;
                        return update_won_amount($user_id, $won_amount,$conn, $won_no);

                    }
                }
                
            }
            
        }
    }
}

try {
    switch ($method) {
        case "GET":
                $period_no = $_GET["period_no"];
                $order_date = Date('d-m-y');
                $sql = "SELECT * FROM orders WHERE period_no = $period_no ORDER BY number ";
                $result = mysqli_query($conn, $sql);
                if ($result) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        cal_price($row['number'], $row['amount']);
                    }
                    $arr = array($GLOBALS['zero'], $GLOBALS['one'],$GLOBALS['two'],$GLOBALS['three'],$GLOBALS['four'],$GLOBALS['five'],$GLOBALS['six'],$GLOBALS['seven'],$GLOBALS['eight'],$GLOBALS['nine']);
                    $min_price = min($GLOBALS['zero'], $GLOBALS['one'],$GLOBALS['two'],$GLOBALS['three'],$GLOBALS['four'],$GLOBALS['five'],$GLOBALS['six'],$GLOBALS['seven'],$GLOBALS['eight'],$GLOBALS['nine']);
                    for($i=0; $i<=9;$i++){
                        if($arr[$i]===$min_price){
                            if($i == 0 || $i == 5){
                                $response =  insert_amt(array(0,5,10), $i, $period_no, $conn);
                            }elseif($i == 1 || $i == 3 || $i == 7 || $i == 9){
                                $response = insert_amt(array(1,3,7,9,11), $i, $period_no, $conn);
                            }elseif($i == 2 || $i == 4 || $i == 6 || $i == 8){
                                $response = insert_amt(array(2,4,6,8,12), $i, $period_no, $conn);
                            }
                            
                            break;
                        }
                    }
                }else{  
                    $err = "Error";
                }
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
