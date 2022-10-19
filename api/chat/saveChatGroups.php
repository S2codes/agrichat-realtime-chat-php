<?php
// fetch all districts  
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET');

include "../auth.php";

// fetch group details 
function fetch_group_details($groupId, $DB){
    $qry = "SELECT * FROM `chatgroups` WHERE id = $groupId";
    return $DB->RetriveSingle($qry);;
}

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    // api authentication 
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

    // get parameters 
    $userid = $_GET['user_id'];
    if (!isset($userid) || $userid == '') {
        echo json_encode(
            array(
                'message' => 'Invalid User',
                'response' => false,
                'status' => '400'
            )
        );
    }

    $sql = "SELECT * FROM `joinedgroups` WHERE userid =$userid";
    
    $total_groups= $DB->CountRows($sql);
    if ($total_groups > 0) {
        $data = $DB->RetriveArray($sql);
        $all_group_details = array();
        foreach ($data as $value) {
            $groupId = $value['groupid'];
            $group_details = fetch_group_details($groupId, $DB);
            $group_details = array(
                "group_id"=> $group_details['id'],
                "group_name"=> $group_details['group_name'],
                "group_category"=> $group_details['group_category'],
                "select_chat"=> $group_details['select_chat'],
                "pin_to_chat"=> $group_details['pin_to_chat']
            );

            array_push($all_group_details, $group_details);
        }
        
        echo json_encode(
            array(
                'message' => 'Success',
                'response' => true,
                'status' => '200',
                'Total Groups' => $total_groups,
                'data' => $all_group_details
            )
        );


    } else {
        echo json_encode(
        array('message' => 'No record Found',
         'response' => false,
          'status' => '401')
        );
    }
}
}

?>