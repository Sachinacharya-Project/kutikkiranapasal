<?php
include './0/database.php';
$search_data = "4";
$query = mysqli_query($conn, "SELECT * FROM `orders` WHERE `ref` LIKE '%{$search_data}%'");
if (mysqli_num_rows($query) == 0){
    echo "<h1 class='cancle'>Sorry, No Data Found with given Reference Number!</h1>";
}else{
    $output = '';
    while($rows = mysqli_fetch_array($query)){
        $output .= "$rows[0]<br>";
    }

    echo $output;
}
?>