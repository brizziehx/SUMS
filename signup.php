<?php
session_start();
require_once('conn/pdo.php');
if(isset($_SESSION['uid'])) {
    header("location: dashboard/index.php");
}

$errors = ['firstname'=>'','lastname'=>'','email'=>'','pass'=>''];
$success = ['suc'=>''];
$email = $password = '';

if(isset($_POST['register'])) {
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $utype = 'Admin';
    date_default_timezone_set('Africa/Nairobi');
    $date_time = date('Y-m-d H:i:s');

    if(empty($firstname)) {
        $errors['firstname'] = "Firstname is required";
    } else {
        if(!preg_match("/^[a-zA-Z']{3,30}$/", $firstname)) {
            $errors['firstname'] = "Firstname is not valid name";
        }
    }

    if(empty($lastname)) {
        $errors['lastname'] = "Lastname is required";
    } else {
        if(!preg_match("/^[a-zA-Z']{3,30}$/", $lastname)) {
            $errors['lastname'] = "Lastname is not valid name";
        }
    }

    $stmt = $conn->prepare("SELECT email FROM user WHERE email = :email");
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->rowCount();

    if(empty($email)) {
        $errors['email'] = "Email is required";
    } else {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Email is not valid";
        } elseif($row > 0) {
            $errors['email'] = "Email already exists! please choose another one";
        }
    }

    if(empty($password)) {
        $errors['pass'] = "Password is required";
    }

    if(!array_filter($errors)) {

        $password = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("INSERT INTO user(firstname, lastname, email, password, usertype, created_at) VALUES(:firstname, :lastname, :email, :password, :utype, :created_at)");
        $stmt->bindValue(':firstname', $firstname, PDO::PARAM_STR);
        $stmt->bindValue(':lastname', $lastname, PDO::PARAM_STR);
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':password', $password, PDO::PARAM_STR);
        $stmt->bindValue(':utype', $utype, PDO::PARAM_STR);
        $stmt->bindValue(':created_at', $date_time, PDO::PARAM_STR);

        if($stmt->execute()) {
            $firstname = $lastname = $email = $password = "";
            $success['suc'] = "User created successfully";
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
    <title>New User | UMS</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="login-container">
        <form action="" method="post" autocomplete="off">
            <h3>New User | SUMS</h3>
            <label>Firstname</label>
            <input type="text" name="firstname" value="<?=$firstname ?? ''?>">
            <div class="err"><?=$errors['firstname']?></div>
            <label>Lastname</label>
            <input type="text" name="lastname" value="<?=$lastname ?? ''?>">
            <div class="err"><?=$errors['lastname']?></div>
            <label>Email</label>
            <input type="text" name="email" value="<?=$email ?? ''?>">
            <div class="err"><?=$errors['email']?></div>
            <label>Password</label>
            <input type="password" name="password" value="<?=$password ?? ''?>">
            <div class="err"><?=$errors['pass']?></div>
            <input type="submit" class="login-btn" value="Register" name="register">
            <div class="suc"><?=$success['suc']?></div>
        </form>
    </div>
</body>
</html>