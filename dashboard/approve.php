<?php
    session_start();
    include('inactivity.php');
    require('../conn/pdo.php');

    if(!isset($_SESSION['admin']) && !isset($_SESSION['hr'])) {
        header("Location: ../login.php");
    }


    $stmt = $conn->prepare("UPDATE dimensions SET status = :approved WHERE dimID = :dimID");
    $stmt->bindValue(':approved', 'approved', PDO::PARAM_STR);
    $stmt->bindValue(':dimID', $_REQUEST['id'], PDO::PARAM_INT);
    $stmt->execute();

    if($stmt->rowCount() > 0) {
        $_SESSION['msg'] = "<script>approved()</script>";
        // $_SESSION['msg'] = "<script>vt.success('Uniform has been approved Successfully', {duration: 5000, position: 'bottom-right'})</script>";
        header('Location: uniform_app.php');
    }
?>