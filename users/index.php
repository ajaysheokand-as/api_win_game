<?php
require_once('../config.php');
require_once('../functions.php');
require_once('./user.php');

$method = $_SERVER['REQUEST_METHOD'];
$response = "";
$err = "";
$pass = "";
$token = "";
$device_type = "";


switch ($method) {
    case "POST":
        $data = json_decode(file_get_contents('php://input'), true);
        $type = isset($data['type']);
        if ($type == "login") {
            login_user($data);
        }
        if ($type == "sign_in") {
            create_user($data);
        }
        break;
    case "GET":
        if (empty($_GET["id"])) {
            $data = json_decode(file_get_contents('php://input'), true);
            if (isset($data['mobile_no']) && isset($data['password'])) {
                $mobile_no = $data['mobile_no'];
                $password = $data['password'];
                if ($result_mob = mysqli_query($conn, "SELECT * FROM `users` WHERE mobile_no='$mobile_no'")) {
                    if (mysqli_num_rows($result_mob) > 0) {
                        if ($result_password = mysqli_query($conn, "SELECT * FROM `users` WHERE mobile_no='$mobile_no' AND password = '$password'")) {
                            if (mysqli_num_rows($result_password) > 0) {
                                // $accessToken = genAccessToken(foundUser);
                                // response.status(200).json({ accessToken });
                                $response = array(
                                    "success" => true,
                                    "error" => ""
                                );
                            } else {
                                $err = "Wrong Password. Try Again";
                            }
                        }
                    } else {
                        $err = "This mobile No is not register with us.";
                    }
                }
            } else {
                $err = "set key as ->'mobile_no','password' ";
            }
        } else {
            $id = $_GET["id"];
            $result = mysqli_query($conn, "SELECT * FROM `users` WHERE id='$id' LIMIT 1");
            if ($result) {
                while ($row = mysqli_fetch_array($result)) {
                    $response = $row;
                }
            } else {
                $err = "Error";
            }
        }
        break;
    case "PUT":
        $id = $_GET["id"];
        $data = json_decode(file_get_contents('php://input'), true);
        $name = $data['name'];
        $mobile_no = $data['mobile_no'];
        $email = $data['email'];
        $password = $data['password'];

        $result = mysqli_query($conn, "UPDATE `users` SET `name`='$name', `mobile_no`=$mobile_no, `email`=$email, `password`='$password' WHERE id='$id'");
        if ($result) {
            $response = array(
                'success' => true,
                'error' => ""
            );
        } else {
            $err = "User Updation Failed";
        }
        break;
    case "DELETE":
        $id = $_GET["id"];
        $result = mysqli_query($conn, "UPDATE `users` SET `status`=0 WHERE id='$id'");
        if ($result) {
            $response = array(
                'success' => true,
                'error' => ""
            );
        } else {
            $err = "Failed You are not able to delete.";
        }
        break;
}