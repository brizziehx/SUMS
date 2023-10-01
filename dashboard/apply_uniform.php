<?php
    error_reporting(0);
    session_start();
    include('inactivity.php');
    require('../conn/conn.php');

    if(!isset($_SESSION['employee'])) {
        header("Location: ../login.php");
    }

    if(isset($_SESSION['change'])) {
        header('location: changepassword.php');
    }

    $res = $conn->query("SELECT * FROM user WHERE userID = '{$_SESSION['uid']}'");

    $row = $res->fetch_array(MYSQLI_BOTH);

    $fullname = $row['firstname']." ".$row['lastname'];

    $permission = $conn->query("SELECT permission, status FROM dimensions WHERE userID = '{$_SESSION['uid']}' AND year(app_date_time) = year(current_date())");
    $perm = $permission->fetch_array(MYSQLI_ASSOC);

    $uniform = $conn->query("SELECT dimensions.*, uniform.* FROM dimensions INNER JOIN uniform ON dimensions.uniformID = uniform.unID WHERE userID = '{$_SESSION['uid']}'");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uniform Application | UMS</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="shortcut icon" href="../inc/fav.ico" type="image/x-icon">
    <link rel="stylesheet" href="../boxicons/css/boxicons.min.css">
    <script src="../table2excel/table2excel.js"></script>
</head>
<body>
    <script src="../js/vanilla-toast.min.js"></script>
    <?php if(isset($_SESSION['msg'])) {
        echo $_SESSION['msg'];
    }
    unset($_SESSION['msg']);
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
                <?php if(isset($_SESSION['employee'])): ?>
                    <li>
                        <a href="index.php"><i class="bx bx-grid-alt icon"></i>Dashboard</a>
                    </li>

                    <li>
                        <a class="active" href="apply_uniform.php"><i class="bx bx-add-to-queue icon"></i> Uniform Application</a>
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

                <?php endif; ?>
            </nav>
        </aside>
        <main>
            <header>
                <h3 class="bread-cumb"><a href="index.php">Dashboard</a> > Uniform Application</h3>
                <span><img src="../photos/<?=$row['image']?>"><?=$fullname?></span>
                <a href="logout.php?logout_id=<?=$row['userID']?>">Logout</a>
            </header>

            <div class="cards grid ucard">
                <div class="flex">
                    <h4>Uniform Application</h4>
                    <div class="flex">
                        <!-- <a id="export" class="btn-add" style="margin-right: 10px;"><i class="bx bx-export" style="margin-right: 5px;"></i>Export to Excel</a> -->
                        <?php $uniformNow = $conn->query("SELECT dimensions.*, uniform.* FROM dimensions INNER JOIN uniform ON dimensions.uniformID = uniform.unID WHERE userID = '{$_SESSION['uid']}' AND year(app_date_time) = year(current_date())"); 
                            if(!$uniformNow->num_rows > 0): ?>
                                <a href="newUniform.php" class="btn-add"><i class="bx bx-plus" style="margin-right: 5px; font-size: 19px"></i>Apply Uniform</a>
                        <?php endif ?>
                    </div>
                </div>
                <?php $uniform = $conn->query("SELECT dimensions.*, uniform.* FROM dimensions INNER JOIN uniform ON dimensions.uniformID = uniform.unID WHERE userID = {$_SESSION['uid']} ORDER BY dimID DESC"); 
                    if($uniform->num_rows > 0): ?>
                <table class="table-u" data-excel-name="All Users">
                    <tbody>
                        <?php $dep = $conn->query("SELECT * FROM department WHERE deptID = '{$row['deptID']}'");
                        $userRow = $dep->fetch_array(MYSQLI_ASSOC);
                        ?>
                        <tr>
                            <th>SN.</th><th>Firstname</th><th>Lastname</th><th>Department</th><th>Costume</th><th>Year</th><th>Dimensions</th><th>Status</th><?php if($perm['permission'] === '1' && $perm['status'] <> 'submitted'):?><th>Action</th><?php endif ?>
                        </tr>
                        <?php 
                            $sn = 1;
                            $sql = $conn->query("SELECT * FROM user WHERE userID = '{$_SESSION['uid']}'");
                            $row2 = $sql->fetch_array(MYSQLI_ASSOC);
                            $status = "";
                                while($row = $uniform->fetch_array(MYSQLI_ASSOC)):
                                    $datetime = explode(' ', $row['app_date_time']);
                                    $date = $datetime[0];
                                    $dateRow = explode('-', $date);
                                    $year = $dateRow[0];
                                    switch($row['status']) {
                                        case 'pending':
                                            $status = "<span class='pending'>".$row['status']."</span>";
                                            break;
                                        case 'submitted':
                                            $status = "<span class='submitted'>".$row['status']."</span>";
                                            break;
                                        case 'approved':
                                            $status = "<span class='approved'>".$row['status']."</span>";
                                            break;
                                        default:
                                            $status = "";
                                            break;
                                    }
                        ?>
                        <tr>
                            <td><?=$sn++?></td><td><?=$row2['firstname'] ?></td><td><?=$row2['lastname']?></td><td><?=$userRow['deptName']?></td><td><?=$row['name']?></td><td><?=$year.' / '.$year+1?></td><td><a href="view.php?id=<?=$row['dimID']?>" class="btn-view">View</a></td><td><?=$status?></td><?php if($row['permission'] === '1' && $perm['status'] <> 'submitted'):?><td><a href="edit_dimensions.php?id=<?=$row['dimID']?>"><i class="bx bx-edit icon update"></i></a></td><?php endif?>
                        </tr>
                        <?php endwhile;?>
                    </tbody>
                </table>
                <?php  endif; ?>
            </div>

            <!-- <div class="footer">
                Copyright &copy; <?=date('Y')?>. All Righst Reserved.
            </div> -->
            <script>
                var table2excel = new Table2Excel();

                document.getElementById('export').addEventListener('click', function() {
                    table2excel.export(document.querySelectorAll('table'));
                });
            </script>
        </main>
    </div>
</body>
</html>