<?php
include '../0/database.php';
if(isset($_POST['refno'])){
    $refno = $_POST['refno'];
    $sql = mysqli_query($conn, "SELECT * FROM `orders` WHERE `ref`='$refno'");
    while($rows = mysqli_fetch_array($sql)){
        echo "$rows[1];$rows[2];$rows[3];$rows[4];$rows[5];$rows[6];$rows[7];$rows[8],";
    }
}
if(isset($_POST['get'])){
    $posted = $_POST['get'];
    if($posted == 'get'){
        $query = mysqli_query($conn, "SELECT * FROM `orders` ORDER BY `orderdate` DESC, `status` DESC");
        $back = '';
        $output = '';
        while ($rows = mysqli_fetch_array($query)){
            $refno_of_this = $rows[5];
            if($back != $refno_of_this){
                $listed_query = mysqli_query($conn, "SELECT `customer`,`orderdate`,`comtotal` FROM `orders` WHERE ref='$refno_of_this'");
                $rw = mysqli_fetch_array($listed_query);
                $second = mysqli_query($conn, "SELECT `status` FROM `orders` WHERE `ref`='$refno_of_this'");
                $controller = 0;
                $counter = 0;
                while($secrows = mysqli_fetch_array($second)){
                    if($secrows[0] == 'COMPLETE'){
                        $controller +=1;
                    }
                    $counter+=1;
                }
                if($controller == $counter){
                    $output .= "
                    <li class='com' onclick=viewMineOrders('$refno_of_this')>
                        <p><strong>ID: $refno_of_this </strong>(Completed)</p>
                        <p><strong>Name: $rw[0]</strong></p>
                        <p>Order On: $rw[1]</p>
                        <p>Total: RS $rw[2]/-</p>
                    </li>
                ";
                }elseif($controller == 0){
                    $output .= "
                    <li class='pen' onclick=viewMineOrders('$refno_of_this')>
                        <p><strong>ID: $refno_of_this </strong>(Pending)</p>
                        <p><strong>Name: $rw[0]</strong></p>
                        <p>Order On: $rw[1]</p>
                        <p>Total: RS $rw[2]/-</p>
                    </li>
                ";
                }else{
                    $output .= "
                    <li class='semi' onclick=viewMineOrders('$refno_of_this')>
                        <p><strong>ID: $refno_of_this </strong>(Some InCompleted)</p>
                        <p><strong>Name: $rw[0]</strong></p>
                        <p>Order On: $rw[1]</p>
                        <p>Total: RS $rw[2]/-</p>
                    </li>
                ";
                }
     
                // echo "$refno_of_this;$rw[0];$rw[1];$rw[2],";
                $back = $refno_of_this;
            }
        }
        echo $output;
    }else{
        $query = mysqli_query($conn, "SELECT * FROM `orders` WHERE `ref` LIKE '%$posted%' or `customer` LIKE '%$posted%' ORDER BY `status` DESC");
        $back = '';
        $output = '';
        while ($rows = mysqli_fetch_array($query)){
            $refno_of_this = $rows[5];
            if($back != $refno_of_this){
                $listed_query = mysqli_query($conn, "SELECT `customer`,`orderdate`,`comtotal` FROM `orders` WHERE ref='$refno_of_this'");
                $rw = mysqli_fetch_array($listed_query);
                $second = mysqli_query($conn, "SELECT `status` FROM `orders` WHERE `ref`='$refno_of_this'");
                $controller = 0;
                $counter = 0;
                while($secrows = mysqli_fetch_array($second)){
                    if($secrows[0] == 'COMPLETE'){
                        $controller +=1;
                    }
                    $counter+=1;
                }
                if($controller == $counter){
                    $output .= "
                    <li class='com' onclick=viewMineOrders('$refno_of_this')>
                        <p><strong>ID: $refno_of_this </strong>(Completed)</p>
                        <p><strong>Name: $rw[0]</strong></p>
                        <p>Order On: $rw[1]</p>
                        <p>Total: RS $rw[2]/-</p>
                    </li>
                ";
                }elseif($controller == 0){
                    $output .= "
                    <li class='pen' onclick=viewMineOrders('$refno_of_this')>
                        <p><strong>ID: $refno_of_this </strong>(Pending)</p>
                        <p><strong>Name: $rw[0]</strong></p>
                        <p>Order On: $rw[1]</p>
                        <p>Total: RS $rw[2]/-</p>
                    </li>
                ";
                }else{
                    $output .= "
                    <li class='semi' onclick=viewMineOrders('$refno_of_this')>
                        <p><strong>ID: $refno_of_this </strong>(Some InCompleted)</p>
                        <p><strong>Name: $rw[0]</strong></p>
                        <p>Order On: $rw[1]</p>
                        <p>Total: RS $rw[2]/-</p>
                    </li>
                ";
                }
     
                // echo "$refno_of_this;$rw[0];$rw[1];$rw[2],";
                $back = $refno_of_this;
            }
        }
        echo $output;
    }
}
if(isset($_POST['ask'])){
    $refno = $_POST['ref'];
    $index = $_POST['id'];
    $query = mysqli_query($conn, "SELECT * FROM `orders` WHERE `ID`='$index' AND `ref`='$refno'");
    if(mysqli_num_rows($query) == 0){
        echo "<h1 style='color: red;'>MayBe Order Has been cancled so cannot be found!</h1>";
    }else{
        $rows = mysqli_fetch_array($query);
        $total = intval($rows[3]) * intval($rows[4]);
        $output = "
        <div class='details' id='details'>
        <p>Name: <span>$rows[1]</span></p>
        <p>REF. No.: <span>$rows[5]</span></p>
        <p>Order Date: <span>$rows[7]</span></p>
        <p>Address: <span>$rows[8]</span></p>
        </div>
        <div class='itemlist'>
        <p>$rows[2] &times; $rows[3] @ $rows[4]</p>
        <p class='complete'>Total: $total/-</p>
        </div>";

        echo $output;
    }
}
if(isset($_POST['update'])){
    $refno = $_POST['bill'];
    $query = mysqli_query($conn, "SELECT * FROM `orders` WHERE `ref`='$refno'");
    $row1 = mysqli_fetch_array(mysqli_query($conn, "SELECT `customer`, `address` FROM `orders` WHERE `ref`='$refno'"));
    $output = "<h1>Editing Orders</h1>
    <div class='details'>
    <p class='nameCos'>Customers Name: <input type='text' name='consname' id='consname' class='cosname' autocomplete='off' required='required' value='$row1[0]'></p>
    <p class='addrCos'>Address: <input type='text' name='cosaddr' class='cosaddr' id='cosaddr' autocomplete='off' required value='$row1[1]'></p>
    </div>
    <div class='items-editing'>
    ";
    while($rows = mysqli_fetch_array($query)){
        $output .= "
        <div class='items' data-id='$rows[0]'>
        <p>
        Item: <input type='text' class='item' autocomplete='off' required='required' value='$rows[2]'>
        </p>
        <p>
        Quantity: <input type='text' class='quantity' autocomplete='off' required='required' value='$rows[3]'>
        </p>
        <p>
        Rate: <input type=text class='rate' autocomplete='off' required='required' value='$rows[4]'>
        </p>
        <p>
        ";
        if($rows[6] == 'PENDING'){
            $output .= "
            Status: <select class='status'>
            <option value='PENDING'>Pending</option>
            <option value='COMPLETE'>Completed</option>
            </select>
            </p>    
            </div>
            <br>
            ";
        }else{
            $output .= "
            Status: <select class='status'>
            <option value='COMPLETE'>Completed</option>
            <option value='PENDING'>Pending</option>
            </select>
            </p>    
            </div>
            <br>
            ";
        }
   
    }
    $output .= "
    </div>
    <div class='sending-data'>
    <input type=submit value='Update' class='send' id='send' onclick='updateContents()'>
    </div>
    ";

    echo $output;
}
?>