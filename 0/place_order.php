<?php
require './database.php';

if(isset($_POST['data'])){
    $customer = $_POST['customername'];
    $address = $_POST['address'];
    $finaldate = $_POST['finalDate'];
    $data = $_POST['data'];
    $refno = '';
    $total = $_POST['total'];
    while (true){
        $refno = rand(1000000, 99999999);
        $res = mysqli_query($conn, "SELECT * FROM `orders` WHERE `ref`='$refno'");
        if (mysqli_num_rows($res) == 0){
            break;
        }
    }
    foreach($data as $item){
        $query = "INSERT INTO `orders`(`customer`, `item`, `quantity`, `rate`, `ref`, `status`, `orderdate`, `address`,`comtotal`) VALUES ('$customer', '$item[0]', '$item[1]', '$item[2]', '$refno','PENDING','$finaldate', '$address', '$total')";
        $sql = mysqli_query($conn, $query);
    }

    echo "$refno, $finaldate, $total";

}

if(isset($_POST['type'])){
    $id = $_POST['id'];
    $quantity = $_POST['quantity'];
    $rate = $_POST['rate'];
    $total = $_POST['total'];
    $productname = $_POST['productname'];
    $customername = $_POST['customersname'];
    $address = $_POST['address'];
    $state = $_POST['states'];

    mysqli_query($conn, "UPDATE `orders` SET `customer`='$customername',`item`='$productname',`quantity`='$quantity',`rate`='$rate',`status`='$state',`address`='$address' WHERE `ID`='$id'");
}
?>