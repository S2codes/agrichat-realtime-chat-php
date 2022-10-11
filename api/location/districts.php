<?php
// fetch all districts  
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

include "../auth.php";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

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
    // $stateId = $_POST['state_id'];
    $stateId = $_GET['state_id'];

    // include "../../db/conn.php";
    // $DB = new Database();

    $sql = "SELECT * FROM `districts` WHERE state_id = '$stateId'";
    
    if ($DB->CountRows($sql) > 0) {
        $data = $DB->RetriveArray($sql);
        echo json_encode(
            array(
                'message' => 'Success',
                'response' => true,
                'status' => '200',
                'data' => $data
            )
        );
    } else {
        echo json_encode(array('message' => 'No record Found',
         'response' => false,
          'status' => '401'));
    }
}
}


?>