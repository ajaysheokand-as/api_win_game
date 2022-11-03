<?php 
declare(strict_types=1);

use Firebase\JWT\JWT;

require_once('../vendor/autoload.php');

function send_api_res($data, $err){
    if($err != ""){
        $err = array(
            'success' => false,
            'error' => $err
        );
        echo json_encode($err);
    }else{
    echo json_encode($data);
    }
}

function create_jwt_token($data){
    $secretKey  = 'bGS6lzFqvvSQ8ALbOxatm7/Vk7mLQyzqaS34Q4oR1ew=';
    $issuedAt   = new DateTimeImmutable();
    $expire     = $issuedAt->modify('+6 minutes')->getTimestamp();      // Add 60 seconds

    $data = [
        'iat'  => $issuedAt->getTimestamp(),         // Issued at: time when the token was generated
        'nbf'  => $issuedAt->getTimestamp(),         // Not before
        'exp'  => $expire,                           // Expire
        'data' => $data,                     // User name
    ];
    return JWT::encode(
        $data,
        $secretKey,
        'HS512'
    );
}

function verify_token($conn,$token){
    $result = mysqli_query($conn, "SELECT * FROM logined_user WHERE token = $token");
    if(mysqli_num_rows($result)>0){
        return true;
    }
        return false;
}
