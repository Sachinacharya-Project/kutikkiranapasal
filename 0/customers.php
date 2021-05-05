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
                    <li class='$class' data-index='$rows[0]'>
                        <p>
                            <strong>Name: </strong>$rows[1]
                        </p>
                        <p>
                            <strong>Amount Paid By: </strong>$rows[2]
                        </p>
                        <p>
                            <strong>Amount Received By: </strong>$rows[3]
                        </p>
                        <p>
                            <strong>Debit/Credit: </strong>$rows[4]
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
                    if($rows[4] < 0){
                        $class = "credit";
                    }
                    $output .= "
                        <li class='$class' data-index='$rows[0]'>
                            <p>
                                <strong>Name: </strong>$rows[1]
                            </p>
                            <p>
                                <strong>Amount Paid By: </strong>$rows[2]
                            </p>
                            <p>
                                <strong>Amount Received By: </strong>$rows[3]
                            </p>
                            <p>
                                <strong>Debit/Credit: </strong>$rows[4]
                            </p>
                        </li>
                    ";
                }
            }elseif($input_value == 'from_coder_debit'){
                while($rows = mysqli_fetch_array($query)){
                    $class = "debit";
                    if($rows[4] > 0){
                        $output .= "
                            <li class='$class' data-index='$rows[0]'>
                                <p>
                                    <strong>Name: </strong>$rows[1]
                                </p>
                                <p>
                                    <strong>Amount Paid By: </strong>$rows[2]
                                </p>
                                <p>
                                    <strong>Amount Received By: </strong>$rows[3]
                                </p>
                                <p>
                                    <strong>Debit/Credit: </strong>$rows[4]
                                </p>
                            </li>
                        ";   
                    }
                }
            }elseif($input_value == "from_coder_credit"){
                while($rows = mysqli_fetch_array($query)){
                    $class = "credit";
                    if($rows[4] < 0){
                        $output .= "
                            <li class='$class' data-index='$rows[0]'>
                                <p>
                                    <strong>Name: </strong>$rows[1]
                                </p>
                                <p>
                                    <strong>Amount Paid By: </strong>$rows[2]
                                </p>
                                <p>
                                    <strong>Amount Received By: </strong>$rows[3]
                                </p>
                                <p>
                                    <strong>Debit/Credit: </strong>$rows[4]
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

?>