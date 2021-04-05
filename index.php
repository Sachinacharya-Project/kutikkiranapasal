<?php
session_start();
if(isset($_SESSION['user'])){
    header('location: /0/dashboard.php');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <script src="https://kit.fontawesome.com/612f542d54.js" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Allan&family=Anton&family=Bebas+Neue&family=Courgette&family=Imbue&family=Kaushan+Script&family=Lobster&family=Nova+Square&family=Oswald:wght@300;400&family=PT+Sans+Narrow&family=Pathway+Gothic+One&family=Poppins&family=Potta+One&family=Righteous&family=Roboto:wght@300;400&family=Squada+One&family=Teko:wght@300;400&family=Trade+Winds&family=Yanone+Kaffeesatz:wght@400;500&family=Yellowtail&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/login.css">
    <title>Account | SignIn/SignUp Here</title>
</head>
<body>
    <form action="/0/account/login.php" class="signin" id="signin" method="POST">
        <div class="header">
            <h1>Login Here</h1>
        </div>
        <div class="username">
            <input type="text" name="user" id="user" class="user" autocomplete="off" required>
            <label for="user">Username</label>
            <p></p>
        </div>
        <div class="password">
            <input type="password" name="pass" id="pass" class="pass" autocomplete="off" required>
            <label for="pass">Password</label>
            <p></p>
        </div>
        <div class="extraThings">
            <p><a href="/0/account/forget.php">Forgot Password?</a></p>
            <p><a href="/0/account/createnew.php">Don't have one?</a></p>
        </div>
        <div class="submit">
            <input type="submit" value="Login" class="send" id="send" name="send">
        </div>
    </form>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
</body>
</html>