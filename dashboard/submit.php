<?php
    session_start();
    include('inactivity.php');
    require('../conn/pdo.php');

    if(!isset($_SESSION['admin']) && !isset($_SESSION['hr'])) {
        header("Location: ../login.php");
    }


    $stmt = $conn->prepare("UPDATE dimensions SET status = :status, sup_status = :sup_status WHERE year(app_date_time) = year(current_date()) AND status = :approved");
    $stmt->bindValue(':status', 'submitted', PDO::PARAM_STR);
    $stmt->bindValue(':sup_status', 'received', PDO::PARAM_STR);
    $stmt->bindValue(':approved', 'approved');
    $stmt->execute();

    if($stmt->rowCount() > 0) {
        $_SESSION['msg'] = "<script>submitAll()</script>";
        // $_SESSION['msg'] = "<script>vt.success('Uniforms submitted Successfully!', {duration: 5000, position: 'bottom-right'})</script>";
        header('Location: uniform_app.php');
    } else {
        $stmt = $conn->prepare("SELECT * FROM dimensions WHERE status = :status AND year(app_date_time) = year(current_date())");
        $stmt->bindValue(':status', 'pending', PDO::PARAM_STR);
        $stmt->execute();

        if($stmt->rowCount() > 0) {
            $_SESSION['msg'] = "<script>vt.error('Approve uniforms first before submitting!', {duration: 5000, position: 'bottom-right'})</script>";
            header('Location: uniform_app.php');
        } else {
            $_SESSION['msg'] = "<script>vt.info('Uniforms are already submitted!', {duration: 5000, position: 'bottom-right'})</script>";
            header('Location: uniform_app.php');
        }
    }

?>