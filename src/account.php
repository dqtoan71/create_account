<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2/26/2018
 * Time: 2:38 PM
 */

namespace toandang\create_account;


class account
{
    private $db;
    private $username;
    private $email;

    // Create a new user object

    public function __construct($db) {

        $this->db = $db;

        return $this;
    }

    // Get username

    public function get_username() { return $this->username; }

    // Get email

    public function get_email() { return $this->email; }

    // Register a new user

    public function register($param) {

        if (!empty($param['username']) && !empty($param['password']) && !empty($param['email'])) {

            $username = $this->db->real_escape_string($param['username']);
            $password = sha1($this->db->real_escape_string($param['password']));
            $email = $this->db->real_escape_string($param['email']);

            $query  = "INSERT INTO users (username, password, email) VALUES ('".$username."','". $password ."','".$email."')";


            return $this->db->query($query);

        }
        return false;

    }

}