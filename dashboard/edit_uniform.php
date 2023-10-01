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

    $uniforms = $conn->query("SELECT * FROM uniform WHERE unID = {$_GET['id']}");
    $uniRow = $uniforms->fetch(PDO::FETCH_BOTH);
    $success = ['suc'=>''];

    if(isset($_POST['update'])) {

        $uniform = $_POST['uniform'];

        if(empty($uniform)) {
            $errors['uniform'] = "Uniform name is required";
        }  else {
            if(!preg_match("/^[a-zA-Z ]{2,}$/", $uniform)) {
                $errors['uniform'] = "Please choose a valid uniform name";
            } else {
                $stmt = $conn->prepare("SELECT * FROM uniform WHERE name = :name AND unID <> {$_REQUEST['id']}");
                $stmt->bindValue(':name', $uniform, PDO::PARAM_STR);
                $stmt->execute();
                if($stmt->rowCount() > 0) {
                    $errors['uniform'] = "Uniform already exists, Choose another name";
                }
            }
        }   

        if(!array_filter($errors)) {
            $stmt = $conn->prepare("UPDATE uniform SET name =:name WHERE unID = :unID");
            $stmt->bindValue(':name', $uniform, PDO::PARAM_STR);
            $stmt->bindValue(':unID', $_REQUEST['id'], PDO::PARAM_INT);
            if($stmt->execute()) {
                $uniform = "";
                $success['suc'] = "Uniform has been updated Successfully";
            }
        }
    }

    $res = $conn->query("SELECT * FROM user WHERE userID = '{$_SESSION['uid']}'");

    $row = $res->fetch(PDO::FETCH_ASSOC);

    $uniforms = $conn->query("SELECT * FROM uniform WHERE unID = {$_GET['id']}");
    $uniRow = $uniforms->fetch(PDO::FETCH_BOTH);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Uniform | UMS</title>
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
            <h3 class="bread-cumb"><a href="index.php">Dashboard</a> > <a href="uniform.php">Uniforms</a> > Edit Uniform</h3>
                <span><img src="../photos/<?=$row['image']?>"><?=$fullname?></span>
                <a href="logout.php?logout_id=<?=$row['userID']?>">Logout</a>
            </header>

            <div class="grid">
                <div class="user-add" style="grid-column: span 5">
                    <form action="" class="regform" method="post" enctype="multipart/form-data" autocomplete="off">
                    <h3>Uniform Reg.</h3>
                        <div class="input">
                            <label>Uniform Name</label>
                            <input type="text" name="uniform" value="<?=$uniRow['name']?>">
                            <div class="err"><?=$errors['uniform'] ?? ''?></div>
                        </div>
                        
                        <input type="submit" class="create-btn" value="UPDATE" name="update">
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