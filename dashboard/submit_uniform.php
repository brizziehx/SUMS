<?php
    session_start();
    include('inactivity.php');
    require('../conn/pdo.php');

    if(!isset($_SESSION['admin']) && !isset($_SESSION['hr'])) {
        header("Location: ../login.php");
    }


    $stmt = $conn->prepare("UPDATE dimensions SET status = :status, sup_status = :sup_status WHERE (year(app_date_time) = year(current_date()) AND status = :approved) AND dimID = :dimID");
    $stmt->bindValue(':status', 'submitted', PDO::PARAM_STR);
    $stmt->bindValue(':sup_status', 'received', PDO::PARAM_STR);
    $stmt->bindValue(':approved', 'approved');
    $stmt->bindValue(':dimID', $_REQUEST['id'], PDO::PARAM_INT);
    $stmt->execute();

    if($stmt->rowCount() > 0) {
        $_SESSION['msg'] = "<script>submitted()</script>";
        // $_SESSION['msg'] = "<script>vt.success('Uniform submitted Successfully!', {duration: 5000, position: 'bottom-right'})</script>";
        header('Location: uniform_app.php');
    }

?>