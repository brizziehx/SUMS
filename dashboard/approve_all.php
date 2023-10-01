<?php
    session_start();
    include('inactivity.php');
    require('../conn/pdo.php');

    if(!isset($_SESSION['admin']) && !isset($_SESSION['hr'])) {
        header("Location: ../login.php");
    }


    $stmt = $conn->prepare("UPDATE dimensions SET status = :approved WHERE (status = :pending AND permission = :off) AND year(app_date_time) = year(current_date())");
    $stmt->bindValue(':approved', 'approved', PDO::PARAM_STR);
    $stmt->bindValue(':pending', 'pending', PDO::PARAM_STR);
    $stmt->bindValue(':off', 0, PDO::PARAM_INT);
    $stmt->execute();

    if($stmt->rowCount() > 0) {
        $_SESSION['msg'] = "<script>approveAll()</script>";
        // $_SESSION['msg'] = "<script>vt.success('Uniforms have been approved Successfully!', {duration: 5000, position: 'bottom-right'})</script>";
        header('Location: uniform_app.php');
    } else {
        $_SESSION['msg'] = "<script>vt.error('Turn off editing mode first before Approving!', {duration: 5000, position: 'bottom-right'})</script>";
        header('Location: uniform_app.php');
    }
?>