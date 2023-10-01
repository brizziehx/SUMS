<?php
session_start();

if(!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
}

require('../conn/pdo.php');

$id = $_REQUEST['id'];

$stmt = $conn->prepare("UPDATE user SET password = :pw WHERE userID = :userID");
$stmt->bindValue(':pw', password_hash('Sums@'.date('Y'), PASSWORD_BCRYPT), PDO::PARAM_STR);
$stmt->bindValue(':userID', $id, PDO::PARAM_INT);
if($stmt->execute()) {
    $date = date('Y');
    $_SESSION['msg'] = "<script>vt.success('Password Updated! New Password: Sums@' + new Date().getFullYear(), {duration: 5000, position: 'bottom-right'})</script>";
    header('Location:users.php');
}

