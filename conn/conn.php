<?php

    $conn = new mysqli('localhost','root','','ums');

    if($conn->connect_error) {
        die("Could not connect to MySQL".$conn->connect_error);
    }

?>