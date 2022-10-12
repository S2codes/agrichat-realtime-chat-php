<?php
    
    include "../../db/conn.php";
    $DB = new Database();
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        $uid = $_POST['userid'];
        $db_table = $_POST['type'];
        $status= $_POST['status'];

        $sql = "UPDATE `$db_table` SET `status`='$status' WHERE id = '$uid'";
        if ($DB->Query($sql)) {
            echo json_encode(
                array(
                    "msg" => "success",
                    "response" => true
                )
            );
        }else {
            echo json_encode(
                array(
                    "msg" => "something error ocuured",
                    "response" => false
                )
            );
        }

    }


?>