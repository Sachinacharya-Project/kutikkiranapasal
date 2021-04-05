<?php
    session_start();
    require '../database.php';
    // if (!isset($_SESSION['user'])){
    //     header('location: /');
    // }
    if(isset($_POST['send'])){
        $username = $_POST['user'];
        $password = md5($_POST['pass']);
        $results = mysqli_query($conn, "SELECT * FROM `userlist` WHERE username='$username' AND password='$password'");
        $rows = mysqli_num_rows($results);
        if ($rows > 0){
            $_SESSION['user'] = $username;
            header('location: /0/dashboard.php');
        }else{
            writeMsg('Sorry, No User found with given Information!\nContact Administrator for new Account');
            header('location: /');
        }
    }
?>