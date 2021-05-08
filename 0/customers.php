<?php
    require "./database.php";
    if(isset($_POST['input_value'])){
        $input_value = $_POST['input_value'];
        $query = mysqli_query($conn, "SELECT * FROM `customers` WHERE `name` LIKE '%$input_value%'");
        if(mysqli_num_rows($query) > 0){
            $output = "";
            while($rows = mysqli_fetch_array($query)){
                $class = "debit";
                if($rows[4] < 0){
                    $class = "credit";
                }
                $output .= "
                    <li class='$class' data-index='$rows[0]' onclick=customer_preview(this)>
                        <p>
                            <strong>Name: </strong>$rows[1]
                        </p>
                        <p>
                            <strong>Amount Paid By: </strong>$rows[2]/-
                        </p>
                        <p>
                            <strong>Amount Received By: </strong>$rows[3]/-
                        </p>
                        <p>
                            <strong>Debit/Credit: </strong>$rows[4]/-
                        </p>
                    </li>
                ";
            }
            echo $output;
        }
    }
    if(isset($_POST['request'])){
        $input_value = $_POST['request'];
        $query = mysqli_query($conn, "SELECT * FROM `customers`");
        if(mysqli_num_rows($query) > 0){
            $output = '';
            if($input_value == 'from_coder_show_all'){
                while($rows = mysqli_fetch_array($query)){
                    $class = "debit";
                    if($rows[4] > $rows[3]){
                        $class = "credit";
                    }
                    $output .= "
                        <li class='$class' data-index='$rows[0]' onclick=customer_preview(this)>
                            <p>
                                <strong>Name: </strong>$rows[1]
                            </p>
                            <p>
                                <strong>Total Transactions: </strong>$rows[2]/-
                            </p>
                            <p>
                                <strong>Debit: </strong>$rows[3]/-
                            </p>
                            <p>
                                <strong>Credit: </strong>$rows[4]/-
                            </p>
                        </li>
                    ";
                }
            }elseif($input_value == 'from_coder_debit'){
                while($rows = mysqli_fetch_array($query)){
                    $class = "debit";
                    if($rows[4] < $rows[3]){
                        $output .= "
                            <li class='$class' data-index='$rows[0]' onclick=customer_preview(this)>
                                <p>
                                    <strong>Name: </strong>$rows[1]
                                </p>
                                <p>
                                    <strong>Total Transactions: </strong>$rows[2]/-
                                </p>
                                <p>
                                    <strong>Debit: </strong>$rows[3]/-
                                </p>
                                <p>
                                    <strong>Credit: </strong>$rows[4]/-
                                </p>
                            </li>
                        ";   
                    }
                }
            }elseif($input_value == "from_coder_credit"){
                while($rows = mysqli_fetch_array($query)){
                    $class = "credit";
                    if($rows[3] < $rows[4]){
                        $output .= "
                            <li class='$class' data-index='$rows[0]' onclick=customer_preview(this)>
                                <p>
                                    <strong>Name: </strong>$rows[1]
                                </p>
                                <p>
                                    <strong>Total Transactions: </strong>$rows[2]/-
                                </p>
                                <p>
                                    <strong>Credit: </strong>$rows[3]/-
                                </p>
                                <p>
                                    <strong>Debit: </strong>$rows[4]/-
                                </p>
                            </li>
                        ";
                    }
                }
            }
            if($output){
                echo $output;
            }
        }
    }
    if(isset($_POST['get_s'])){
        $index = $_POST['get_s'];
        $getting_bas = mysqli_query($conn, "SELECT * FROM `customers` WHERE `ID`='$index'");
        $row = mysqli_fetch_array($getting_bas);
        $name = $row[1];
        $trans = $row[2];
        $debit = $row[3];
        $credit = $row[4];

        $output = "
            <h1>Sachin Acharya</h1>
            <div class='basic_details'>
                <p><strong>Name</strong>: $name</p>
                <p><strong>Total Transactions</strong>: $trans/-</p>
                <p><strong>Debit</strong>: $debit/-</p>
                <p><strong>Credit</strong>: $credit/-</p>
            </div>
        ";
        /**
         * Collecting from orders
         */
        $orders_q = mysqli_query($conn, "SELECT * FROM `orders` WHERE `customer`='$name'");
        $collective_out = "<div class='products_list'>";
        if(mysqli_num_rows($orders_q) > 0){
            while($rows = mysqli_fetch_array($orders_q)){
                $product = $rows[2];
                $quantity = $rows[3];
                $rate = $rows[4];
                $cost = $rows[10];
                $collective_out .= "
                    <li>
                        <p><strong>Product: </strong>$product</p>
                        <p><strong>Quantity: </strong>$quantity</p>
                        <p><strong>Rate: </strong>$rate/-</p>
                        <p><strong>Cost: </strong>$cost/-</p>
                    </li>
                ";
            }
            $collective_out .= "
                </div>
                <div class='buttons'>
                    <button onclick='close_th()'>Close!</button>
                </div>
            ";
        }
        $output .= $collective_out;
        echo $output;
    }
?>