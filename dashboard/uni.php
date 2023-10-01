<?php
    session_start();
    include('inactivity.php');
    require('../conn/conn.php');

    if(!isset($_SESSION['admin']) && !isset($_SESSION['hr'])) {
        header("Location: ../login.php");
    }

    if(isset($_SESSION['change'])) {
        header('location: changepassword.php');
    }

    $res = $conn->query("SELECT * FROM user WHERE userID = '{$_SESSION['uid']}'");

    $row = $res->fetch_array(MYSQLI_BOTH);

    $fullname = $row['firstname']." ".$row['lastname'];

    $all_dimensions = $conn->query("SELECT * FROM dimensions");
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
    <script src="../swal/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../swal/sweetalert2.css">
</head>
<body>
        <script>
            function permissionON() {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Permission Granted',
                    showConfirmButton: false,
                    timer: 1500
                })
            }

            function permissionOFF() {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Permission Turned Off',
                    showConfirmButton: false,
                    timer: 1500
                })
            }
            function approved() {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Uniform approved Successfully',
                    showConfirmButton: false,
                    timer: 1600
                })
            }
            function submitted() {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Uniform Submitted Successfully',
                    showConfirmButton: false,
                    timer: 1600
                })
            }
            function submitAll() {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Uniforms Submitted Successfully',
                    showConfirmButton: false,
                    timer: 1600
                })
            }
            function approveAll() {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Uniforms Approved Successfully',
                    showConfirmButton: false,
                    timer: 1600
                })
            }
            
        </script>
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
                        <a  class="active" href="uniform.php"><i class="bx bx-add-to-queue icon"></i>Uniforms</a>
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
                <?php elseif(isset($_SESSION['hr'])): ?>
                    <li>
                        <a href="index.php"><i class="bx bx-grid-alt icon"></i>Dashboard</a>
                    </li>

                    <li>
                        <a class="active" href="uniform.php"><i class="bx bx-add-to-queue icon"></i>Uniforms</a>
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
                <h3 class="bread-cumb"><a href="index.php">Dashboard</a> > <a href="uniform.php">Uniform</a> > Uniform Apps</h3>
                <span><img src="../photos/<?=$row['image']?>"><?=$fullname?></span>
                <a href="logout.php?logout_id=<?=$row['userID']?>">Logout</a>
            </header>

            <div class="cards grid ucard">
                <div class="flex">
                    <h4>Uniform Applications</h4>
                    <div class="flex">
                        <?php if($all_dimensions->num_rows > 0): ?>
                            <a href="all_dimensions_.php?department=<?=$_REQUEST['department']?>" style="margin-right: 10px;" class="btn-view-all">View Dimensions</a>
                        <?php endif;
                            $check = $conn->query("SELECT * FROM dimensions WHERE (status = 'pending' AND year(app_date_time) = year(current_date())) AND permission = 0");
                            if($check->num_rows > 0):
                        ?>
                        <a href="approve_all.php" style="margin-right: 10px;" class="btn-add"><i class="bx bx-check-double" style="margin-right: 5px; font-size: 19px"></i>Approve Uniforms</a>
                        <?php endif ?>
                        <!-- <a id="export" class="btn-add" style="margin-right: 10px;"><i class="bx bx-export" style="margin-right: 5px;"></i>Export to Excel</a> -->
                        <?php
                            $check2 = $conn->query("SELECT * FROM dimensions WHERE status = 'approved' AND year(app_date_time) = year(current_date())");
                            if($check2->num_rows > 0):
                        ?>
                        <a href="submit.php" class="btn-add">Submit Uniforms<i class="bx bx-mail-send" style="margin-left: 5px; font-size: 19px"></i></a>
                        <?php endif ?>
                    </div>
                </div>
                <table class="table-u" data-excel-name="All Users">
                    <tbody>
                        <?php $dep = $conn->query("SELECT * FROM department WHERE deptID = '{$row['deptID']}'");
                        $userRow = $dep->fetch_array(MYSQLI_ASSOC);
                        ?>
                        <tr>
                        <th>SN.</th><th>Firstname</th><th>Lastname</th><th>Department</th><th>Costume</th><th>Year</th><th>Dimensions</th><th>Editing Mode</th><th>Status</th><th>Action</th>
                        </tr>
                        <?php 
                            $uniform = $conn->query("SELECT dimensions.*, uniform.* FROM dimensions INNER JOIN uniform ON dimensions.uniformID = uniform.unID");
                            $sn = 1;
                            $status = "";
                            $em = "";
                            if($uniform->num_rows > 0):
                                while($row = $uniform->fetch_array(MYSQLI_ASSOC)):
                                    $sql = $conn->query("SELECT user.*, department.* FROM user INNER JOIN department ON department.deptID = user.deptID WHERE user.userID = '{$row['userID']}' AND department.deptName = '{$_REQUEST['department']}'");
                                    while($row2 = $sql->fetch_array(MYSQLI_ASSOC)):
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

                                        if($row['permission'] === '1'){
                                            $em = "<span class=on>On</span>";
                                        } elseif($row['permission'] === '0'){
                                            $em = "<span class=off>Off</span>";
                                        }
                        ?>
                        <tr>
                            <td><?=$sn++?></td><td><?=$row2['firstname']?></td><td><?=$row2['lastname']?></td><td><?=$row2['deptName']?></td><td><?=$row['name']?></td><td><?=$year.' / '.$year + 1?></td><td><a href="view_dimension.php?id=<?=$row['dimID']?>&userID=<?=$row2['userID']?>" class="btn-view">View</a></td><td><?=$em?></td><td><?=$status?></td><td><?php if($row['status'] !== 'submitted'):?><?php if($row['permission'] !== '1'):?><?php if($row['status'] === 'pending'):?><a href="approve.php?id=<?=$row['dimID']?>"><i class="bx bx-check-double icon approve"></i></a><?php endif; if($row['status'] === 'approved'):?><a href="submit_uniform.php?id=<?=$row['dimID']?>"><i class="bx bx-send icon send"></i></a><?php endif;?><a href="allow.php?id=<?=$row['dimID']?>"><i class="bx bx-universal-access icon allow"></i></a><?php else: ?><a href="off.php?id=<?=$row['dimID']?>"><i class="bx bx-power-off icon off"></i></a><?php endif; endif?></td>
                        </tr>
                        <?php endwhile; endwhile; endif; ?>
                    </tbody>
                </table>
            </div>

            <!-- <div class="footer">
                Copyright &copy; <?=date('Y')?>. All Righst Reserved.
            </div> -->
        </main>
    </div>
</body>
</html>