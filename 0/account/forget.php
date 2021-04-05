<?php
echo md5('sa');
session_start();
require '../database.php';
$username = $_SESSION['user'];
$array = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM `userlist` WHERE username='$username'"));
print_r($array);
echo $array[1];
echo $array['name'];
?>