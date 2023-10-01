<?php
    session_start();
    include('inactivity.php');
    require('../conn/pdo.php');

    if(!isset($_SESSION['uid'])) {
        header("Location: ../login.php");
    }
    

    $stmt = $conn->prepare("DELETE FROM notifications WHERE notID = :notID");
    $stmt->bindValue(':notID', $_REQUEST['id'], PDO::PARAM_INT);
    $stmt->execute();

    if($stmt->rowCount() > 0) {
        $_SESSION['msg'] = "<script>deletedSuccessfully()</script>";
        header('Location: feeds.php');
    }

?>