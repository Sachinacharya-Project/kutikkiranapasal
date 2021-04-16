<?php

$host = '';
$username = 'root';
$password = '';
$db = 'kiranaledger';

$conn = mysqli_connect($host, $username, $password, $db) or die('Sorry, Proble with Server! Server Down!');
function writeMsg($alert) {
    echo "<script>alert('$alert')</script>";
}
function update_orders($connection, $myarray){
    $results = '';
    foreach($myarray as $arr){
        $prodname = $arr[0];
        $quantity = $arr[1];
        $query = mysqli_query($connection, "SELECT * FROM `products` WHERE name='$prodname'");
        $lenght = mysqli_num_rows($query);
        $rows = mysqli_fetch_array($query);
        if($lenght > 0){
            $newQuan = intval($rows[2]) + intval($quantity);
            mysqli_query($connection, "UPDATE `products` SET `quantity`='$newQuan' WHERE name='$prodname'");
            $results .= "Product $prodname is Updated by $newQuan Quantity inStock\n";
        }else{
            mysqli_query($connection, "INSERT INTO `products`(`name`, `quantity`) VALUES ('$prodname', '$quantity')");
            $results .= "$prodname Has been added as new Product\n";
        }
    }

    return $results;
}
function loading_options($connection){
    $query = "SELECT `name`,`quantity` FROM `products`";
    $sql = mysqli_query($connection, $query);
    $output = "<option value='null'>Choose Product</option>";
    while($rows = mysqli_fetch_array($sql)){
        $value = strtolower($rows[0]);
        $display = ucwords($rows[0]);
        $output .= "<option value='$value'> $display ($rows[1] Units)</option>";
    }
    return $output;
}
?>