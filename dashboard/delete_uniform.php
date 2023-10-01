<?php
    session_start();
    include('inactivity.php');
    require('../conn/pdo.php');

    if(!isset($_SESSION['admin'])) {
        header("Location: ../login.php");
    }

    $stmt = $conn->prepare("DELETE FROM uniform WHERE unID = :unID");
    $stmt->bindValue(':unID', $_REQUEST['id'], PDO::PARAM_INT);
    if($stmt->execute()) {
        $_SESSION['msg'] = "<script>vt.success('Uniform was deleted successfully', {duration: 5000, position: 'bottom-right'})</script>";
        header('Location: uniform.php');
    }
