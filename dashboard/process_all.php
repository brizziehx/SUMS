<?php
    session_start();
    include('inactivity.php');
    require('../conn/pdo.php');

    if(!isset($_SESSION['supplier'])) {
        header("Location: ../login.php");
    }


    $stmt = $conn->prepare("UPDATE dimensions SET sup_status = :sup_status WHERE year(app_date_time) = year(current_date()) AND sup_status = :sup_status_rec");
    $stmt->bindValue(':sup_status', 'inprogress', PDO::PARAM_STR);
    $stmt->bindValue(':sup_status_rec', 'received', PDO::PARAM_STR);
    $stmt->execute();

    if($stmt->rowCount() > 0) {
        $_SESSION['msg'] = "<script>inProgressAll()</script>";
        // $_SESSION['msg'] = "<script>vt.success('Uniform submitted Successfully!', {duration: 5000, position: 'bottom-right'})</script>";
        header('Location: received.php');
    }

?>