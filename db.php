<?php
    $host = 'localhost';
    $user = 'aaaaaa';
    $pw = 'aaaaaaaa';
    $dbname = 'CCTF';
    $mysqli = mysqli_connect($host, $user, $pw, $dbname);
    
    if(!$mysqli){
        echo "<script>alert('DB connection is fail.');</script>";
    }
?>
