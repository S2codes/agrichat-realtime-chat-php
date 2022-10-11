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

    $data = json_decode(file_get_contents('php://input'), true);
    $name = $data['name'];
    $company = $data['company'];
    $state = $data['state'];
    $district = $data['district'];
    $block = $data['block'];
    $mobile = $data['mobile'];
    $email = $data['email'];
    $activity = $data['activity'];
    $password = $data['password'];

    include "../../db/conn.php";
    $DB = new Database();

    $validation_sql_by_email = "SELECT * FROM `business` WHERE `email` = '$email'";

    //   return if user's mobile number already exits 
    if ($DB->CountRows($validation_sql_by_email) > 0) {
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


    $sql = "INSERT INTO `business`(`name`, `company_name`, `state`, `district`, `block`, `mobile`, `email`, `activity`, `psw`, `token`, `status`) VALUES ('$name','$company','$state','$district','$block','$mobile','$email','$activity','$hash','$token','active')";


    if ($DB->Query($sql)) {
      echo json_encode(
        array(
          'message' => 'Inserted Successfuly',
          'response' => true,
          'status' => '200',
          'data' => [
            'name' => $name,
            'company' => $company,
            'state' => $state,
            'district' => $district,
            'block' => $block,
            'mobile' => $mobile,
            'email' => $email,
            'activity' => $activity,
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