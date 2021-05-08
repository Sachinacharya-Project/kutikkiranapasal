<?php
    require './database.php';
    if(isset($_POST['data_list'])){
        $customer = $_POST['customer'];
        $customer_address = $_POST['customeraddress'];
        $amount_received = $_POST['amount_received'];
        $date = $_POST['date'];
        $datalist = $_POST['data_list'];
        $refno = uniqid();
        $total_received_amount = $_POST['total_received_amount'];
        $bereturned = 0;
        $bepaid = 0;
        $test_amout = $total_received_amount - $amount_received;
        if($test_amout > 0){
            $bepaid = $test_amout;
        }else{
            $bereturned = (-1*$test_amout);
        }
        foreach($datalist as $data){
            mysqli_query($conn, "INSERT INTO 
            `orders`(`customer`, `item`, `quantity`, `rate`, `ref`, `status`, `orderdate`, `address`, `comtotal`, `thistotal`, `bepaid`, `bereturned`, `received_amount`)
            VALUES (
            '$customer','$data[0]','$data[1]','$data[2]','$refno','PENDING','$date','$customer_address','$total_received_amount','$data[3]', '$bepaid', '$bereturned', '$amount_received')");
            $new_query = mysqli_query($conn, "SELECT `quantity` FROM `products` WHERE `name`='$data[0]'");
            if(mysqli_num_rows($new_query) > 0){
                $row = mysqli_fetch_array($new_query);
                $ne = $row[0] - $data[1];
                mysqli_query($conn, "UPDATE `products` SET `quantity`='$ne' WHERE `name`='$data[0]'");
            }
        }
        mysqli_query($conn, "INSERT INTO `transactions`(`refno`, `trans_type`) VALUES ('$refno','orders')");
        $update_customer = mysqli_query($conn, "SELECT * FROM `customers` WHERE `name`='$customer'");
        if(mysqli_num_rows($update_customer) > 0){
            $row = mysqli_fetch_array($update_customer);
            $paid_sofar = $row[2] + $amount_received;
            $incoming = $row[3] + $bepaid;
            $outgoing = $row[4] + $bereturned;
            mysqli_query($conn, "UPDATE `customers` SET `paid`='$paid_sofar', `income`='$incoming', `outgoing`='$outgoing'");
        }else{
            mysqli_query($conn, "INSERT INTO `customers`(`name`, `paid`, `income`, `outgoing`) VALUES('$customer', '$amount_received', '$bepaid', '$bereturned')");
        }
        header("Content-Type: application/json");
        echo json_encode([$refno, $test_amout, $date, $total_received_amount, $amount_received]);
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
        mysqli_query($conn, "UPDATE `orders` SET `item`='$productname',`quantity`='$quantity',`rate`='$rate',`status`='$state' WHERE `ID`='$id'");
    }
    if(isset($_POST['productlist'])){
        echo loading_options($conn);
    }
    if(isset($_POST['get_back_span'])){
        $value = $_POST['get_back_span'];
        $query = mysqli_query($conn, "SELECT `name` FROM `customers` WHERE `name` LIKE '%$value%'");
        if(mysqli_num_rows($query) > 0){
            $output = "";
            while($rows = mysqli_fetch_array($query)){
                $output .= "<span class='target_free' onclick='update_customer_name(\"$rows[0]\")'>$rows[0]</span>";
            }
            echo $output;
        }
    }
?>