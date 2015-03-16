<?php

/**
 * File to handle all API requests
 * Accepts GET and POST
 * 
 * Each request will be identified by TAG
 * Response will be JSON data

  /**
 * check for POST request 
 */
if (isset($_POST['tag']) && $_POST['tag'] != '') {
    // get tag
    $tag = $_POST['tag'];

    // include db handler
    require_once 'include/DB_Functions.php';
    $db = new DB_Functions();

    // response Array
    $response = array("tag" => $tag, "status" => 0);

    // check for tag type
    if ($tag == 'login') {
        // Request type is check Login
        $email = $_POST['email'];
        $password = $_POST['password'];

        // check for user
        $user = $db->getUserByEmailAndPassword($email, $password);
        if ($user) {
            // user found
            $response["status"] = 1;
            $response["user"]["name"] = $user["name"];
            $response["user"]["email"] = $user["email"];
            $response["user"]["created_at"] = $user["created_at"];
            $response["user"]["updated_at"] = $user["updated_at"];
            echo json_encode($response);
        } else {
            // user not found
            // echo json with error = 1
            $response["status"] = 0;
            $response["message"] = "Incorrect email or password.";
            echo json_encode($response);
        }
    } else if ($tag == 'register') {
        // Request type is Register new user
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        // check if user is already existed
        if ($db->userExists($email)) {
            // user is already existed - error response
            $response["status"] = 0;
            $response["message"] = "This email is already registered.";
            echo json_encode($response);
        } else {
            // store user
            $user = $db->storeUser($name, $email, $password);
            if ($user) {
                // user stored successfully
                $response["status"] = 1;
                $response["user"]["name"] = $user["name"];
                $response["user"]["email"] = $user["email"];
                $response["user"]["created_at"] = $user["created_at"];
                $response["user"]["updated_at"] = $user["updated_at"];
                echo json_encode($response);
            } else {
                // user failed to store
                $response["status"] = 0;
                $response["message"] = "Error occured in Registartion. Pleas try again.";
                echo json_encode($response);
            }
        }
    } else if ($tag == 'values') {
        $update = FALSE;
        $value = $_POST['value'];
        $email = $_POST['email'];
        $sign = $_POST['sign'];
    
        if (isset($_POST['server_id']) && $_POST['server_id'] != "") {
            $server_id = $_POST['server_id'];
            $update = $db->checkServerId($server_id);
        }
        
        if (!$update) {
            $user_DB = $db->getUserByEmail($email);
            if ($user_DB != false) {
                //user found
                $user_id = $user_DB["id"];
                $values = $db->storeVital($user_id, $sign, $value);
                if ($values) {
                    $response["server_id"] = $values["id"];
                }
                $response["status"] = 1;
                $response["message"] = "Your data is logged.";
            } else {
                //user not found
                $response["status"] = 0;
                $response["message"] = "Error with saving.";
            }
            
        } else {
            $values = $db->updateVital($server_id, $value);
            if ($values) {
                $response["server_id"] = $values["id"];
                $response["status"] = 1;
                $response["message"] = "Your data is updated.";
            } else {
                $response["status"] = 0;
                $response["message"] = "Error with updating.";
            }
        }
        echo json_encode($response);
    } else {
        echo "Invalid Request";
    }
} else {
    echo "Server side not available.";
}
?>
