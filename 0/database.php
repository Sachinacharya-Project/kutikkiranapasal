<?php

$host = '';
$username = 'root';
$password = '';
$db = 'kiranaledger';

$conn = mysqli_connect($host, $username, $password, $db) or die('Sorry, Proble with Server! Server Down!');

function writeMsg($alert) {
    echo "<script>alert('$alert')</script>";
}

?>