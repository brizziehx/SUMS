<?php
    session_start();
    include('inactivity.php');
    require('../conn/pdo.php');

    if(!isset($_SESSION['admin'])) {
        header("Location: ../login.php");
    }

    $stmt1 = $conn->prepare("SELECT * FROM user WHERE userID = :uid");
    $stmt1->bindValue(':uid', $_REQUEST['id'], PDO::PARAM_INT);
    $stmt1->execute();
    $row = $stmt1->fetch(PDO::FETCH_ASSOC);

    $stmt = $conn->prepare("DELETE FROM user WHERE userID = :userID");
    $stmt->bindValue(':userID', $_REQUEST['id'], PDO::PARAM_INT);
    if($stmt->execute()) {
        unlink('../photos/'.$row['image']);
        $_SESSION['msg'] = "<script>vt.success('User was deleted successfully', {duration: 5000, position: 'bottom-right'})</script>";
        header('Location:users.php');
    }
