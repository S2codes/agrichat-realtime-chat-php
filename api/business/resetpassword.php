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
                'response' => true,
                'status' => '401'
            )
        );
        exit();
    } else {

        // $data = json_decode(file_get_contents('php://input'), true);
        $token = $_GET['user_id'];
        $new_password = $_POST['password'];
        $validation_sql = "SELECT * FROM `business` WHERE `token` = '$token'";

        //   return if user's mobile number already exits 
        if ($DB->CountRows($validation_sql) > 0) {

            $user_data = $DB->RetriveSingle($validation_sql);
            $id = $user_data['id'];
            $new_hash = password_hash($new_password, PASSWORD_BCRYPT);
            $new_token = bin2hex(random_bytes(10));

            $qry = "UPDATE `business` SET `psw`='$new_hash',`token`='$new_token' WHERE `id` = '$id'";
            if ($DB->Query($qry)) {
                $userData = $DB->RetriveSingle($token_query);
                echo json_encode(
                    array(
                        'message' => "Success! your Password is Changed",
                        'response' => true,
                        'status' => '200',
                        'data' => [
                            'name' => $userData['name'],
                            'company' => $userData['company'],
                            'state' => $userData['state'],
                            'district' => $userData['district'],
                            'block' => $userData['block'],
                            'mobile' => $userData['mobile'],
                            'email' => $userData['email'],
                            'activity' => $userData['activity'],
                            'status' => $userData['status'],
                            'user_id' => $userData['token']
                        ]
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
