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
        $result = mysql_query("SELECT * FROM users WHERE email = '$email' AND password = '$password_val") or die(mysql_error());
        // check for result 
        $no_of_rows = mysql_num_rows($result);
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
        $no_of_rows = mysql_num_rows($result);
        if ($no_of_rows > 0) {
            // user existed 
            return true;
        } else {
            // user not existed
            return false;
        }
    }

    /**
     * Storing new vital param into table logs
     * returns vital param details
     */
    public function storeVital($user_id, $parameter, $value) {
        $result = mysql_query("INSERT INTO logs(id, parameter, value, created_at, user_id) VALUES(NULL, '$parameter', '$value', NOW(), '$user_id')");
        // check for successful store
        if ($result) {
            // get user details 
            $vital_id = mysql_insert_id(); // last inserted id
            $result = mysql_query("SELECT * FROM logs WHERE id = $vital_id");
            // return user details
            return mysql_fetch_array($result);
        } else {
            return false;
        }
    }
    
}

?>
