<?php
    include './database.php';
    if(isset($_POST['typed'])){
        $typed = $_POST['typed'];
        $query = '';
        // from ref on ispaid isreceived total
        if($typed == 'all'){
            $query = "SELECT `name`, `ref`, `orderdate`, `ispaid`, `isdelivered`, `total` FROM `purchases` ORDER BY `orderdate` DESC";
        }elseif($typed == 'incompleted'){
            $query = "SELECT `name`, `ref`, `orderdate`, `ispaid`, `isdelivered`, `total` FROM `purchases` WHERE iscompleted='no' ORDER BY `orderdate` DESC";
        }elseif($typed == 'completed'){
            $query = "SELECT `name`, `ref`, `orderdate`, `ispaid`, `isdelivered`, `total` FROM `purchases` WHERE iscompleted='yes' ORDER BY `orderdate` DESC";
        }elseif($typed == 'deu'){
            $query = "SELECT `name`, `ref`, `orderdate`, `ispaid`, `isdelivered`, `total` FROM `purchases` WHERE ispaid='no' ORDER BY `orderdate` DESC";
        }else{
            $query = "SELECT `name`, `ref`, `orderdate`, `ispaid`, `isdelivered`, `total` FROM `purchases` WHERE isdelivered='no' ORDER BY `orderdate` DESC";
        }
        $sql = mysqli_query($conn, $query);
        $output = '';
        $rows = mysqli_fetch_array($sql);
        $output .= "
        <li onclick='show(`$rows[1]`)'>
        <p>Ref. No: $rows[1]</p>
        <p>From: $rows[0]</p>
        <p>On: $rows[2]</p>
        <p>isPaid: $rows[3]</p>
        <p>isReceived: $rows[4]</p>
        <p>Total: $rows[5]</p>
        </li>
        ";
        if($output == ''){
            echo "Sorry! No data Found for given query i.e $typed";
        }else{
            echo $output;
        }
    }
    if(isset($_POST['shopname'])){
        $shopname = $_POST['shopname'];
        $shopaddress = $_POST['shopaddress'];
        $ispaid = $_POST['ispaid'];
        $isdelivered = $_POST['isdelivered'];
        $payment_type = $_POST['payment_type'];
        $account = $_POST['account'];
        $total = $_POST['total'];
        $datas = $_POST['constdata'];
        $refno = uniqid();
        $orderdate = $_POST['finalDate'];
        $debit = $_POST['debit'];
        $credit = $_POST['credit'];
        $datastring = 'No Debit or Credit';
        // Total Amount received
        $standard_amount = $total + $debit + $credit;
        if($debit != 0){
            $datastring = "$debit (Debit)";
        }elseif($credit != 0){
            $datastring = "$credit (Credit)";
        }
        $isspaid = '';
        $issdelivered = '';
        $dist = '';
        if($ispaid == 'yes'){
            $isspaid = 'Paid';
        }else{
            $isspaid = 'Not-Paid';
        }
        if($isdelivered == 'yes'){
            $issdelivered = 'Delivered';
        }else{
            $issdelivered = 'Not-Delivered';
        }
        if($payment_type == 'Bank Transfer'){
            $dist = 'Account Number';
        }else{
            $dist = 'Specification';
        }
        $output = "
        <h1>Results</h1>
        <div class='main_details'>
            <p><span>Reference Number: $refno</span></p>
            <p><span>Name: </span>$shopname</p>
            <p><span>Address: </span>$shopaddress</p>
            <p><span>Date: </span>$orderdate</p>
            <p><span>Payment Status: </span> $isspaid</p>
            <p><span>Delivery Status: </span>$issdelivered</p>
            <p><span>Payment Type: </span>$payment_type</p>
            <p><span>$dist</span>: $account</p>
            <p><span>Paid Amount: </span>$standard_amount</p>
            <p><span>Total Cost: </span>$total</p>
            <p><span>Debit/Credit: </span>$datastring</p>
        </div>
        <br>
        <div class='interm_items'>";
        foreach($datas as $data){
            $icon = 'no';
            if($ispaid == 'yes' && $isdelivered == 'yes'){
                $icon = 'yes';
            }else{
                $icon = 'no';
            }
            $productname = $data[0];
            $unit = $data[1];
            $rate = $data[2];
            $quantity = $data[3];
            $thistotal = $data[4];
            $arra = [[$productname, $quantity]];
            update_orders($conn, $arra);
            $query = "INSERT INTO `purchases` (`ID`, `ref`, `name`, `address`, `product`, `unit`, `rate`, `quantity`, `orderdate`, `ispaid`, `isdelivered`, `payment_by`, `selftotal`, `total`, `iscompleted`, `extra`, `bepaid`, `bereturned`, `paid_amount`) VALUES (NULL, '$refno', '$shopname', '$shopaddress', '$productname', '$unit', '$rate', '$quantity', '$orderdate', '$ispaid', '$isdelivered', '$payment_type', '$thistotal', '$total', '$icon', '$account', $debit, $credit, '$standard_amount')";
            $sql = mysqli_query($conn, $query);
            $output .= "
            <li>
                <p><span>Product Name: </span>$productname</p>
                <p><span>Unit: </span>$unit</p>
                <p><span>Quantity): </span>$quantity/-</p>
                <p><span>Rate(Rs): </span>$rate/-</p>
                <p><span>Cost(Rs): </span>$thistotal/-</p>
            </li>
            ";
        }
        $output .= "
        </div>
        <div class='closing_this'>
            <button onclick=closing_windows('data_lockdown')>Okay!</button>
        </div>
        ";
        mysqli_query($conn, "INSERT INTO `transactions`(`refno`, `trans_type`) VALUES('$refno', 'purchases')");
        echo $output;
    }
    if(isset($_POST['show'])){
        $refno = $_POST['show'];
        $query = "SELECT * FROM `purchases` WHERE `ref`='$refno'";
        $results = mysqli_query($conn, $query);
        $for_basics = mysqli_fetch_array($results);
        if($for_basics[9] == 'yes'){
            $ispaided = 'Paid';
        }else{
            $ispaided = 'Not-Paid';
        }
        if($for_basics[10] == 'yes'){
            $isdelivered = 'Delivered';
        }else{
            $isdelivered = 'Not-Delivered';
        }
        if($for_basics[11] == 'Bank Transfer'){
            $dist = 'Account Number';
        }else{
            $dist = 'Specification';
        }
        $output = "
        <h1>Results</h1>
        <div class='main_details'>
        <p><span>Reference Number: $refno</span></p>
        <p><span>Name: </span>$for_basics[2]</p>
        <p><span>Address: </span>$for_basics[3]</p>
        <p><span>Date: </span>$for_basics[8]</p>
        <p><span>Payment Status: </span> $ispaided</p>
        <p><span>Delivery Status: </span>$isdelivered</p>
        <p><span>Payment Type: </span>$for_basics[11]</p>
        <p><span>$dist: </span>$for_basics[15]</p>
        <p><span>Total: </span>$for_basics[13]/-</p>
        </div>
        <br>
        <div class='interm_items'>
        ";
        $results = mysqli_query($conn, $query);
        while($rows = mysqli_fetch_array($results)){
            $output .= "
            <li>
                <p><span>Product Name: </span>$rows[4]</p>
                <p><span>Unit: </span>$rows[5]</p>
                <p><span>Quantity): </span>$rows[7]/-</p>
                <p><span>Rate(Rs): </span>$rows[6]/-</p>
                <p><span>Cost(Rs): </span>$rows[12]/-</p>
            </li>
            ";
            // print_r($rows);
        }
        $output .= "
        </div>
        <div class='closing_this'>
            <button onclick=closing_windows('data_lockdown')>Okay!</button>
        </div>
        ";

        echo $output;
    }
    if(isset($_POST['type'])){
        $myarray = $_POST['myarray'];
        echo update_orders($conn, $myarray);
    }
    if(isset($_POST['showprod'])){
        $query = mysqli_query($conn, "SELECT * FROM `products` ORDER BY `quantity` ASC");
        $output = '';
        while($rows = mysqli_fetch_array($query)){
            $let = 'none';
            $tick = 'Available';
            if($rows[2] <= 0){
                $let = 'out';
                $tick = 'Out of Stock';
            }elseif($rows[2] <= 10 && $rows[2] >= 1){
                $let = 'almost';
                $tick = 'Low';
            }
            $output .="
            <li class='$let'>
            <p><span>Product: $rows[1]</span></p>
            <p><span>InStock: $rows[2]</span></p>
            <p><span>Availability: </span><strong>$tick</strong></p>
            </li>
            ";
        }
        echo $output;
    }
?>