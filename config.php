<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "crud";
$connection = new mysqli($servername, $username, $password, $database);
if ($connection){
    echo"You are connected";
}
else{
    echo "COULD NOT CONNECT";
}
session_start();
//LOGIN CHECK
function requireLogin() {
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
}
//ADMIN CHECK
function requireAdmin() {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] != 'admin') {
        header("Location: profile.php");
        exit;
    }
}
?>