<?php
    /**
     * Bepaid == Amount to be received by me
     */
    include './database.php';
    if(isset($_POST['typed'])){
        $typed = $_POST['typed'];
        $query = '';
        // from ref on ispaid isreceived total
        if($typed == 'all'){
            $query = "SELECT `name`, `ref`, `orderdate`, `ispaid`, `isdelivered`, `total`,`bepaid`, `bereturned` FROM `purchases` ORDER BY `orderdate` DESC";
        }elseif($typed == 'incompleted'){
            $query = "SELECT `name`, `ref`, `orderdate`, `ispaid`, `isdelivered`, `total`,`bepaid`, `bereturned` FROM `purchases` WHERE iscompleted='no' ORDER BY `orderdate` DESC";
        }elseif($typed == 'completed'){
            $query = "SELECT `name`, `ref`, `orderdate`, `ispaid`, `isdelivered`, `total`,`bepaid`, `bereturned` FROM `purchases` WHERE iscompleted='yes' ORDER BY `orderdate` DESC";
        }elseif($typed == 'deu'){
            $query = "SELECT `name`, `ref`, `orderdate`, `ispaid`, `isdelivered`, `total`,`bepaid`, `bereturned` FROM `purchases` WHERE ispaid='no' ORDER BY `orderdate` DESC";
        }else{
            $query = "SELECT `name`, `ref`, `orderdate`, `ispaid`, `isdelivered`, `total`,`bepaid`, `bereturned` FROM `purchases` WHERE isdelivered='no' ORDER BY `orderdate` DESC";
        }
        $sql = mysqli_query($conn, $query);
        $output = '';
        if(mysqli_num_rows($sql) > 0){
            $newref = "";
            while($rows = mysqli_fetch_array($sql)){
                if($newref != $rows[1])
                {
                    $class = "normal";
                    if($rows[6] != 0){
                        $class = "debitlight";
                    }elseif($rows[7] != 0){
                        $class = "creditlight";
                    }
                    $output .= "
                    <li class='$class' onclick='show(`$rows[1]`)'>
                    <p>Ref. No: $rows[1]</p>
                    <p>From: $rows[0]</p>
                    <p>On: $rows[2]</p>
                    <p>isPaid: $rows[3]</p>
                    <p>isReceived: $rows[4]</p>
                    <p>Actual Cost: $rows[5]/-</p>
                    </li>
                    ";
                    $newref = $rows[1];
                }
            }
            echo $output;
        }else{
            echo "Sorry! No data Found for given query i.e $typed";
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
        if($debit == 0){
            $standard_amount = $total - $credit;
        }
        $class = "normal";
        if($debit != 0){
            $class = "debit";
            $datastring = "$debit (Debit)";
        }elseif($credit != 0){
            $class = "credit";
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
            <p class='dbcd $class'><span>Debit/Credit: </span>$datastring</p>
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
            <button onclick=nadanada('data_lockdown')>Okay!</button>
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
        $class = "debitlight";
        if($for_basics[17] == 0){
            $class = "creditlight";
        }
        $dbdc = $for_basics[17] + $for_basics[18];
        if($for_basics[17] == 0 && $for_basics[18] == 0){
            $class = "normal";
            $dbdc = "No Debit and Credit";
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
        <p><span>Amount Paid: </span>$for_basics[19]/-</p>
        <p><span>Total Cost: </span>$for_basics[13]/-</p>
        <p class='$class'><span>Debit/credit: </span>$dbdc/-</p>
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
            <button onclick=edit_purchases_item('$refno')>Edit</button>
            <button onclick=nada('data_lockdown')>Okay!</button>
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
    if(isset($_POST['update_purchase'])){
        $refno = $_POST['update_purchase'];
        $query = mysqli_query($conn, "SELECT `name`, `orderdate`, `ispaid`, `isdelivered`,`paid_amount`, `bepaid`, `bereturned` FROM `purchases` WHERE `ref`='$refno'");
        $rows = mysqli_fetch_array($query);
        if($rows){
            $name = $rows[0];
            $orderdate = $rows[1];
            $ispaid = $rows[2];
            $isdelivered = $rows[3];
            $paid = $rows[4];
            $bepaid = $rows[5];
            $bereturned = $rows[6];
            $ispaid_opt = "
                <option value='yes'>Did you paid? (yes)</option>
                <option value='yes'>Yes</option>
                <option value='no'>No</option>
            ";
            if($ispaid == 'no'){
                $ispaid_opt = "
                    <option value='no'>Did you paid? (No)</option>
                    <option value='no'>No</option>
                    <option value='yes'>Yes</option>
                ";
            }
            $isdelivered_opt = "
                <option value='yes'>Have you got delivered?(yes)</option>
                <option value='yes'>Yes</option>
                <option value='no'>No</option>
            ";
            if($isdelivered == 'no'){
                $isdelivered_opt = "
                    <option value='no'>Have you got delivered?(no)</option>
                    <option value='no'>No</option>
                    <option value='yes'>Yes</option>
                ";
            }
            $category = "";
            if($bepaid != 0){
                $category = "
                <div class='received'>
                    <label for='received_amount'>(New) Received Amount</label>
                    <input type='text' class='received_amount' name='received_amount' id='received_amount' placeholder='Amount Paid' value='0' autocomplete='off' onkeyup=toreceived_amount(this)>
                    <p class='last_paid'>
                        <p><strong>+Amount to be received: </strong><span id='beact_tot'>$bepaid</span>/-</p>
                        <p><strong>=Total Received will be: </strong><span class='here_is_new' id='behere_is_new' data-tots='$bepaid'>$bepaid</span>/-</p>
                    </p>
                </div>
                ";
            }elseif ($bereturned != 0){
                $category = "
                    <div class='amounts'>
                        <label for='amount_paid'>(New) Amount Paid</label>
                        <input type='text' class='amount_paid' name='amount_paid' id='amount_paid' placeholder='Amount Paid' value='0' autocomplete='off' onkeyup=update_price_tag(this)>
                        <p class='last_paid'>
                            <p><strong>Amount to be paid: </strong><span>$bereturned</span>/-</p>
                            <p><strong>Paid so far: </strong><span id='act_tot'>$paid</span>/-</p>
                            <p><strong>=Total Paid will be: </strong><span class='here_is_new' id='here_is_new' data-tots='$paid'>$paid</span>/-</p>
                        </p>
                    </div>
                ";
            }
            $output = "
            <h1>Editing Purchases</h1>
            <div class='details'>
            <p>
                <strong>Ref. No.: </strong>
                $refno
            </p>
            <p>
                <strong>Name: </strong>
                $name
            </p>
            <p>
                <strong>Order On</strong>
                $orderdate
            </p>
            </div>
            <div class='editables'>
                <select class='ispaid'>
                    $ispaid_opt
                </select>
                <select class='isdelivered'>
                    $isdelivered_opt
                </select>
                $category
            </div>
                <div class='submits'>
                <button onclick=updates_purchase_with_new('$refno')>Update Changes</button>
                <button onclick=closing_windows('iamediting_purchases')>Close</button>
            </div>
            ";

            echo $output;
        }
    }
    if(isset($_POST['new_datas'])){
        $data_array = $_POST['new_datas'];
        $refno = $data_array[0];
        $ispaid = $data_array[1];
        $isdelivered = $data_array[2];
        $total_paid = $data_array[3];
        $received_amount = $data_array[4];
        $first_query = mysqli_query($conn, "SELECT `total`,`bepaid`, `bereturned`,`paid_amount` FROM `purchases` WHERE `ref`='$refno'");
        $rows = mysqli_fetch_array($first_query);
        if($rows){
            $actual_cost = $rows[0];
            $bepaid = $rows[1];
            $bereturned = $rows[2];
            $before_paid = $rows[4];
            $qry = "UPDATE `purchases` SET `ispaid`='$ispaid',`isdelivered`='$isdelivered',`paid_amount`='$total_paid', `bepaid`='$received_amount', `bereturned`='$bereturned' WHERE `ref`='$refno'";
            if($total_paid == 0 || $total_paid == '0'){
                $qry = "UPDATE `purchases` SET `ispaid`='$ispaid',`isdelivered`='$isdelivered',`bepaid`='$received_amount', `bereturned`='$bereturned' WHERE `ref`='$refno'";
            }elseif($received_amount == 0 || $received_amount == '0'){
                // paid - total
                $thisbereturned = $total_paid -$actual_cost;
                if($total_paid - $actual_cost < 0){
                    $thisbereturned = $actual_cost - $total_paid;
                }
                $qry = "UPDATE `purchases` SET `ispaid`='$ispaid',`isdelivered`='$isdelivered',`paid_amount`='$total_paid', `bepaid`='$received_amount', `bereturned`='$thisbereturned' WHERE `ref`='$refno'";
            }
            mysqli_query($conn, $qry);
        }
    }
?>