<?php
    require './database.php';
    // if(isset($_POST['data'])){
    //     $customer = $_POST['customername'];
    //     $address = $_POST['address'];
    //     $finaldate = $_POST['finalDate'];
    //     $data = $_POST['data'];
    //     $refno = '';
    //     $total = $_POST['total'];
    //     $credit = $_POST['credit'];
    //     $debit = $_POST['debit'];
    //     while (true){
    //         $refno = rand(1000000, 99999999);
    //         $res = mysqli_query($conn, "SELECT * FROM `orders` WHERE `ref`='$refno'");
    //         if (mysqli_num_rows($res) == 0){
    //             break;
    //         }
    //     }
    //     foreach($data as $item){
    //         $query = "INSERT INTO `orders`(`customer`, `item`, `quantity`, `rate`, `ref`, `status`, `orderdate`, `address`,`comtotal`, `bepaid`, `bereturned`) VALUES ('$customer', '$item[0]', '$item[1]', '$item[2]', '$refno','PENDING','$finaldate', '$address', '$total', $debit, $credit)";
    //         $sql = mysqli_query($conn, $query);
    //     }
    //     mysqli_query($conn, "INSERT INTO `transactions`(`refno`, `trans_type`) VALUES('$refno', 'orders')");
    //     echo "$refno, $finaldate, $total";

    // }
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
            `orders`(`customer`, `item`, `quantity`, `rate`, `ref`, `status`, `orderdate`, `address`, `comtotal`, `thistotal`, `bepaid`, `bereturned`)
            VALUES (
            '$customer','$data[0]','$data[1]','$data[2]','$refno','PENDING','$date','$customer_address','$total_received_amount','$data[3]', '$bepaid', '$bereturned')");
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
            $amount_paid = $row[2] + $amount_received;
            $dc = $row[4] + $test_amout;
            mysqli_query($conn, "UPDATE INTO `customers` SET `paid`='$amount_paid', `dc`='$dc' WHERE `name`='$customer'");
        }else{
            mysqli_query($conn, "INSERT INTO `customers`(`name`,`paid`,`received`,`dc`) VALUES('$customer', '$amount_received', '0', '$test_amout')");
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
        mysqli_query($conn, "UPDATE `orders` SET `customer`='$customername',`item`='$productname',`quantity`='$quantity',`rate`='$rate',`status`='$state',`address`='$address' WHERE `ID`='$id'");
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