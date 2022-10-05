<?php 

function send_api_res($data, $err){
    if($err != ""){
        $err = array(
            'success' => false,
            'error' => true
        );
        echo json_encode($err);
    }
    echo json_decode($data);
}