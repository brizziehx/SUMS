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

    $errors = ['firstname'=>'','lastname'=>'','email'=>'','pass'=>''];
    $success = ['suc'=>''];

    $dep = $conn->query("SELECT * FROM department");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users | UMS</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="shortcut icon" href="../inc/fav.ico" type="image/x-icon">
    <link rel="stylesheet" href="../boxicons/css/boxicons.min.css">
    <script src="../js/jquery.min.js"></script>
    <script src="../js/table-sortable.js"></script>
    <script src="../table2excel/table2excel.js"></script>
    <link rel="stylesheet" href="../css/table-sortable.css">
    <!-- <link rel="stylesheet" href="bootstrap.min.css"> -->
    <style>
        .d-flex {
            display: flex;
        }
        .justify-content-end {
            justify-content: end;
        }
        .text {
            grid-column: 9 / span 2;
            display: flex;
            align-items: center;
        }
        .custom-select {
            display: inline-block;
            width: 100%;
            height: calc(2.25rem + 2px);
            padding: .375rem 1.75rem .375rem .75rem;
            line-height: 1.5;
            color: #495057;
            vertical-align: middle;
            background: #fff url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 4 5'%3E%3Cpath fill='%23343a40' d='M2 0L0 2h4zm0 5L0 3h4z'/%3E%3C/svg%3E") no-repeat right .75rem center;
            background-size: 8px 10px;
            border: 1px solid #ced4da;
            border-radius: .25rem;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none
        }

        .custom-select:focus {
            outline: 0;
        }

        .custom-select:focus::-ms-value {
            color: #495057;
            background-color: #fff
        }

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
                        <a class="active" href="users.php"><i class="bx bxs-user-detail icon"></i>Users</a>
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

                    <!-- <li>
                        <a href="#"><i class="bx bx-user-check icon"></i>Approved Uniforms</a>
                    </li>

                    <li>
                        <a href="#"><i class="bx bx-user-x icon"></i>Unapproved Uniforms</a>
                    </li> -->

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
                        <a href="index.php"><i class="bx bx-grid-alt icon"></i>Dashboard</a>
                    </li>

                    <li>
                        <a href="apply_uniform.php"><i class="bx bx-add-to-queue icon"></i> Uniform Application</a>
                    </li>

                    <li>
                        <a href="send.php"><i class="bx bx-message-square-detail  icon"></i>Notications</a>
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
                <h3 class="bread-cumb"><a href="index.php">Dashboard</a> > Users</h3>
                <span><img src="../photos/<?=$row['image']?>"><?=$fullname?></span>
                <a href="logout.php?logout_id=<?=$row['userID']?>">Logout</a>
            </header>


            <div class="cards grid ucard">
                <div class="flex">
                    <h4>All Users</h4>
                    <!-- <div class="search"><input type="text" onkeyup="myFunction()" id="search" placeholder="Search users"></div> -->
                    <div class="flex">
                        <a id="export" class="btn-add" style="margin-right: 10px;"><i class="bx bx-export" style="margin-right: 5px;"></i>Export to Excel</a>
                        <a href="newUser.php" class="btn-add"><i class="bx bx-user-plus" style="cursor:pointer; margin-right: 5px; font-size: 19px"></i>Add User</a>
                    </div>
                </div>
                <div class="fc">
                  <input type="text" class="form-control" placeholder="Search users..." id="searchField">
                </div>
                <table id="myTable" class="table-u" data-excel-name="All Users">
                    <tbody>
                        <tr>
                            <th>SN.</th><th>Firstname</th><th>Lastname</th><th>Email</th><th>Phone</th><th>Gender</th><th>User Type</th><th>Created At</th><th>Actions</th>
                        </tr>
                        <?php 
                            $all = $conn->query("SELECT * FROM user");
                            $sn = 1;
                            while($row = $all->fetch_assoc()):
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
                        <tr>
                            <td><?=$sn++?></td><td><?=$row['firstname']?></td><td><?=$row['lastname']?></td><td><?=$row['email']?></td><td><?=$row['phone']?></td><td><?=$row['gender']?></td><td><?=$row['usertype']?></td><td><?=date('jS M Y',$user_timestamp)?></td><td><a href="reset.php?id=<?=$row['userID']?>"><i class="bx bx-reset icon res"></i></a><a href="edit.php?id=<?=$row['userID']?>"><i class="bx bx-edit icon update"></i></a><a href="delete.php?id=<?=$row['userID']?>"><i class="bx bx-trash icon del"></i></a></td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>         

            <!-- <div class="footer">
                Copyright &copy; <?=date('Y')?>. All Righst Reserved.
            </div> -->

            <script>

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
                
                var table2excel = new Table2Excel();

                document.getElementById('export').addEventListener('click', function() {
                    table2excel.export(document.querySelectorAll('table'));
                });
            </script>
        </main>
    </div>
</body>
</html>