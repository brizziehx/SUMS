<?php

    try {
        $conn = new PDO('mysql:host=localhost;dbname=ums','root','', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    } catch(PDOException $e) {
        echo $e->getMessage();
    }
?>