<?php
// Join group
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

        $userId = $_POST['userid'];
        $groupId = $_POST['groupid'];

        if ($userId == '' && $groupId == '') {
            echo json_encode(
                array(
                    'message' => 'Invalid Inputs fields',
                    'response' => false,
                    'status' => '400'
                )
            );
        }

    $check_sql ="SELECT * FROM `joinedgroups` WHERE `userid` = $userId AND `groupid`= $groupId";
    if ($DB->CountRows($check_sql) > 0) {
        echo json_encode(
            array(
                'message' => 'Already in Group',
                'response' => false,
                'status' => '400',
            )
        );

        exit();
    }
    

    $sql = "INSERT INTO `joinedgroups`(`userid`, `groupid`) VALUES ('$userId','$groupId')";
    if ($DB->Query($sql)) {
        echo json_encode(
            array(
                'message' => 'Success! Joined Group',
                'response' => true,
                'status' => '200',
                'data' => [
                    "groupid" => $groupId,
                    "userid" => $userId,
                    "pinned_status" => "0",
                    "selected_status" => "0"
                ]
            )
        );
    }else {
        echo json_encode(
            array(
                'message' => 'Internal Server Error',
                'response' => true,
                'status' => '500'
            )
        );
    }

}
}


?>