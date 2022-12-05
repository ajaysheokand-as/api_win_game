<?php 
    function getUserData($conn, $data){
        // print_r($data);
        if(isset($data['id']) ){
        $userId = $data['id'];

        $query = "SELECT * FROM users WHERE id = $userId";
        $result = mysqli_query($conn, $query);

        $userData = mysqli_fetch_assoc($result);
        // print_r($userData);
        return $userData;

    }
        
    }

    function createOrder($conn, $data,$rzp_response){
        $userid= $data['id'];
        $amount = $data['amount'];
        print_r($rzp_response);
        $rzp_id = $rzp_response['id'];
        $rzp_entity = $rzp_response['entity'];
        $rzp_currency = $rzp_response['currency'];
        $rzp_receipt = $rzp_response['receipt'];
        $rzp_status = $rzp_response['status'];
        $rzp_attempts = $rzp_response['attempts'];
        // $rzp_notes = implode(',', $rzp_response['notes']);
        $rzp_created_at = $rzp_response['created_at'];
        
        $query = "INSERT INTO `transaction`(`userid`, `amount`, `type`,`razor_pay_id`, `rzp_entity`, `rzp_currency`, `rzp_receipt`, `rzp_status`,  `rzp_attempts`, `rzp_created_at`) 
        VALUES ($userid,$amount,'CREDIT','$rzp_id','$rzp_entity','$rzp_currency','$rzp_receipt','$rzp_status','$rzp_attempts',$rzp_created_at)";

        if(mysqli_query($conn,$query)){
            return true;
        }
        return false;
    }
    