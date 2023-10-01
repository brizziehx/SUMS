<?php
    session_start();
    require_once('conn/conn.php');
    if(isset($_SESSION['uid'])) {
        header("location: dashboard/index.php");
    } else {
        header("location: login.php");
    }
?>
