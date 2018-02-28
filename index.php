<?php
/**
 * Created by PhpStorm.
 * User: Dell
 * Date: 2/26/2018
 * Time: 6:38 PM
 */


use toandang\create_account\account;

include "vendor/autoload.php";

$db = new mysqli('localhost','root','','test');
if ($db->connect_errno) {
    echo 'Database connection problem: ' . $db->connect_errno;
    exit();
}
$account = new account($db);

//Register account
$rand = rand();
$param = array(
    "username" => "newbie".$rand,
    "password" => $rand,
    "email" => "newbie".$rand."@gmail.com"
);
$result = $account->register($param);


//Update account
//$new_rand = rand();
//$param_update = array(
//    "id" => 55,
//    "username" => "newbie".$new_rand,
//    "email" => "newbie".$new_rand."@gmail.com"
//);
//$result_update = $account->update($param_update);
//var_dump($result_update);


//Delete account
//$result_delete = $account->delete(55);
//var_dump($result_delete);


//Show list users
$list_user = $account->get_users();
foreach ( $list_user as $row ) {
    echo "</br>Id: ".$row['id']." - Username: ".$row['username']." - Email: ".$row['email']."</br>";
}
