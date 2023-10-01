<?php
    session_start();
    include('inactivity.php');
    require('../conn/pdo.php');

    if(!isset($_SESSION['admin']) && !isset($_SESSION['hr'])) {
        header("Location: ../login.php");
    }

    if(isset($_SESSION['change'])) {
        header('location: changepassword.php');
    }

    $res = $conn->query("SELECT * FROM user WHERE userID = {$_SESSION['uid']}");
    $row = $res->fetch(PDO::FETCH_ASSOC);
    $fullname = $row['firstname']." ".$row['lastname'];


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
    <style>
        .suc {
            text-align: center;
            color: green;
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
                        <a class="active" href="uniform_app.php"><i class="bx bx-folder icon"></i> Uniform Applications</a>
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
                        <a href="uniform.php"><i class="bx bx-add-to-queue icon"></i>Uniforms</a>
                    </li>
                    
                    <li>
                        <a class="active" href="uniform_app.php"><i class="bx bx-folder icon"></i> Uniform Applications</a>
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
                <?php elseif(isset($_SESSION['supplier'])): ?>
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
                <?php if(isset($_SESSION['supplier'])): ?>
                        <h3 class="bread-cumb"><a href="index.php">Dashboard</a> > <a href="received.php">Received Uniforms</a> > All Dimensions</h3>
                <?php else: ?>
                        <h3 class="bread-cumb"><a href="index.php">Dashboard</a> > <a href="uniform_app.php">Uniform Applications</a> > All Dimensions</h3>
                <?php endif ?>
                <span><img src="../photos/<?=$row['image']?>"><?=$fullname?></span>
                <a href="logout.php?logout_id=<?=$row['userID']?>">Logout</a>
            </header>

            <div class="grid">
                <div class="uniform-app">
                    <form>
                        <h3>Uniform Dimensions</h3>
                        <?php
                            $usersDep = $conn->query("SELECT user.*, department.* FROM user INNER JOIN department ON user.deptID = department.deptID"); 
                            while($row = $usersDep->fetch(PDO::FETCH_ASSOC)):
                                $uniform = $conn->query("SELECT uniform.*, dimensionS.* FROM dimensionS INNER JOIN uniform ON uniform.unID = dimensions.uniformID WHERE year(app_date_time) = year(current_date()) AND dimensions.userID = {$row['userID']}");
                                while($row2 = $uniform->fetch(PDO::FETCH_ASSOC)):
                        ?> 
                        <div class="divider">
                            <div class="text">
                                <span class="innerText">
                                    Employee Details
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input">
                                <label>Full name</label>
                                <input value="<?=$row['firstname'].' '.$row['lastname']?>" readonly>
                            </div>
                            <div class="input">
                                <label>Gender</label>
                                <input value="<?=$row['gender']?>" readonly id="less">
                            </div>
                            <div class="input">
                                <label>Department</label>
                                <input value="<?=$row['deptName']?>" readonly id="less2">
                            </div>
                            <div class="input">
                                <label>Uniform Name</label>
                                <input value="<?=$row2['name']?>" readonly>
                            </div>
                        </div>
                        <div class="divider">
                            <div class="text">
                                <span class="innerText">Top Dimensions - Inches</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input">
                                <label>Neck</label>
                                <input value="<?=$row2['neck'] ?? ''?>" readonly>
                            </div>
                            <div class="input">
                                <label>Bust</label>
                                <input value="<?=$row2['bust'] ?? ''?>" readonly id="less">
                            </div>
                            <div class="input">
                                <label>Back</label>
                                <input value="<?=$row2['back'] ?? ''?>" readonly id="less2">
                            </div>
                            <div class="input">
                                <label>Shoulder</label>
                                <input value="<?=$row2['shoulder'] ?? ''?>" readonly id="less2">
                            </div>
                            <div class="input">
                                <label>Chest</label>
                                <input value="<?=$row2['chest'] ?? ''?>" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input">
                                <label>Sleeve</label>
                                <input value="<?=$row2['sleeve'] ?? ''?>" readonly>
                            </div>
                            <div class="input">
                                <label>Upper Arm</label>
                                <input value="<?=$row2['upper_arm'] ?? ''?>" readonly id="less">
                            </div>
                            <div class="input">
                                <label>Nape to Waist</label>
                                <input value="<?=$row2['nape_to_waist'] ?? ''?>" readonly id="less2">
                            </div>
                            <div class="input">
                                <label>Wrist</label>
                                <input value="<?=$row2['wrist'] ?? ''?>" readonly id="less2">
                            </div>
                            <div class="input">
                                <label>Shoulder to Waist</label>
                                <input value="<?=$row2['shoulder_to_waist'] ?? ''?>" readonly>
                            </div>
                        </div>
                        <div class="divider">
                            <div class="text">
                                <span class="innerText">Bottom Dimensions - Inches</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input">
                                <label>Waist</label>
                                <input value="<?=$row2['waist'] ?? ''?>" readonly>
                            </div>
                            <div class="input">
                                <label>Calf</label>
                                <input value="<?=$row2['waist'] ?? ''?>" readonly id="less">
                            </div>
                            <div class="input">
                                <label>Waist to Hip</label>
                                <input value="<?=$row2['waist_to_hip'] ?? ''?>" readonly id="less2">
                            </div>
                            <div class="input">
                                <label>Ankle</label>
                                <input value="<?=$row2['ankle'] ?? ''?>" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input">
                                <label>Outside Leg</label>
                                <input value="<?=$row2['outside_leg'] ?? ''?>" readonly>
                            </div>
                            <div class="input">
                                <label>Inside Leg</label>
                                <input value="<?=$row2['inside_leg'] ?? ''?>" readonly id="less">
                            </div>
                            <div class="input">
                                <label>Shoes Type</label>
                                <input value="<?=$row2['shoes_type'] ?? ''?>" readonly id="less2">
                            </div>
                            <div class="input">
                                <label>Shoes Size.</label>
                                <input value="<?=$row2['shoes_no'] ?? ''?>" readonly>
                            </div>
                        </div>
                        <div class="divider" style="margin:30px 0">
                            <div class="text">
                                <span class="innerText"> END </span>
                            </div>
                        </div>
                        <?php endwhile; endwhile?>
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