<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Access-Control-Allow-Methods, Content-Type, Authorization, X-Requested-with');

include "../auth.php";
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
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

    // $data= json_decode(file_get_contents('php://input'), true);
    $name = $_POST['name'];
    $state = $_POST['state'];
    $district = $_POST['district'];
    $block = $_POST['block'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $field = $_POST['field'];
    $password = $_POST['password'];

    include "../../db/conn.php";
    $DB = new Database();

    $validation_sql_by_mobile = "SELECT * FROM `expert` WHERE `mobile` = '$mobile'";
    //   return if user's mobile number already exits 
    if ($DB->CountRows($validation_sql_by_mobile) > 0) {
      echo json_encode(
        array(
          'message' => "User already exits",
          'response' => false,
          'status' => '401'
        )
      );
      return;
    }

    $token = bin2hex(random_bytes(10));
    $hash = password_hash($password, PASSWORD_BCRYPT);

    $sql = "INSERT INTO `expert`(`name`, `state`, `district`, `block`, `mobile`, `email`, `field`, `psw`, `token`, `status`) VALUES ('$name','$state','$district','$block','$mobile','$email','$field','$hash','$token','active')";


    if ($DB->Query($sql)) {
      echo json_encode(
        array(
          'message' => 'Inserted Successfuly',
          'response' => false,
          'status' => '200',
          'data' => [
            'name' => $name,
            'state' => $state,
            'district' => $district,
            'block' => $block,
            'mobile' => $mobile,
            'email' => $email,
            'field' => $field,
            'status' => 'active',
            'user_id' => $token
          ]
        )
      );
      return;
    } else {
      echo json_encode(
        array(
          'message' => 'Error! Query Failed',
          'response' => false,
          'status' => '500'
        )
      );
      return;
    }
  }
}


?>