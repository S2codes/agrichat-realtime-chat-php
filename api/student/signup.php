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

    // $data = json_decode(file_get_contents('php://input'), true);
    $name = $_POST['name'];
    $state = $_POST['state'];
    $district = $_POST['district'];
    $block = $_POST['block'];
    $mobile = $_POST['mobile'];
    $email = $_POST['email'];
    $standard = $_POST['standard'];
    $institute = $_POST['institute'];
    $password = $_POST['password'];

    // include "../../db/conn.php";
    // $DB = new Database();

    $validation_sql_by_email = "SELECT * FROM `students` WHERE `email` = '$email'";

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


    $sql = "INSERT INTO `students`(`name`, `state`, `district`, `block`, `mobile`, `email`, `standard`, `institute`, `psw`,`token`, `status`) VALUES ('$name','$state','$district','$block','$mobile','$email','$standard','$institute','$hash','$token', 'active')";


    if ($DB->Query($sql)) {
      echo json_encode(
        array(
          'message' => 'Inserted Successfuly',
          'response' => true,
          'status' => '200',
          'data' => [
            'name' => $name,
            'state' => $state,
            'district' => $district,
            'block' => $block,
            'mobile' => $mobile,
            'email' => $email,
            'standard' => $standard,
            'institute' => $institute,
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
