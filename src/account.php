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

        session_start();

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

            $query  = 'INSERT INTO users (user, password, email) '
                . 'VALUES ("' . $username . '", "' . $password . '", "' . $email . '")';

            return $this->db->query($query);

        }
        return false;

    }

    // Update an existing user's password

    public function update($username) {

        if (!empty($_POST['email']) && $_POST['email'] !== $_POST['old_email']) {

            $this->email = $this->db->real_escape_string($_POST['email']);

            $query  = 'UPDATE users '
                . 'SET email = "' . $this->email . '" '
                . 'WHERE user = "' . $username . '"';

            if ($this->db->query($query)) $this->msg[] = 'Your email has been changed successfully.';
            else $this->error[] = 'Something went wrong. Please, try again later.';

        } elseif (!empty($_POST['email'])) $this->error[] = 'You must enter an email adress.';

        if (!empty($_POST['password']) && !empty($_POST['newpassword1']) && !empty($_POST['newpassword2'])) {

            if ($_POST['newpassword1'] == $_POST['newpassword2']) {

                $this->password = sha1($this->db->real_escape_string($_POST['password']));

                if ($this->verify_password()) {

                    $this->password = sha1($this->db->real_escape_string($_POST['newpassword1']));

                    $query  = 'UPDATE users '
                        . 'SET password = "' . $this->password . '" '
                        . 'WHERE user = "' . $username . '"';

                    if ($this->db->query($query)) $this->msg[] = 'Your password has been changed successfully.';
                    else $this->error[] = 'Something went wrong. Please, try again later.';

                } else $this->error[] = 'Wrong password.';

            } else $this->error[] = 'Passwords don\'t match.';

        } elseif (empty($_POST['password']) && (!empty($_POST['newpassword1']) || !empty($_POST['newpassword2']))) {

            $this->error[] = 'Old password field was empty.';

        } elseif (!empty($_POST['password']) && empty($_POST['newpassword1'])) {

            $this->error[] = 'New password field was empty.';

        } elseif (!empty($_POST['password']) && empty($_POST['newpassword2'])) {

            $this->error[] = 'You must enter the new password again.';
        }

        // To avoid resending the form on refreshing
        $_SESSION['msg'] = $this->msg;
        $_SESSION['error'] = $this->error;
        header('Location: ' . $_SERVER['REQUEST_URI']);
        exit();

    }

    // Delete an existing user

    public function delete($user) {
        $query = 'DELETE FROM users WHERE user = "' . $user . '"';
        return ($this->db->query($query));
    }

    // Get info about an user

    public function get_user_info($user) {
        $query = 'SELECT user, password, email FROM users WHERE user = "' . $user . '"';
        $result = $this->db->query($query);
        return $result->fetch_object();
    }

    // Get all the existing users

    public function get_users() {

        $query = 'SELECT user, password, email FROM users';

        return ($this->db->query($query));
    }

    // Print info messages in screen

    public function display_info() {
        foreach ($this->msg as $msg) {
            echo '<p class="msg">' . $msg . '</p>';
        }
    }

    // Print errors in screen

    public function display_errors() {
        foreach ($this->error as $error) {
            echo '<p class="error">' . $error . '</p>';
        }
    }

    // Check if the users db has been created

    public function db_exists() {
        return ($this->db->query('SELECT 1 FROM users'));
    }

    // Check if the users db has any users

    public function empty_db() {
        $query = 'SELECT * FROM users';
        $result = $this->db->query($query);
        return ($result->num_rows === 0);
    }

    // Create a new db to start with

    private function create_db() {

        $query 	= 'CREATE TABLE users ('
            . 'user VARCHAR(75) NOT NULL, '
            . 'password VARCHAR(75) NOT NULL, '
            . 'email VARCHAR(150) NULL, '
            . 'PRIMARY KEY (user) '
            . ') ENGINE=MyISAM COLLATE=utf8_general_ci';

        return ($this->db->query($query));

    }

    // Drop an existing db

    private function drop_db() {

        $query 	= 'DROP TABLE IF EXISTS users ';

    }
}