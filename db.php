<?php
    $host = 'localhost';
    $user = 'test';
    $pw = 'test';
    $dbname = 'CCTF';
    $mysqli = mysqli_connect($host, $user, $pw, $dbname);
    
    if(!$mysqli){
        echo "<script>alert('DB connection is fail.');</script>";
    }
?>
