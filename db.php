<?php
    $host = 'localhost';
    $user = 'universe';
    $pw = 'kk123456789';
    $dbname = 'CCTF';
    $mysqli = mysqli_connect($host, $user, $pw, $dbname);
    
    if(!$mysqli){
        echo "<script>alert('DB connection is fail.');</script>";
    }
?>