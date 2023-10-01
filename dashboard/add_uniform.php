<?php
    session_start();
    include('inactivity.php');
    require('../conn/pdo.php');

    if(!isset($_SESSION['admin'])) {
        header("Location: ../login.php");
    }

    if(isset($_SESSION['change'])) {
        header('location: changepassword.php');
    }

    $res = $conn->query("SELECT * FROM user WHERE userID = '{$_SESSION['uid']}'");

    $row = $res->fetch(PDO::FETCH_ASSOC);

    $errors = [];

    $fullname = $row['firstname']." ".$row['lastname'];

    $success = ['suc'=>''];

    if(isset($_POST['register'])) {

        $uniform = $_POST['uniform'];
        $department = $_POST['department'] ?? '';

        if(empty($uniform)) {
            $errors['uniform'] = "Uniform name is required";
        }  else {
            if(!preg_match("/^[a-zA-Z ]{2,}$/", $uniform)) {
                $errors['uniform'] = "Please choose a valid uniform name";
            } else {
                $stmt = $conn->prepare("SELECT * FROM uniform WHERE name = :name");
                $stmt->bindValue(':name', $uniform, PDO::PARAM_STR);
                $stmt->execute();
                if($stmt->rowCount() > 0) {
                    $errors['uniform'] = "Uniform already exists, Choose another name";
                }
            }
        }

        if(empty($department)) {
            $errors['dep'] = "Department is required";
        }
        

        if(!array_filter($errors)) {
            $stmt = $conn->prepare("INSERT INTO uniform(name, deptID) VALUES(:name, :deptID)");
            $stmt->bindValue(':name', $uniform, PDO::PARAM_STR);
            $stmt->bindValue(':deptID', $department, PDO::PARAM_INT);
            if($stmt->execute()) {
                $uniform = "";
                $success['suc'] = "Uniform has been added Successfully";
            }
        }
    }

    $res = $conn->query("SELECT * FROM user WHERE userID = '{$_SESSION['uid']}'");

    $row = $res->fetch(PDO::FETCH_ASSOC);
    
    $dep = $conn->query("SELECT * FROM department WHERE NOT EXISTS (SELECT * FROM uniform WHERE uniform.deptID = department.deptID)");

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Uniform | UMS</title>
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
                <?php endif; ?>
            </nav>
        </aside>
        <main>
            <header>
                <h3 class="bread-cumb"><a href="index.php">Dashboard</a> > <a href="uniform.php">Uniforms</a> > Add Uniform</h3>
                <span><img src="../photos/<?=$row['image']?>"><?=$fullname?></span>
                <a href="logout.php?logout_id=<?=$row['userID']?>">Logout</a>
            </header>

            <div class="grid">
                <div class="user-add" style="grid-column: span 5">
                    <form action="" class="regform" method="post" enctype="multipart/form-data" autocomplete="off">
                    <h3>Uniform Reg.</h3>
                        <div class="input">
                            <label>Uniform Name</label>
                            <input type="text" name="uniform" value="<?=$uniform ?? ''?>">
                            <div class="err"><?=$errors['uniform'] ?? ''?></div>
                        </div>
                        <div class="input">
                                <label>Department</label>
                                <select name="department" class="custom-select">
                                    <option selected disabled>Select department...</option>
                                    <?php if($dep->rowCount() > 0):  while($row = $dep->fetch(PDO::FETCH_ASSOC)): ?>
                                        <option value="<?=$row['deptID']?>"><?=$row['deptName']?></option>
                                    <?php endwhile; endif; ?>
                                </select>
                                <div class="err"><?=$errors['dep'] ?? ''?></div>
                            </div>
                        <input type="submit" class="create-btn" value="Register" name="register">
                        <div class="suc"><?=$success['suc']?></div>
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