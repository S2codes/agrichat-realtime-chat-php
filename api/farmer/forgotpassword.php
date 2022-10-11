<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorization, X-Requested-with');

include "../auth.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (!isset($_GET['api_key']) && !isset($_GET['user_id'])) {
        echo json_encode(
            array(
                'message' => 'Invalid',
                'response' => false,
                'status' => '401'
            )
        );
        exit();
    }

    if ($api_auth != $_GET['api_key']) {
        echo json_encode(
            array(
                'message' => 'Invalid api key',
                'response' => false,
                'status' => '401'
            )
        );
        exit();
    } else {

        // $data = json_decode(file_get_contents('php://input'), true);
        $token = $_GET['user_id'];

        // include "../../db/conn.php";
        // $DB = new Database();

        $validation_sql= "SELECT * FROM `farmers` WHERE `token` = '$token'";

        //   return if user's mobile number already exits 
        if ($DB->CountRows($validation_sql) > 0) {

            $user_data = $DB->RetriveSingle($validation_sql);

            $name = $user_data['name'];
            $to = $user_data['email'];
            $subject = "Forgot Password";
            $msg = "Hello $name, as per your request to reset your password please go through the link. <br>
        <a href='http://localhost/agrichat/resetpassword?user_id=$token'>http://localhost/agrichat/resetpassword</a>
        <br>Thank You
        <br>Agri Chat.
        ";
            $headers  = 'MIME-Version: 1.0' . "\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
            $headers .= 'From: Agri Chat <support@agrichat.in>' . "\r\n";
            if (mail($to, $subject, $msg, $headers)) {

                echo json_encode(
                    array(
                        'message' => "Success! Check Your Mail to reset your Password",
                        'response' => true,
                        'status' => '200'
                    )
                );
            }
            return;
        } else {
            echo json_encode(
                array(
                    'message' => 'Invalid Credentials',
                    'response' => false,
                    'status' => '401'
                )
            );
            exit();
        }
    }
}
