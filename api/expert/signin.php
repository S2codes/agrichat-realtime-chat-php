<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorization, X-Requested-with');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    // $data = json_decode(file_get_contents('php://input'), true);

    if (!isset($_GET['api_key'])) {
        echo json_encode(
            array(
                'message' => 'Invalid api key',
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

        if (isset($_POST['email'])) {
            $email = $_POST['email'];
        }else {
            $email='';
        }

        if (isset($_POST['password'])) {
            $user_password = $_POST['password'];
        }else {
            $user_password = '';
        }

        if (isset($_POST['user_id'])) {
            $token_id = $_POST['user_id'];
        }else {
            $token_id = '';
        }

        include "../../db/conn.php";
        $DB = new Database();

        if ($token_id == '' || !isset($token_id)) {
            $sql = "SELECT * FROM `expert` WHERE `email` = '$email'";
            if ($DB->CountRows($sql) > 0) {
                $userData = $DB->RetriveSingle($sql);
                $password_verify = password_verify($user_password, $userData['psw']);
                if ($password_verify) {
                    $userId = $userData['id'];
                    $new_token = bin2hex(random_bytes(10));
                    $q = "UPDATE `business` SET `token`='$new_token' WHERE id = $userId";
                    if ($DB->Query($q)) {
                        $userData = $DB->RetriveSingle($sql);
                        echo json_encode(
                            array(
                                'message' => "Validate Success",
                                'response' => true,
                                'status' => '401',
                                "data" => [
                                    'name' => $userData['name'],
                                    'state' => $userData['state'],
                                    'district' => $userData['district'],
                                    'block' => $userData['block'],
                                    'mobile' => $userData['mobile'],
                                    'email' => $userData['email'],
                                    'field' => $userData['field'],
                                    'status' => $userData['status'],
                                    'user_id' => $userData['token']
                                ]
                            )
                        );
                    } else {
                        echo json_encode(
                            array(
                                'message' => "Query Failed",
                                'response' => false,
                                'status' => '500'
                            )
                        );
                    }
                } else {
                    // if password not matched 
                    echo json_encode(
                        array(
                            'message' => "Invalid credentials",
                            'response' => false,
                            'status' => '401'
                        )
                    );
                }
            } else {
                // if mobile number not found 
                echo json_encode(
                    array(
                        'message' => "Invalid Credentials",
                        'status' => false
                    )
                );
                return;
            }
        }
        //   if token set 
        else {
            $token_query = "SELECT * FROM `expert` WHERE `token` = '$token_id'";
            if ($DB->CountRows($token_query) > 0) {
                $userData = $DB->RetriveSingle($token_query);
                echo json_encode(
                    array(
                        'message' => "Validate Success",
                        'status' => true,
                        "data" => $userData
                    )
                );
            } else {
                echo json_encode(
                    array(
                        'message' => "Invalid Credentials",
                        'status' => false
                    )
                );
                return;
            }
        }
    }
}

?>