<?php
    session_start();
    include('inactivity.php');
    require('../conn/conn.php');

    if(!isset($_SESSION['supplier'])) {
        header("Location: ../login.php");
    }

    if(isset($_SESSION['change'])) {
        header('location: changepassword.php');
    }

    $res = $conn->query("SELECT * FROM user WHERE userID = '{$_SESSION['uid']}'");

    $row = $res->fetch_array(MYSQLI_BOTH);

    $fullname = $row['firstname']." ".$row['lastname'];

    $all_dimensions = $conn->query("SELECT * FROM dimensions");

    $rec = $conn->query("SELECT * FROM dimensions WHERE year(app_date_time) = year(current_date()) AND sup_status = 'received'");
    $inp = $conn->query("SELECT * FROM dimensions WHERE year(app_date_time) = year(current_date()) AND sup_status = 'inprogress'");
    $comp = $conn->query("SELECT * FROM dimensions WHERE (year(app_date_time) = year(current_date()) AND sup_status = 'complete') AND flag = 0")
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Received Uniforms | UMS</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="shortcut icon" href="../inc/fav.ico" type="image/x-icon">
    <link rel="stylesheet" href="../boxicons/css/boxicons.min.css">
    <script src="../swal/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../swal/sweetalert2.css">
</head>
<body>
        <script>
            function inProgress() {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Uniform is inprogress!',
                    showConfirmButton: false,
                    timer: 1500
                })
            }

            function inProgressAll() {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Uniforms are inprogress!',
                    showConfirmButton: false,
                    timer: 1500
                })
            }

            function complete() {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Uniform is complete!',
                    showConfirmButton: false,
                    timer: 1500
                })
            }

            function completeAll() {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Uniforms are complete!',
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

            function sent() {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Notification has been sent successfully',
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
                <?php if(isset($_SESSION['supplier'])): ?>
                    <li>
                        <a href="index.php"><i class="bx bx-grid-alt icon"></i>Dashboard</a>
                    </li>

                    <li>
                        <a class="active" href="received.php"><i class="bx bx-add-to-queue icon"></i>Received Uniforms</a>
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
                <?php endif; ?>
            </nav>
        </aside>
        <main>
            <header>
                <h3 class="bread-cumb"><a href="index.php">Dashboard</a> > Received Uniforms</h3>
                <span><img src="../photos/<?=$row['image']?>"><?=$fullname?></span>
                <a href="logout.php?logout_id=<?=$row['userID']?>">Logout</a>
            </header>

            <div class="cards grid ucard">
                <div class="flex">
                    <h4>Received Uniforms</h4>
                    <div class="flex">
                        <?php if($comp->num_rows > 0): ?>
                            <a href="notify_all.php" class="btn-add" style="margin-right: 10px;"><i class="bx bx-mail-send" style="margin-right: 5px;"></i>Notify</a>
                        <?php endif ?>
                        <?php if($rec->num_rows > 0): ?>
                            <a href="process_all.php" class="btn-add" style="margin-right: 10px;"><i class="bx bx-analyse" style="margin-right: 5px;"></i>Inprogress</a>
                        <?php endif ?>
                        <?php if($inp->num_rows > 0): ?>
                            <a href="complete_all.php" class="btn-add" style="margin-right: 10px;"><i class="bx bx-check-circle" style="margin-right: 5px;"></i>Complete</a>
                        <?php endif ?>
                        <a href="print_all.php" class="btn-add" style="margin-right: 10px;"><i class="bx bx-printer" style="margin-right: 5px;"></i>Print Dimensions</a>
                    </div>
                </div>
                <table class="table-u">
                    <tbody>
                        <?php $dep = $conn->query("SELECT * FROM department WHERE deptID = '{$row['deptID']}'");
                        $userRow = $dep->fetch_array(MYSQLI_ASSOC);
                        ?>
                        <tr>
                        <th>SN.</th><th>Firstname</th><th>Lastname</th><th>Department</th><th>Costume</th><th>Year</th><th>Dimensions</th><th>Status</th><th>Print</th><th>Action</th>
                        </tr>
                        <?php 
                            $uniform = $conn->query("SELECT dimensions.*, uniform.* FROM dimensions INNER JOIN uniform ON dimensions.uniformID = uniform.unID WHERE status = 'submitted' ORDER BY dimID DESC");
                            $sn = 1;
                            $status = "";
                            $em = "";
                            if($uniform->num_rows > 0):
                                while($row = $uniform->fetch_array(MYSQLI_ASSOC)):
                                    $sql = $conn->query("SELECT user.*, department.* FROM user INNER JOIN department ON department.deptID = user.deptID WHERE user.userID = '{$row['userID']}'");
                                    while($row2 = $sql->fetch_array(MYSQLI_ASSOC)):
                                        $datetime = explode(' ', $row['app_date_time']);
                                        $date = $datetime[0];
                                        $dateRow = explode('-', $date);
                                        $year = $dateRow[0];
                                        switch($row['sup_status']) {
                                            case 'received':
                                                $status = "<span class='pending'>".$row['sup_status']."</span>";
                                                break;
                                            case 'complete':
                                                $status = "<span class='submitted'>".$row['sup_status']."</span>";
                                                break;
                                            case 'inprogress':
                                                $status = "<span class='approved'>inprogress</span>";
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
                            <td><?=$sn++?></td><td><?=$row2['firstname']?></td><td><?=$row2['lastname']?></td><td><?=$row2['deptName']?></td><td><?=$row['name']?></td><td><?=$year.' / '.$year + 1?></td><td><a href="view_dimension.php?id=<?=$row['dimID']?>&userID=<?=$row2['userID']?>" class="btn-view">View</a></td><td><?=$status?></td><td><a href="print_dim.php?id=<?=$row['dimID']?>&userID=<?=$row2['userID']?>"><i class="bx bx-printer icon" style="color: blue; margin-left:5px"></i></a></td><td><?php if($row['sup_status'] === 'received'):?><a class="btn-process" href="process.php?id=<?=$row['dimID']?>">Process</a><?php endif?><?php if($row['sup_status'] === 'inprogress'):?><a class="btn-done" href="complete.php?id=<?=$row['dimID']?>">Complete</a><?php endif?></td>
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