<?php
include '../0/database.php';
if(isset($_POST['refno'])){
    $refno = $_POST['refno'];
    $sql = mysqli_query($conn, "SELECT * FROM `orders` WHERE `ref`='$refno'");
    while($rows = mysqli_fetch_array($sql)){
        echo "$rows[1];$rows[2];$rows[3];$rows[4];$rows[5];$rows[6];$rows[7],";
    }
}

if(isset($_POST['get'])){
    $query = mysqli_query($conn, "SELECT * FROM `orders` ORDER BY `orderdate` DESC");
    $back = '';
    while ($rows = mysqli_fetch_array($query)){
        $refno_of_this = $rows[5];
        if($back != $refno_of_this){
            $listed_query = mysqli_query($conn, "SELECT `customer`,`orderdate`,`comtotal` FROM `orders` WHERE ref='$refno_of_this'");
            $rw = mysqli_fetch_array($listed_query);
            echo "$refno_of_this;$rw[0];$rw[1];$rw[2],";
            $back = $refno_of_this;
        }
    }
}
?>