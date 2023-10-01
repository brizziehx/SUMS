<?php
    session_start();
    include('inactivity.php');
    require('../conn/conn.php');

    if(!isset($_SESSION['uid'])) {
        header("Location: ../login.php");
    }

    if(isset($_SESSION['change'])) {
        header('location: changepassword.php');
    }

    $res = $conn->query("SELECT * FROM user WHERE userID = '{$_SESSION['uid']}'");

    $row = $res->fetch_array(MYSQLI_ASSOC);

    $fullname = $row['firstname']." ".$row['lastname'];

    $errors = [];

    if(isset($_POST['submit'])) {
        $password = $_POST['password'];
        $npassword = $_POST['npassword'];
        $cpassword = $_POST['cpassword'];
    
    
        if(empty($password)) {
            $errors['pass'] = "Current password is required";
        }
    
        if(empty($npassword)) {
            $errors['npass'] = "New password is required";
        } elseif(!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^\da-zA-Z]).{8,}$/', $npassword)) {
            $errors['npass'] = "Choose a strong password";
        }
    
        if(empty($cpassword)) {
            $errors['cpass'] = "Confirmation password is required";
        } elseif($npassword !== $cpassword) {
            $errors['cpass'] = "Passwords doesn't match";
        }
    
        if(!array_filter($errors)) {
    
            $sql = "SELECT password FROM user WHERE userID = '{$_SESSION['uid']}'";
            $user = $conn->query($sql);
    
            $row = $user->fetch_array(MYSQLI_BOTH);
    
            if(password_verify($password, $row['password'])) {
                $npassword = password_hash($npassword, PASSWORD_BCRYPT);
                $stmt = $conn->prepare("UPDATE user SET password = ? WHERE userID = ?");
                $stmt->bind_param('si', $npassword, $_SESSION['uid']);
                if($stmt->execute()) {
                    $password = $npassword = $cpassword = "";
                    $_SESSION['msg'] = "<script>changed()</script>";
                }
            } else {
                $errors['pass'] = "Current password is incorect!";
            }
        }
        
    }

    $res = $conn->query("SELECT * FROM user WHERE userID = '{$_SESSION['uid']}'");

    $row = $res->fetch_array(MYSQLI_ASSOC);

    $fullname = $row['firstname']." ".$row['lastname'];


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password | UMS</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="shortcut icon" href="../inc/fav.ico" type="image/x-icon">
    <link rel="stylesheet" href="../boxicons/css/boxicons.min.css">
    <script src="../swal/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../swal/sweetalert2.css">
