<?php

    if(!isset($_SESSION['uid'])) {
        header("Location: login.php");
    }
    
    // if(isset($_SESSION['uid'])){
    //     header("Location: dashboard/index.php");
    // }

?>