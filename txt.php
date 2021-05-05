<?php
    // $to = "9865252424@ntc.net.np";
    $to = "9865252424@ntc.net.np";
    $from = "acharyaraj71@gmail.com";
    $message = "This is new message";
    $headers = "From: $from";
    mail($to, "Subject", $message, $headers);
?>