</head>
<body>
    <script>
        function changed() {
            Swal.fire({
                position: 'center',
                icon: 'success',
                title: 'Password changed successfully',
                showConfirmButton: false,
                timer: 1500
            })
        }
    </script>
    <?php if(isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
    } 
    unset($_SESSION['msg'])
    ?>
    <div class="container">
        <aside>
            <?php 
                switch($row['usertype']){
                    case 'Admin';
                        $user = "Administrator";
                        break;
                    case 'Employee';
                        $user = "Employee";
                        break;
                    case 'Supplier':
                        $user = "Supplier";
                        break;
                    case 'HR':
                        $user = "Human Resource - HR";
                        break;
                    default:
                        $user = "";
                        break;
                }
            ?>
            <div class="logo">
                <img src="../inc/SUMS.png" alt="logp">
                <span>Staff Uniform Management System</span>
                <h3 align="center"><?=$user?></h3>
            </div>
            <nav>
                <?php if(isset($_SESSION['admin'])): ?>
                    <li>
                        <a href="index.php"><i class="bx bx-grid-alt icon"></i>Dashboard</a>
                    </li>
                    

                    <li>
                        <a href="users.php"><i class="bx bxs-user-detail icon"></i>Users</a>
                    </li>

                    <li>
                        <a href="departments.php"><i class="bx bx-home icon"></i>Departments</a>
                    </li>

                    <li>
                        <a href="uniform.php"><i class="bx bx-add-to-queue icon"></i>Uniforms</a>
                    </li>

                    
                    <li>
                        <a href="uniform_app.php"><i class="bx bx-folder icon"></i> Uniform Applications</a>
                    </li>

                    <li>
                        <a href="feeds.php"><i class="bx bx-message-square-detail icon"></i>FeedBack</a>
                    </li>

                    <li>
                        <a href="logs.php"><i class="bx bx-cog icon"></i>Logs</a>
                    </li>

                    <li>
                        <a class="active" href="profile.php"><i class="bx bx-user icon"></i>Profile</a>
                    </li>

                    <li>
                        <a href="logout.php?logout_id=<?=$row['userID']?>"><i class="bx bx-log-out icon"></i>Logout</a>
                    </li>
                <?php elseif(isset($_SESSION['employee'])): ?>
                    <li>
                        <a href="index.php"><i class="bx bx-grid-alt icon"></i>Dashboard</a>
                    </li>

                    <li>
                        <a href="apply_uniform.php"><i class="bx bx-add-to-queue icon"></i> Uniform Application</a>
                    </li>

                    <li>
                        <a href="feeds.php"><i class="bx bx-message-square-detail  icon"></i>Notications</a>
                    </li>

                    <li>
                        <a class="active" href="profile.php"><i class="bx bx-user icon"></i>Profile</a>
                    </li>

                    <li>
                        <a href="logout.php?logout_id=<?=$row['userID']?>"><i class="bx bx-log-out icon"></i>Logout</a>
                    </li>
                    
                <?php elseif(isset($_SESSION['supplier'])): ?>
                    <li>
                        <a href="index.php"><i class="bx bx-grid-alt icon"></i>Dashboard</a>
                    </li>
                    <li>
                        <a href="received.php"><i class="bx bx-add-to-queue icon"></i>Received Uniforms</a>
                    </li>
                    <li>
                        <a href="payments.php"><i class="bx bx-money icon"></i>Payments</a>
                    </li>
                    <li>
                        <a class="active" href="profile.php"><i class="bx bx-user icon"></i>Profile</a>
                    </li>

                    <li>
                        <a href="logout.php?logout_id=<?=$row['userID']?>"><i class="bx bx-log-out icon"></i>Logout</a>
                    </li>
                <?php elseif(isset($_SESSION['hr'])): ?>
                    <li>
                        <a href="index.php"><i class="bx bx-grid-alt icon"></i>Dashboard</a>
                    </li>

                    <li>
                        <a href="uniform.php"><i class="bx bx-add-to-queue icon"></i>Uniforms</a>
                    </li>
                    
                    <li>
                        <a href="uniform_app.php"><i class="bx bx-folder icon"></i> Uniform Applications</a>
                    </li>

                    <li>
                        <a href="feeds.php"><i class="bx bx-message-square-detail icon"></i>FeedBack</a>
                    </li>

                    <li>
                        <a href="report.php"><i class="bx bx-file icon"></i>Report</a>
                    </li>

                    <li>
                        <a class="active" href="profile.php"><i class="bx bx-user icon"></i>Profile</a>
                    </li>

                    <li>
                        <a href="logout.php?logout_id=<?=$row['userID']?>"><i class="bx bx-log-out icon"></i>Logout</a>
                    </li>
                <?php endif; ?>
            </nav>
        </aside>
        <main>
            <header>
                <h3 class="bread-cumb"><a href="index.php">Dashboard</a> > <a href="profile.php">Profile</a> > Change Password</h3>
                <span><img src="../photos/<?=$row['image']?>"><?=$fullname?></span>
                <a href="logout.php?logout_id=<?=$row['userID']?>">Logout</a>
            </header>

            <div class="grid">
            <div class="user-change">
                    <form action="" class="regform" method="post">
                    <h3>Change Password</h3>
                        <div class="input">
                            <label>Current Password</label>
                            <input type="password" name="password" value="<?=$password ?? ''?>">
                            <div class="err"><?=$errors['pass'] ?? ''?></div>
                        </div>
                        <div class="input">
                            <label>New Password</label>
                            <input type="password" name="npassword" value="<?=$npassword ?? ''?>">
                            <div class="err"><?=$errors['npass'] ?? ''?></div>
                        </div>
                        <div class="input">
                            <label>Confirm New Password</label>
                            <input type="password" name="cpassword" value="<?=$cpassword ?? ''?>">
                            <div class="err"><?=$errors['cpass'] ?? ''?></div>
                        </div>
                        
                        <input type="submit" class="create-btn" value="Chnage Password" name="submit">
                        <div class="suc"></div>
                    </form>
                </div>
            </div>

            <!-- <div class="footer">
                Copyright &copy; <?=date('Y')?>. All Righst Reserved.
            </div> -->
        </main>
    </div>
</body>
</html>