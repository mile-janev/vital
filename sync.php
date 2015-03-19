<?php
if (isset($_POST['email']) && $_POST['email'] != '') {
    //get parameters
    $email = $_POST['email'];
    $paramsNum = $_POST['params_num'];
    
    $params = array();
    for ($i=0; $i<$paramsNum; $i++) {
        $params[$i] = $_POST[$i];
    }
    
    // include db handler
    require_once 'include/DB_Functions.php';
    $db = new DB_Functions();

    $synced = $db->syncUserData($email, $params);
    
    // response Array
    if ($synced) {
        $response = array("status" => 1);
        $response["message"] = "Synced.";
    } else {
        $response = array("status" => 0);
        $response["message"] = "Sync failed.";
    }
    
    echo json_encode($response);
    
} else {
    echo "Server side not available.";
}

