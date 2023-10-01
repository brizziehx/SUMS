<?php
    session_start();
    date_default_timezone_set('Africa/Nairobi');
    include('inactivity.php');
    require('../conn/pdo.php');

    if(!isset($_SESSION['hr'])) {
        header("Location: ../login.php");
    }
    

    try {
        $sql = $conn->query("SELECT user.*, dimensions.* FROM user INNER JOIN dimensions ON user.userID = dimensions.userID WHERE dimensions.sup_status = 'complete' AND dimensions.flag <> 1");
        if($sql->rowCount() > 0) {
            while($row = $sql->fetch(PDO::FETCH_ASSOC)):

                $msg = "Hello there ".$row['firstname']." ". $row['lastname'].", your uniforms are ready to pick up.";

                $stmt = $conn->prepare("INSERT INTO notifications(Details, userID, date_time, fromUserID) VALUES(:Details, :userID, :date_time, :fromUserID)");
                $stmt->bindValue(':Details', $msg, PDO::PARAM_STR);
                $stmt->bindValue(':userID', $row['userID'], PDO::PARAM_INT);
                $stmt->bindValue(':date_time', date('Y-m-d H:i:s'), PDO::PARAM_STR);
                $stmt->bindValue(':fromUserID', $_SESSION['uid'], PDO::PARAM_INT);
                $stmt->execute();

                $stmt2 = $conn->prepare("UPDATE dimensions SET flag = :flag WHERE userID = :userID");
                $stmt2->bindValue(':flag', 1, PDO::PARAM_INT);
                $stmt2->bindValue(':userID', $row['userID'], PDO::PARAM_INT);
                $stmt2->execute();
            endwhile;

            if($stmt->rowCount() > 0) {
                $_SESSION['msg'] = "<script>sent()</script>";
                header('Location: uniform_app.php');
            }
        } else {
            echo 'all users are already notified';
        }
    } catch(Exception $e) {
        echo $e->getMessage();
    }
?>