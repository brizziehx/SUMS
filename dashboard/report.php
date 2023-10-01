<?php
    session_start();
    include('inactivity.php');
    require('../conn/conn.php');

    //MONTHLY
    $feb = $conn->query("SELECT * FROM dimensions WHERE month(app_date_time) = 2 AND year(app_date_time) = year(current_date())");
    $jan = $conn->query("SELECT * FROM dimensions WHERE month(app_date_time) = 1 AND year(app_date_time) = year(current_date())");
    $mar = $conn->query("SELECT * FROM dimensions WHERE month(app_date_time) = 3 AND year(app_date_time) = year(current_date())");
    $apr = $conn->query("SELECT * FROM dimensions WHERE month(app_date_time) = 4 AND year(app_date_time) = year(current_date())");
    $may = $conn->query("SELECT * FROM dimensions WHERE month(app_date_time) = 5 AND year(app_date_time) = year(current_date())");
    $june = $conn->query("SELECT * FROM dimensions WHERE month(app_date_time) = 6 AND year(app_date_time) = year(current_date())");
    $july = $conn->query("SELECT * FROM dimensions WHERE month(app_date_time) = 7 AND year(app_date_time) = year(current_date())");
    $aug = $conn->query("SELECT * FROM dimensions WHERE month(app_date_time) = 8 AND year(app_date_time) = year(current_date())");
    $sep = $conn->query("SELECT * FROM dimensions WHERE month(app_date_time) = 9 AND year(app_date_time) = year(current_date())");
    $oct = $conn->query("SELECT * FROM dimensions WHERE month(app_date_time) = 10 AND year(app_date_time) = year(current_date())");
    $nov = $conn->query("SELECT * FROM dimensions WHERE month(app_date_time) = 11 AND year(app_date_time) = year(current_date())");
    $dec = $conn->query("SELECT * FROM dimensions WHERE month(app_date_time) = 12 AND year(app_date_time) = year(current_date())");


    if(!isset($_SESSION['admin']) && !isset($_SESSION['hr'])) {
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
    <title>Report | UMS</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="shortcut icon" href="../inc/fav.ico" type="image/x-icon">
    <link rel="stylesheet" href="../boxicons/css/boxicons.min.css">
    <script src="../table2excel/table2excel.js"></script>
    <style>
        .fc {
            grid-column: span 3;
        }

        .form-control {
            display: block;
            width: 100%;
            padding: .375rem .75rem;
            font-size: 1rem;
            line-height: 1.5;
            color: #495057;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid #ced4da;
            border-radius: .25rem;
            transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out
        }
    </style>
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
                        <a  href="uniform_app.php"><i class="bx bx-folder icon"></i> Uniform Applications</a>
                    </li>

                    <li>
                        <a class="active" href="feeds.php"><i class="bx bx-message-square-detail icon"></i>FeedBack</a>
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
                        <a href="uniform.php"><i class="bx bx-add-to-queue icon"></i>Uniforms</a>
                    </li>
                    
                    <li>
                        <a href="uniform_app.php"><i class="bx bx-folder icon"></i> Uniform Applications</a>
                    </li>

                    <li>
                        <a href="feeds.php"><i class="bx bx-message-square-detail icon"></i>FeedBack</a>
                    </li>

                    <li>
                        <a class="active" href="report.php"><i class="bx bx-file icon"></i>Report</a>
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
                <h3 class="bread-cumb"><a href="index.php">Dashboard</a> > Report</h3>
                <span><img src="../photos/<?=$row['image']?>"><?=$fullname?></span>
                <a href="logout.php?logout_id=<?=$row['userID']?>">Logout</a>
            </header>

            <div class="cards grid ucard">
                <div class="flex">
                    <h4>Monthly and Yearly Report</h4>
                    <div class="flex">
                        <a id="export" class="btn-add" style="margin-right: 10px;"><i class="bx bx-export" style="margin-right: 5px;"></i>Export to Excel</a>
                        <a href="report_all.php" class="btn-add"><i class="bx bx-printer" style="cursor:pointer; margin-right: 5px; font-size: 19px"></i>Print</a>
                    </div>
                </div>
                <table class="table-u" data-excel-name="Yearly Report">
                    <tbody>
                        <tr>
                        <th><a href="report_month.php?month=01">JAN</a></th><th><a href="report_month.php?month=02">FEB</a></th><th><a href="report_month.php?month=03">MAR</a></th><th><a href="report_month.php?month=04">APR</a></th><th><a href="report_month.php?month=05">MAY</a></th><th><a href="report_month.php?month=06">JUNE</a></th><th><a href="report_month.php?month=07">JULY</a></th><th><a href="report_month.php?month=08">AUG</a></th><th><a href="report_month.php?month=09">SEPT</a></th><th><a href="report_month.php?month=10">OCT</a></th><th><a href="report_month.php?month=11">NOV</a></th><th><a href="report_month.php?month=12">DEC</a></th>
                        </tr>
                        
                        <tr>
                            <td><?=$jan->num_rows?></td><td><?=$feb->num_rows?></td><td><?=$mar->num_rows?></td><td><?=$apr->num_rows?></td><td><?=$may->num_rows?></td><td><?=$june->num_rows?></td><td><?=$july->num_rows?></td><td><?=$aug->num_rows?></td><td><?=$sep->num_rows?></td><td><?=$oct->num_rows?></td><td><?=$nov->num_rows?></td><td><?=$dec->num_rows?></td>
                        </tr>
                    </tbody>
                </table>

                

                <div class="flex" style="border-top: 1px solid #eee;padding-top:10px"><h4>Individual Report</h4></div>
                    
                <div class="fc">
                  <input type="text" class="form-control" placeholder="Search users..." id="searchField">
                </div>
                <table class="table-u" id="myTable">
                    <tbody>
                        <tr>
                        <th>#</th><th>Firstname</th><th>Lastname</th><th>Email</th><th>Gender</th><th>Department</th><th>Print</th>
                        </tr>
                        <?php
                            $users = $conn->query("SELECT user.*, department.* FROM user INNER JOIN department ON user.deptID = department.deptID");
                            $sn = 1;
                            if($users->num_rows > 0):
                                while($row = $users->fetch_assoc()):
                        ?>
                        <tr>
                            <td><?=$sn++?></td><td><?=$row['firstname']?></td><td><?=$row['lastname']?></td><td><?=$row['email']?></td><td><?=$row['gender']?></td><td><?=$row['deptName']?></td><td><a href="print.php?id=<?=$row['userID']?>"><i class="bx bx-printer" style="cursor:pointer; color:blue; margin-right: 5px; font-size: 19px"></i></a></td>
                        </tr>
                        <?php endwhile; endif; ?>
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
                var searchBox_3 = document.getElementById("searchField");
                    searchBox_3.addEventListener("keyup",function(){
                    var keyword = this.value;
                    keyword = keyword.toUpperCase();
                    var table = document.getElementById("myTable");
                    var all_tr = table.getElementsByTagName("tr");
                    for(var i=0; i<all_tr.length; i++){
                            var all_columns = all_tr[i].getElementsByTagName("td");
                            for(j=0;j<all_columns.length; j++){
                                if(all_columns[j]){
                                    var column_value = all_columns[j].textContent || all_columns[j].innerText;
                                    
                                    column_value = column_value.toUpperCase();
                                    if(column_value.indexOf(keyword) > -1){
                                        all_tr[i].style.display = "";
                                        break;
                                    }else{
                                        all_tr[i].style.display = "none";
                                    }
                                }
                            }
                        }
                    });
            </script>
            
        </main>
    </div>
</body>
</html>