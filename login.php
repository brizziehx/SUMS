<?php
session_start();
require_once('conn/pdo.php');


if(isset($_SESSION['uid'])) {
    header("location: dashboard/index.php");
}

$errors = ['email'=>'','pass'=>'','locked'=>'','inactive'=>''];
$email = $password = '';

if(isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $_SESSION['password'] = $password;

    if(empty($email)) {
        $errors['email'] = "Email is required";
    } else {
        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Email is not valid";
        }
    }

    if(empty($password)) {
        $errors['pass'] = "Password is required";
    }

    // if($password == 'Sums@'.date('Y')) {
    //     $_SESSION['change'] = $password;
    // }

    if(!array_filter($errors)) {
        $stmt = $conn->prepare("SELECT * FROM user WHERE email = :email");
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $numRow = $stmt->rowCount();

        if($numRow == 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if($_SESSION['password'] == 'Sums@'.date('Y') || strtoupper($row['lastname']) === $_SESSION['password']) {
                $_SESSION['change'] = $_SESSION['password'];
            } else {
                unset($_SESSION['change']);
                unset($_SESSION['password']);
            }

            if(password_verify($password, $row['password'])) {
                date_default_timezone_set('Africa/Nairobi');
                $time = date('Y-m-d H:i:s');
                $conn->query("UPDATE user SET logintime = '{$time}' WHERE userID = {$row['userID']}");
                if($row['usertype'] === 'Admin') {
                    $_SESSION['uid'] = $row['userID'];
                    $_SESSION['admin'] = $row['usertype'];
                    $_SESSION['last_timestamp'] = time();
                    header("location: dashboard/");
                } elseif($row['usertype'] === 'Employee') {
                    $_SESSION['uid'] = $row['userID'];
                    $_SESSION['employee'] = $row['usertype'];
                    $_SESSION['last_timestamp'] = time();
                    header("location: dashboard/");
                } elseif($row['usertype'] === 'HR') {
                    $_SESSION['uid'] = $row['userID'];
                    $_SESSION['hr'] = $row['usertype'];
                    $_SESSION['last_timestamp'] = time();
                    header("location: dashboard/");
                } elseif($row['usertype'] === 'Supplier') {
                    $_SESSION['uid'] = $row['userID'];
                    $_SESSION['supplier'] = $row['usertype'];
                    $_SESSION['last_timestamp'] = time();
                    header("location: dashboard/");
                }
            } else {
                    $errors['pass'] = "Wrong password! try again";
            }
        } else {
            $errors['email'] = "Email doesn't exist";
        }
    }
}

if(isset($_GET['status']) && $_GET['status'] == 'inactivity') {
    $errors['inactive'] = "You have been logged out due to inactivity";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | UMS</title>
    <link rel="stylesheet" href="css/style.css">

</head>
<body>
    <div class="login-container">
        <form action="" method="post" autocomplete="off">
            <h3>Login | SUMS</h3>
            <label>Email</label>
            <input type="text" name="email" value="<?=$email ?? ''?>">
            <div class="err"><?=$errors['email']?></div>
            <label>Password</label>
            <input type="password" name="password" value="<?=$password ?? ''?>" id="pass">
            <div class="err"><?=$errors['pass']?></div>
            <div class="pw"><input onclick="myFunction()" type="checkbox" id="check"> <span>Show Password</span></div>
            <input type="submit" class="login-btn" value="Login" name="login">
            <div class="err" style="text-align:center"><?=$errors['inactive']?></div>
            <p>Forgot password!? Reset it <a href="forgot_pass.php">here</a></p>
        </form>
    </div>
    <script>
        function myFunction() {
            const pass = document.getElementById("pass");
            if (pass.type === "password") {
                pass.type = "text";
            } else {
                pass.type = "password";
            }
        }
</script>

</body>
</body>
</html>