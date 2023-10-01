<?php
    session_start();
    include('inactivity.php');
    require('../conn/conn.php');

    if(!isset($_SESSION['admin'])) {
        header("Location: ../login.php");
    }

    if(isset($_SESSION['change'])) {
        header('location: changepassword.php');
    }

    $res = $conn->query("SELECT * FROM user WHERE userID = '{$_SESSION['uid']}'");

    $row = $res->fetch_array(MYSQLI_BOTH);

    $fullname = $row['firstname']." ".$row['lastname'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logs | UMS</title>
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
                        <a  href="uniform_app.php"><i class="bx bx-folder icon"></i> Uniform Applications</a>
                    </li>

                    <li>
                        <a href="feeds.php"><i class="bx bx-message-square-detail icon"></i>FeedBack</a>
                    </li>

                    <li>
                        <a class="active" href="logs.php"><i class="bx bx-cog icon"></i>Logs</a>
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
                <h3 class="bread-cumb"><a href="index.php">Dashboard</a> > User Logs</h3>
                <span><img src="../photos/<?=$row['image']?>"><?=$fullname?></span>
                <a href="logout.php?logout_id=<?=$row['userID']?>">Logout</a>
            </header>


            <div class="cards grid ucard">
                <div class="flex">
                    <h4>User Logs</h4>
                    <div class="flex">
                        <a id="export" class="btn-add"><i style="margin-right: 5px;" class="bx bx-export"></i>Export to Excel</a>
                    </div>
                </div>
                <table class="table-u" data-excel-name="User Logs">
                    <tbody>
                        <tr>
                            <th>SN.</th><th>Firstname</th><th>Lastname</th><th>Email</th><th>Login Time</th><th>Logout Time</th>
                        </tr>
                        <?php 
                            $all = $conn->query("SELECT * FROM user");
                            $sn = 1;
                            $login_time = "";
                            $logout_time = "";
                            while($row = $all->fetch_assoc()):
                               if($row['logintime'] > 0) {
                                    include('login_timestamp.php');
                                    $login_time = date('D, jS  M Y - H:i:s', $Login_timestamp);
                               } else {
                                    $login_time = "";
                               }
                               if($row['logouttime'] > 0) {
                                    include('logout_timestamp.php');
                                    $logout_time = date('D, jS  M Y - H:i:s', $Logout_timestamp);
                               } else {
                                    $logout_time = "";
                               }
                        ?>
                        <tr>
                            <td><?=$sn++?></td><td><?=$row['firstname']?></td><td><?=$row['lastname']?></td><td><?=$row['email']?></td><td><?=$login_time?></td><td><?=$logout_time?></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
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