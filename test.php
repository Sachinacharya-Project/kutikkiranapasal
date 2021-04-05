<?php
include './0/database.php';

$sorted = "";
$query = mysqli_query($conn, "SELECT * FROM `orders` ORDER BY `orderdate` DESC");
$back = '';
while ($rows = mysqli_fetch_array($query)){
    $refno_of_this = $rows[5];
    if($back != $refno_of_this){
        $listed_query = mysqli_query($conn, "SELECT `customer`,`orderdate`,`comtotal` FROM `orders` WHERE ref='$refno_of_this'");
        $rw = mysqli_fetch_array($listed_query);
        echo "$rw[0];$rw[1];$rw[2],";
        $back = $refno_of_this;
    }
}
?>