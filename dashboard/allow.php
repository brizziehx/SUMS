<?php
    session_start();
    include('inactivity.php');
    require('../conn/pdo.php');

    if(!isset($_SESSION['admin']) && !isset($_SESSION['hr'])) {
        header("Location: ../login.php");
    }

    $stmt = $conn->prepare("UPDATE dimensions SET permission = :allowed, status = :pending WHERE dimID = :dimID AND year(app_date_time) = year(current_date())");
    $stmt->bindValue(':allowed', 1, PDO::PARAM_INT);
    $stmt->bindValue(':pending', 'pending', PDO::PARAM_STR);
    $stmt->bindValue(':dimID', $_REQUEST['id'], PDO::PARAM_INT);
    $stmt->execute();

    if($stmt->rowCount() > 0) {
        $_SESSION['msg'] = "<script>permissionON();</script>";
        // $_SESSION['msg'] = "<script>vt.success('Permission to edit has been Granted!', {duration: 5000, position: 'bottom-right'})</script>";
        header('Location: uniform_app.php');
    } 