<?php
require_once('../config.php');
require_once('../functions.php');
$method = $_SERVER['REQUEST_METHOD'];
$response = "";
$err = "";
$pass = "";
$token = "";
$device_type = "";


if (isset($_FILES['image'])) {
    $image = $_FILES['image'];
    if ($image['error'] === UPLOAD_ERR_OK) {
        $tmp_name = $image['tmp_name'];
        $file_size =$image['size'];
        $file_tmp =$image['name'];
        $file_type=$image['type'];

        $exploded = explode('.', $image['name']);
        $file_ext = end($exploded);
        $file_ext = strtolower($file_ext);

        $image_name = idate("U").".$file_ext";
        $name = "./transactionImage/$image_name";
        
        $expensions= array("jpeg","jpg","png");
    
        if(in_array($file_ext,$expensions)=== false){
            $errors=['message'=>"Extension not allowed, please choose a JPEG or PNG file."];
        }
        
        if($file_size > 2097152){
            $errors=['message'=>'File size must be less then 2 MB'];
        }
        if(empty($errors)==true){
            if(move_uploaded_file($tmp_name, $name)){   
                $response = array(
                    "success" => true,
                    "message" => 'Image Uploaded',
                );
            }else{
                $response = array(
                    "success" => false,
                    "message" => 'Error while uploading image',
                );
            }
        }else{
            $err = $errors;
        }
    }
}

send_api_res($response, $err);
