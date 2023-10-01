<?php
session_start();

include('inactivity.php');
require('../conn/conn.php');

if(!isset($_SESSION['hr'])) {
    header('location: ../login.php');
}

if(isset($_SESSION['change'])) {
    header('location: changepassword.php');
}

$res = $conn->query("SELECT * FROM user WHERE userID = '{$_SESSION['uid']}'");

$row = $res->fetch_array(MYSQLI_BOTH);

$fullname = $row['firstname']." ".$row['lastname'];

$dims = $conn->query("SELECT dimensions.*, uniform.* FROM dimensions INNER JOIN uniform ON dimensions.uniformID = uniform.unID WHERE month(app_date_time) = {$_REQUEST['month']}");
$comp = $conn->query("SELECT dimensions.*, uniform.* FROM dimensions INNER JOIN uniform ON dimensions.uniformID = uniform.unID WHERE month(app_date_time) = {$_REQUEST['month']} AND dimensions.sup_status = 'complete'");

switch($_REQUEST['month']) {
    case '01':
        $month = "January";
        break;
    case '02':
        $month = "February";
        break;
    case '03':
        $month = "March";
        break;
    case '04':
        $month = "April";
        break;
    case '05':
        $month = "May";
        break;
    case '06':
        $month = "June";
        break;
    case '07':
        $month = "July";
        break;
    case '08':
        $month = "August";
        break;
    case '09':
        $month = "September";
        break;
    case '10':
        $month = "October";
        break;
    case '11':
        $month = "November";
        break;
    case '12':
        $month = "December";
        break;
    default:
    $month = "";
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Uniform Management System - Monthly Report</title>
    <link rel="stylesheet" href="../css/print.css">
    <link rel="stylesheet" href="../boxicons/css/boxicons.min.css">
</head>
<body>
    <div class="container-r">
        <div class="header-content print">
            <div class="divider">
                <h2>Staff Uniform Management System</h2>
                <div class="text">
                    <img src="../inc/SUMS.png" alt="">
                </div>
                <h3>Year <?=date('Y')?> - <?=date('Y')+1?> Monthly Uniform Report</h3>
            </div>
            <div class="details">
                <div class="row">
                    <b>Month: </b> <?=$month?> 
                </div>
                <div class="row">
                    <b>Ordered Uniforms: </b> <?=$dims->num_rows?>
                </div>
                <div class="row">
                    <b>Completed: </b> <?=$comp->num_rows?>
                </div>

            </div>
            <div class="content">
                <h3>List Of Ordered Uniforms</h3>
                <table>
                    <tr>
                        <th>#</th><th>Firstname</th><th>Lastname</th><th>Gender</th><th>Department</th><th>Uniform Name</th>
                    </tr>
                    <?php
                        $dims = $conn->query("SELECT dimensions.*, uniform.* FROM dimensions INNER JOIN uniform ON dimensions.uniformID = uniform.unID WHERE month(app_date_time) = {$_REQUEST['month']} AND year(app_date_time) = year(current_date())");
                        $N = 1;
                        while($row__ = $dims->fetch_array(MYSQLI_BOTH)):

                            $users = $conn->query("SELECT user.*, department.* from user INNER JOIN department ON user.deptID = department.deptID WHERE user.userID = {$row__['userID']}");
                            while($row_ = $users->fetch_array(MYSQLI_BOTH)):
                            
                    ?>
                            <tr>
                                <td><?=$N++?></td><td><?=$row_['firstname']?></td><td><?=$row_['lastname']?></td><td><?=$row_['gender']?></td><td><?=$row_['deptName']?></td><td><?=$row__['name']?></td>
                            </tr>
                    <?php endwhile; endwhile; ?>
                </table>
            </div>
            <div class="hidden">
                <h4>Printed By: <?=$row['usertype']?> - <span><?php echo $fullname?></span></h4>
            </div>
        </div>
        <div class="buttons">
            <a href="report.php"><i class="bx bx-undo"></i>Go Back</a>
            <a class="printBtn"><i class="bx bx-printer"></i>Print Report</a>
        </div>
    </div>
    <script>
        const printBtn = document.querySelector('.printBtn');
        printBtn.addEventListener('click', () => {
            print()
        });
    </script>
</body>
</html>