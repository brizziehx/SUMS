<?php
    session_start();
    include('inactivity.php');
    require('../conn/pdo.php');

    if(!isset($_SESSION['admin'])) {
        header("Location: ../login.php");
    }

    $stmt = $conn->prepare("DELETE FROM department WHERE deptID = :deptID");
    $stmt->bindValue(':deptID', $_REQUEST['id'], PDO::PARAM_INT);
    if($stmt->execute()) {
        $_SESSION['msg'] = "<script>vt.success('Department was deleted successfully', {duration: 5000, position: 'bottom-right'})</script>";
        header('Location: departments.php');
    }
