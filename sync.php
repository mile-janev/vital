<?php
if (isset($_POST['email']) && $_POST['email'] != '') {
    //get parameters
    $email = $_POST['email'];
    $paramsNum = $_POST['params_num'];
    
    $params = [];
    for ($i=0; $i<$paramsNum; $i++) {
        $params[$i] = $_POST[$i];
    }
    
    // include db handler
    require_once 'include/DB_Functions.php';
    $db = new DB_Functions();

    $db->syncUserData($email, $params);
    
    // response Array
    $response = array("status" => 0);
    $response["message"] = $paramsNum;
    
    echo json_encode($response);
    
} else {
    echo "Server side not available.";
}

