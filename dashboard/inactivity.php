<?php
    // set inactive time of 900 seconds
    $inactivity_time = 15 * 60;

    if(isset($_SESSION['last_timestamp']) && (time() - $_SESSION['last_timestamp']) > $inactivity_time) {
        session_unset();
        session_destroy();
        header("location: ../login.php?status=inactivity");
        exit();
    } else {
        session_regenerate_id(true);
        $_SESSION['last_timestamp'] = time();
    }
?>