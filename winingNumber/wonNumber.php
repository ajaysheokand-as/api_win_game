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
                            $response = $i;
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


?>