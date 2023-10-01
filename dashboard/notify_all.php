<?php
    session_start();
    include('inactivity.php');
    require('../conn/pdo.php');

    if(!isset($_SESSION['supplier'])) {
        header("Location: ../login.php");
    }
    

    $sql = $conn->query("SELECT * FROM user WHERE usertype = 'HR'");
    $row = $sql->fetch(PDO::FETCH_ASSOC);

    $msg = "Hello there ".$row['firstname']." ". $row['lastname'].", I would like to inform you that all uniforms are ready to pick up.";

    $stmt = $conn->prepare("INSERT INTO notifications(Details, userID, date_time, fromUserID) VALUES(:Details, :userID, :date_time, :fromUserID)");
    $stmt->bindValue(':Details', $msg, PDO::PARAM_STR);
    $stmt->bindValue(':userID', $row['userID'], PDO::PARAM_INT);
    $stmt->bindValue(':date_time', date('Y-m-d H:i:s'), PDO::PARAM_STR);
    $stmt->bindValue(':fromUserID', $_SESSION['uid'], PDO::PARAM_INT);
    $stmt->execute();

    if($stmt->rowCount() > 0) {
        $_SESSION['msg'] = "<script>sent()</script>";
        header('Location: received.php');
    }

?>