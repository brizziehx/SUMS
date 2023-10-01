<?php
    session_start();

    require('../conn/pdo.php');
    // $user = $conn->query("SELECT * FROM user WHERE userID = {$_SESSION['uid']}");
    // $row = $user->fetch_assoc();
    // $name = $row['firstname'].' '.$row['lastname'];
    // // $gen = ($row['gender'] == 'male') ? 'his' : 'her';

    $stmt = $conn->prepare("SELECT * FROM user WHERE userID = :uid");
    $stmt->bindValue(':uid', $_SESSION['uid'], PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $reseted_pass = 'Sums@'.date('Y');

    if(!isset($_SESSION['change'])) {
        header("Location: ../login.php");
    }

    $errors = ['pass'=>'','pass2'=>''];

    if(isset($_POST['update'])) {
        $password = $_POST['password'];
        $password2 = $_POST['password2'];

        if(empty($password)) {
            $errors['pass'] = "New password is required";
        } elseif($password == $reseted_pass){
            $errors['pass'] = "Please choose another password";
        } elseif(!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\da-zA-Z]).{8,}$/', $password)) {
            $errors['pass'] = "Please choose a strong password";
        }

        if(empty($password2)) {
            $errors['pass2'] = "Repeat password is required";
        } elseif($password2 != $password) {
            $errors['pass2'] = "Passwords doesn't match";
        }

        if(!array_filter($errors)) {
            $password = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $conn->prepare("UPDATE user SET password = :pw WHERE userID = :uid");
            $stmt->bindValue(':pw', $password, PDO::PARAM_STR);
            $stmt->bindValue(':uid', $_SESSION['uid'], PDO::PARAM_INT);
            if($stmt->execute()) {
                unset($_SESSION['change']);
                $password = "";
                $password2 = "";
                // $success['updated'] = "Password Updated Successfully!";
                $success['updated'] = "<script>updated();</script>!";
            }
        }
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <script src="../swal/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../swal/sweetalert2.min.css">
    <title>Update Password | Student Transport Management System</title>
</head>
<body>
    <script>
        function updated() {
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Password Updated',
                showConfirmButton: false,
                timer: 1500
            });
        }
    </script>
    <div class="login-container">
        <form method="POST" action="">
            <h3>Update Password to Continue</h3>
            <label>Enter New Password</label>
            <input type="password" name="password" value="<?=$password ?? ''?>">
            <div class="err"><?=$errors['pass']?></div>
            <label>Repeat New Password</label>
            <input type="password" name="password2" value="<?=$password2 ?? ''?>">
            <div class="err"><?=$errors['pass2']?></div>
            <input type="submit" class="login-btn" value="Update" name="update">
            <div class="suc" style="display: none;">
                <?php
                    if(isset($success['updated'])) {
                        echo $success['updated'];
                        echo "<script> setTimeout(()=> location.href = 'index.php', 1600)</script>";
                    }
                ?>
            </div>
        </form>
    </div>
</body>
</html>