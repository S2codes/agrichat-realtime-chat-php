<?php
    
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        echo "from chneg statue";
        print_r($_POST);

        $uid = $_POST['userid'];
        $db_table = $_POST['type'];
        $status= $_POST['status'];

        $sql = "UPDATE status where id = $uid";

    }


?>