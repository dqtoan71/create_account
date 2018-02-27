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

            // Test username, email exists
            $row_user = $this->get_user_info($username, $email);
            if($row_user->num_rows > 0){
                return false;
            }
            $query  = "INSERT INTO users (username, password, email) VALUES ('".$username."','". $password ."','".$email."')";
            return $this->db->query($query);

        }
        return false;

    }

    // Update user
    public function update($param) {
        if (!empty($param['id']) && !empty($param['username']) && !empty($param['email'])) {
            $id = $this->db->real_escape_string($param['id']);
            $username = $this->db->real_escape_string($param['username']);
            $email = $this->db->real_escape_string($param['email']);

            // If not exists id => return
            $row_user = $this->get_user_by_id($id);
            if($row_user->num_rows == 0){
                return false;
            }

            // Test username, email exists
            $row_user = $this->get_user_info($username, $email);
            if($row_user->num_rows > 0){
                return false;
            }

            $query  = "UPDATE users "
                . "SET username = '" . $username . "' ,"
                . "email = '" . $email . "' "
                . "WHERE id = '" . $id . "'";

            return $this->db->query($query);
        }
        return false;
    }

    //Delete user
    public function delete($id) {
        $query = 'DELETE FROM users WHERE id = "' . $id . '"';

        // If not exists id => return
        $row_user = $this->get_user_by_id($id);
        if($row_user->num_rows == 0){
            return false;
        }
        return ($this->db->query($query));
    }

    // Get info about an user
    public function get_user_by_id($id) {
        $query = 'SELECT * FROM users WHERE id = "' . $id . '"';
        return $this->db->query($query);
    }

    // Get info about an user
    public function get_user_info($username, $email) {
        $query = 'SELECT * FROM users WHERE username = "' . $username . '" and email = "'. $email .'"';
        return $this->db->query($query);
    }

    // Get all the existing users
    public function get_users() {
        $query = 'SELECT id, username, email FROM users';
        return ($this->db->query($query));
    }


}