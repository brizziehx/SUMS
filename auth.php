<?php

    if(!isset($_SESSION['uid'])) {
        header("Location: login.php");
    }

?>