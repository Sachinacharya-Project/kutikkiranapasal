<?php
include './database.php';
if(isset($_POST['search_data'])){
    $search_data = $_POST['search_data'];
    $query = mysqli_query($conn, "SELECT * FROM `orders` WHERE `ref` LIKE '%$search_data%' or `customer` LIKE '%$search_data%'");
    if (mysqli_num_rows($query) == 0){
        echo "<h1 class='cancel' style='color: red;'>Sorry, No Data Found with given Reference Number!</h1>";
    }else{
        $output = '';
        while($rows = mysqli_fetch_array($query)){
            $total = intval($rows[3]) * intval($rows[4]);
            $output .= "
            <li onclick=viewMineOrders('${maindata[0]}')>
                <p>Name: </p>
                <p>Order On: ${maindata[2]}</p>
                <p>Total: RS ${maindata[3]}/-</p>
            </li>
            ";

        }

        echo $output;

    }
}

?>