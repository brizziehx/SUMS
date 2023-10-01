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

    $row = $res->fetch_array(MYSQLI_BOTH);

    $fullname = $row['firstname']." ".$row['lastname'];

    
    $datetime = explode(' ', $row['created_at']);

    $date = $datetime[0];
    $time = $datetime[1];

    $dateRow = explode('-', $date);
    $year = $dateRow[0];
    $month = $dateRow[1];
    $day = $dateRow[2];

    $timeRow = explode(':', $time);
    $hours = $timeRow[0];
    $minutes = $timeRow[1];
    $seconds = $timeRow[2];

    $user_timestamp = mktime($hours,$minutes,$seconds,$month,$day,$year);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile | UMS</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="shortcut icon" href="../inc/fav.ico" type="image/x-icon">
    <link rel="stylesheet" href="../boxicons/css/boxicons.min.css">
</head>
<body>
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
                <h3 class="bread-cumb"><a href="index.php">Dashboard</a> > Profile</h3>
                <span><img src="../photos/<?=$row['image']?>"><?=$fullname?></span>
                <a href="logout.php?logout_id=<?=$row['userID']?>">Logout</a>
            </header>

            <div class="profile">
                <div class="picture">
                    <img src="../photos/<?=$row['image']?>" alt="<?=$row['firstname']?>\'s image">
                </div>
                <div class="p-header">
                    <h3>Profile Information</h3>
                </div>
    
                <div class="p-card">
                    First Name : <span> <b><?=$row['firstname'] ?></b> </span>
                </div>
                <div class="p-card">
                    Last Name : <span> <b><?=$row['lastname'] ?></b> </span>
                </div>
                <div class="p-card">
                    Email Address : <span><b><?=$row['email']?></b></span>
                </div>
                <div class="p-card">
                    Gender : <span><b><?=$row['gender']?></b></span>
                </div>
                <div class="p-card">
                    Phone Number : <span><b><?=$row['phone']?></b></span>
                </div>
                <div class="p-card">
                    User Account Type : <span><b><?=$row['usertype']?></b></span>
                </div>
                <div class="p-card">
                    Date Created : <span><b><?=date('D, jS F Y', $user_timestamp)?></b></span>
                </div>
                <div class="p-card">
                    Time Created : <span><b><?=date('H:i:s', $user_timestamp)?></b></span>
                </div>
                <div class="p-card-full"></div>
                <a href="changepass.php" class="p-card">Change Password</a>
            </div>

            <!-- <div class="footer">
                Copyright &copy; <?=date('Y')?>. All Righst Reserved.
            </div> -->
        </main>
    </div>
</body>
</html>