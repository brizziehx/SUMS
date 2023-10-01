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

    $row = $res->fetch_assoc();

    $fullname = $row['firstname']." ".$row['lastname'];

    $usersAll = $conn->query("SELECT * FROM user");

    $departments = $conn->query("SELECT * FROM department");

    $uniforms = $conn->query("SELECT * FROM uniform");

    //NOTIFICATION HR
    $nots = $conn->query("SELECT * FROM notifications WHERE userID = {$row['userID']} ORDER BY notID DESC LIMIT 1");
    $not = $conn->query("SELECT * FROM notifications WHERE userID = {$row['userID']}");

    // DIMENSIONS
    $approved = $conn->query("SELECT * FROM dimensions WHERE status = 'approved'");
    $pending = $conn->query("SELECT * FROM dimensions WHERE status = 'pending'");
    $submitted = $conn->query("SELECT * FROM dimensions WHERE status = 'submitted'");

    // SUPPLIER
    $received = $conn->query("SELECT * FROM dimensions WHERE sup_status = 'received' OR status = 'submitted'");
    $in_progress = $conn->query("SELECT * FROM dimensions WHERE sup_status = 'inprogress'");
    $completed = $conn->query("SELECT * FROM dimensions WHERE sup_status = 'complete'");

    // EMPLOYEE
    $app = $conn->query("SELECT dimensions.*, uniform.* FROM dimensions INNER JOIN uniform ON dimensions.uniformID = uniform.unID WHERE userID = {$_SESSION['uid']}"); 
    $empApp = $conn->query("SELECT dimensions.*, uniform.* FROM dimensions INNER JOIN uniform ON dimensions.uniformID = uniform.unID WHERE dimensions.userID = {$_SESSION['uid']} AND dimensions.status = 'approved'"); 
    $empSub = $conn->query("SELECT dimensions.*, uniform.* FROM dimensions INNER JOIN uniform ON dimensions.uniformID = uniform.unID WHERE dimensions.userID = {$_SESSION['uid']} AND dimensions.status = 'submitted'"); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | UMS</title>
    <link rel="shortcut icon" href="../inc/fav.ico" type="image/x-icon">
    <link rel="stylesheet" href="../css/style.css">
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
                        <a class="active" href="index.php"><i class="bx bx-grid-alt icon"></i>Dashboard</a>
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
                        <a href="profile.php"><i class="bx bx-user icon"></i>Profile</a>
                    </li>

                    <li>
                        <a href="logout.php?logout_id=<?=$row['userID']?>"><i class="bx bx-log-out icon"></i>Logout</a>
                    </li>
                <?php elseif(isset($_SESSION['employee'])): ?>
                    <li>
                        <a class="active" href="index.php"><i class="bx bx-grid-alt icon"></i>Dashboard</a>
                    </li>

                    <li>
                        <a href="apply_uniform.php"><i class="bx bx-add-to-queue icon"></i> Uniform Application</a>
                    </li>

                    <li>
                        <a href="feeds.php"><i class="bx bx-message-square-detail  icon"></i>Notications</a>
                    </li>

                    <li>
                        <a href="profile.php"><i class="bx bx-user icon"></i>Profile</a>
                    </li>

                    <li>
                        <a href="logout.php?logout_id=<?=$row['userID']?>"><i class="bx bx-log-out icon"></i>Logout</a>
                    </li>
                    
                <?php elseif(isset($_SESSION['supplier'])): ?>
                    <li>
                        <a class="active" href="index.php"><i class="bx bx-grid-alt icon"></i>Dashboard</a>
                    </li>
                    <li>
                        <a href="received.php"><i class="bx bx-add-to-queue icon"></i>Received Uniforms</a>
                    </li>
                    <li>
                        <a href="payments.php"><i class="bx bx-money icon"></i>Payments</a>
                    </li>
                    <li>
                        <a href="profile.php"><i class="bx bx-user icon"></i>Profile</a>
                    </li>

                    <li>
                        <a href="logout.php?logout_id=<?=$row['userID']?>"><i class="bx bx-log-out icon"></i>Logout</a>
                    </li>
                <?php elseif(isset($_SESSION['hr'])): ?>
                    <li>
                        <a class="active" href="index.php"><i class="bx bx-grid-alt icon"></i>Dashboard</a>
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
                        <a href="profile.php"><i class="bx bx-user icon"></i>Profile</a>
                    </li>

                    <li>
                        <a href="logout.php?logout_id=<?=$row['userID']?>"><i class="bx bx-log-out icon"></i>Logout</a>
                    </li>
                <?php endif; ?>
            </nav>
        </aside>
        <main>
            <header>
                <h3 class="bread-cumb">Dashboard</h3>
                <span><img src="../photos/<?=$row['image']?>"><?=$fullname?></span>
                <a href="logout.php?logout_id=<?=$row['userID']?>">Logout</a>
            </header>


            <div class="cards grid">
                <?php if(isset($_SESSION['admin'])): ?>
                    <a href="users.php" class="card">
                        <h4>Users</h4>
                        <span><?=$usersAll->num_rows?></span>
                    </a>
                    <a href="uniform.php" class="card">
                        <h4>Uniforms</h4>
                        <span><?=$uniforms->num_rows?></span>
                    </a>
                    <a href="departments.php" class="card">
                        <h4>Departments</h4>
                        <span><?=$departments->num_rows?></span>
                    </a>
                    <a href="all_approved.php" class="card">
                        <h4>Approved Uniforms</h4>
                        <span><?=$approved->num_rows?></span>
                    </a>

                    <a href="feeds.php" class="half">
                        <div class="flex">
                            <h4>Notifications </h4>
                            <b><?=$not->num_rows?></b>
                        </div>
                        <?php if($nots->num_rows > 0):
                            while($row = $nots->fetch_array(MYSQLI_ASSOC)): ?>
                            <P><?=$row['Details']?></P>
                        <?php endwhile; else: ?>
                            <p>There's No Notifications At This Time</p>
                        <?php endif ?>
                    </a>

                    <a href="all_submitted.php" class="card">
                        <h4>Submitted Uniforms</h4>
                        <span><?=$submitted->num_rows?></span>
                    </a>

                    <a href="all_pending.php" class="card">
                        <h4>Pending Uniforms</h4>
                        <span><?=$pending->num_rows?></span>
                    </a>
                    <!-- <a href="#" class="half">
                        <h4>Half Card</h4>
                    </a>

                    <a href="#" class="half">
                        <h4>Half Card</h4>
                    </a> -->

                    <!-- <a href="#" class="full">
                        <h4>Half Card</h4>
                    </a> -->

                    <?php elseif(isset($_SESSION['employee'])): ?>
                        <a href="feeds.php" class="card">
                            <div class="flex">
                                <h4>Notifications </h4>
                                <b><?=$not->num_rows?></b>
                            </div>
                            <?php if($nots->num_rows > 0):
                                while($row = $nots->fetch_array(MYSQLI_ASSOC)): ?>
                                <P><?=$row['Details']?></P>
                            <?php endwhile; else: ?>
                                <p>There's No Notifications At This Time</p>
                            <?php endif ?>
                        </a>

                        <a href="apply_uniform.php" class="card">
                            <h4>My Applications</h4>
                            <span><?=$app->num_rows ?></span>
                        </a>
                        
                        <a href="#" class="card">
                            <h4>Approved</h4>
                            <span><?=$empApp->num_rows?></span>
                        </a>
                            
                        <a href="#" class="card">
                            <h4>Submitted</h4>
                            <span><?=$empSub->num_rows?></span>
                        </a>

                    <?php elseif(isset($_SESSION['supplier'])): ?>
                        <a href="received.php" class="card">
                            <h4>Received Uniforms</h4>
                            <span><?=$received->num_rows?></span>
                        </a>
                        <a href="#" class="card">
                            <h4>Inprogress Uniforms</h4>
                            <span><?=$in_progress->num_rows?></span>
                        </a>

                        <a href="#" class="card">
                            <h4>Completed Uniforms</h4>
                            <span><?=$completed->num_rows?></span>
                        </a>
                    <?php elseif(isset($_SESSION['hr'])): ?>
                        <a href="all_approved.php" class="card">
                            <h4>Approved Uniforms</h4>
                            <span><?=$approved->num_rows?></span>
                        </a>

                        <a href="all_pending.php" class="card">
                            <h4>Pending Uniforms</h4>
                            <span><?=$pending->num_rows?></span>
                        </a>

                        <a href="feeds.php" class="card">
                            <div class="flex">
                                <h4>Notifications </h4>
                                <b><?=$not->num_rows?></b>
                            </div>
                            <?php if($nots->num_rows > 0):
                                while($row = $nots->fetch_array(MYSQLI_ASSOC)): ?>
                                <P><?=$row['Details']?></P>
                            <?php endwhile; else: ?>
                                <p>There's No Notifications At This Time</p>
                            <?php endif ?>
                        </a>

                        <a href="all_submitted.php" class="card">
                            <h4>Submitted Uniforms</h4>
                            <span><?=$submitted->num_rows?></span>
                        </a>

                        
                        
                    <?php endif; ?>

                <div class="footer">
                    Copyright &copy; <?=date('Y')?>. All Righst Reserved.
                </div>
            </div>

        </main>
    </div>
</body>
</html>