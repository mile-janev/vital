<?php

class DB_Functions {

    private $db;

    //put your code here
    // constructor
    function __construct() {
        require_once 'DB_Connect.php';
        // connecting to database
        $this->db = new DB_Connect();
        $this->db->connect();
    }

    // destructor
    function __destruct() {
        
    }

    /**
     * Storing new user
     * returns user details
     */
    public function storeUser($name, $email, $password) {
        $password = sha1($password); // encrypted password
        $result = mysql_query("INSERT INTO users(id, name, email, password, created_at, updated_at) VALUES(NULL, '$name', '$email', '$password', NOW(), NOW())");
        // check for successful store
        if ($result) {
            // get user details 
            $id = mysql_insert_id(); // last inserted id
            $result = mysql_query("SELECT * FROM users WHERE id = $id");
            // return user details
            return mysql_fetch_array($result);
        } else {
            return false;
        }
    }

    /**
     * Get user by email and password
     */
    public function getUserByEmailAndPassword($email, $password) {
        $password_val = sha1($password);
        $result = mysql_query("SELECT * FROM users WHERE email = '$email' AND password = '$password_val'") or die(mysql_error());
        // check for result 
        if (mysql_num_rows($result) > 0) {
            $user = mysql_fetch_array($result);
            return $user;
        } else {
            return false;
        }
    }
    
    /**
     * Get user by email
     */
    public function getUserByEmail($email) {
        $result = mysql_query("SELECT * FROM users WHERE email = '$email'") or die(mysql_error());
        // check for result 
        $no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            $result = mysql_fetch_array($result);
            return $result;
        } else {
            return false;
        }
    }

    /**
     * Check user is existed or not
     */
    public function userExists($email) {
        $result = mysql_query("SELECT email from users WHERE email = '$email'");
        if (mysql_num_rows($result) > 0) {
            // user existed 
            return true;
        } else {
            // user not existed
            return false;
        }
    }

    /**
     * Storing new vital sign into table logs
     * returns vital sign details
     */
    public function storeVital($user_id, $sign, $value, $description) {
        $result = mysql_query("INSERT INTO logs(id, sign, value, description, created_at, updated_at, user_id) VALUES(NULL, '$sign', '$value', '$description', NOW(), NOW(), '$user_id')");
        // check for successful store
        if ($result) {
            // get user details 
            $vital_id = mysql_insert_id(); // last inserted id
            $result = mysql_query("SELECT * FROM logs WHERE id = $vital_id");
            // return logs details
            return mysql_fetch_array($result);
        } else {
            return false;
        }
    }
    
    /**
     * Storing new vital sign into table logs
     * returns vital sign details
     */
    public function updateVital($server_id, $value) {
        $result = mysql_query("UPDATE logs SET value='$value', updated_at=NOW() WHERE id='$server_id'");
        // check for successful store
        if ($result) {
            // get user details 
            $vital_id = mysql_insert_id(); // last inserted id
            $result = mysql_query("SELECT * FROM logs WHERE id = $server_id");
            // return logs details
            return mysql_fetch_array($result);
        } else {
            return false;
        }
    }
    
    /**
     * Check if server_id exists in server database
     * returns true if server_id exists, else false
     */
    public function checkServerId($server_id) {
        $result = mysql_query("SELECT id FROM logs WHERE id = $server_id");
        if (mysql_num_rows($result) > 0) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * 
     * @param type $email
     * @param type $params
     */
    public function syncUserData($email, $params) {
        $user = $this->getUserByEmail($email);
        $user_id = $user['id'];
        
        $result1 = mysql_query("DELETE FROM logs WHERE user_id=$user_id");
        
        foreach ($params as $param) {
            $data = explode("<>", $param);
            if ($data[1]) {//If server_id exists
                $result2 = mysql_query("SELECT * FROM logs WHERE id=".$data[1]);
                if (mysql_num_rows($result2) == 0) {
                    $result3 = mysql_query("INSERT INTO logs(id, sign, value, description, created_at, updated_at, user_id) VALUES('$data[1]', '$data[2]', '$data[3]', '$data[4]', '$data[5]', NOW(), '$user_id')");
                } else {
                    $result3 = mysql_query("INSERT INTO logs(id, sign, value, description, created_at, updated_at, user_id) VALUES(NULL, '$data[2]', '$data[3]', '$data[4]', '$data[5]', NOW(), '$user_id')");
                }
            } else {
                $result3 = mysql_query("INSERT INTO logs(id, sign, value, description, created_at, updated_at, user_id) VALUES(NULL, '$data[2]', '$data[3]', '$data[4]', '$data[5]', NOW(), '$user_id')");
            }
        }
        return TRUE;
    }
    
}

?>
