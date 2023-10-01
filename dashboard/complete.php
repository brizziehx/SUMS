<?php
    session_start();
    include('inactivity.php');
    require('../conn/pdo.php');

    if(!isset($_SESSION['supplier'])) {
        header("Location: ../login.php");
    }


    $stmt = $conn->prepare("UPDATE dimensions SET sup_status = :sup_status WHERE year(app_date_time) = year(current_date()) AND dimID = :dimID");
    $stmt->bindValue(':sup_status', 'complete', PDO::PARAM_STR);
    $stmt->bindValue(':dimID', $_REQUEST['id'], PDO::PARAM_INT);
    $stmt->execute();

    if($stmt->rowCount() > 0) {
        $_SESSION['msg'] = "<script>complete()</script>";
        // $_SESSION['msg'] = "<script>vt.success('Uniform submitted Successfully!', {duration: 5000, position: 'bottom-right'})</script>";
        header('Location: received.php');
    }

?>