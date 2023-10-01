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

    $fullname = $row['firstname']." ".$row['lastname'];

    $dep = $conn->query("SELECT * FROM department");

    $user = $conn->query("SELECT * FROM user WHERE userID = '{$_REQUEST['id']}'");
    $userRow = $user->fetch(PDO::FETCH_ASSOC);

    $errors = ['firstname'=>'','lastname'=>'','email'=>'','pass'=>''];
    $success = ['suc'=>''];

    if(isset($_POST['update'])) {
        $firstname = trim($_POST['firstname']);
        $lastname = trim($_POST['lastname']);
        $email = trim($_POST['email']);
        $utype = $_POST['usertype'] ?? '';
        $department = $_POST['department'] ?? '';
        date_default_timezone_set('Africa/Nairobi');
        $date_time = date('Y-m-d H:i:s');
        $phone = $_POST['phone'];
        $gender = $_POST['gender'] ?? '';

        if(empty($firstname)) {
            $errors['firstname'] = "Firstname is required";
        } else {
            if(!preg_match("/^[a-zA-Z']{3,30}$/", $firstname)) {
                $errors['firstname'] = "Firstname is not valid name";
            }
        }

        if(empty($lastname)) {
            $errors['lastname'] = "Lastname is required";
        } else {
            if(!preg_match("/^[a-zA-Z']{3,30}$/", $lastname)) {
                $errors['lastname'] = "Lastname is not valid name";
            }
        }

        if(empty($phone)) {
            $errors['phone'] = "Phone number is required";
        } else {
            if(!preg_match("/^[0-9]{10}$/", $phone)) {
                $errors['phone'] = "Please enter a valid number";
            }
        }

        if(empty($gender)) {
            $errors['gender'] = "Gender is required";
        }

        $stmt = $conn->prepare("SELECT email FROM user WHERE email = :email AND userID <> :userID");
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':userID', $_REQUEST['id'], PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->rowCount();

        if(empty($email)) {
            $errors['email'] = "Email is required";
        } else {
            if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = "Email is not valid";
            } elseif($row > 0) {
                $errors['email'] = "Email already exists! Choose another one";
            }
        }

        if(empty($_FILES['image']['name'])) {
            $errors['img'] = "An image is required";
        } else {
            $img_name = $_FILES['image']['name'];
            $tmp_name = $_FILES['image']['tmp_name'];
            $allowed_extensions = ['png','jpg','webp','jpeg'];

            $img_explode = explode('.', $img_name);
            $img_extension = strtolower(end($img_explode));

            if(!in_array($img_extension, $allowed_extensions) === true) {
                $errors['img'] = "Please choose a valid image";
            }
        }


        if(empty($utype)) {
            $errors['utype'] = "User type is required";
        }


        if($utype == 'Employee') {
            if(empty($department)) {
                $errors['dep'] = "Department is required";
            }
        }

        
        if(!array_filter($errors)) {
            $new_img_name = time().$img_name;

            $stmt = $conn->prepare("UPDATE user SET firstname = :firstname, lastname = :lastname, email = :email, phone = :phone, gender = :gender, usertype = :utype, updated_at = :updated_at, image = :image, deptID = :deptID WHERE userID = :userID");
            $stmt->bindValue(':firstname', $firstname, PDO::PARAM_STR);
            $stmt->bindValue(':lastname', $lastname, PDO::PARAM_STR);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->bindValue(':phone', $phone, PDO::PARAM_STR);
            $stmt->bindValue(':gender', $gender, PDO::PARAM_STR);
            $stmt->bindValue(':utype', $utype, PDO::PARAM_STR);
            $stmt->bindValue(':updated_at', $date_time, PDO::PARAM_STR);
            $stmt->bindValue(':image', $new_img_name, PDO::PARAM_STR);
            $stmt->bindValue(':deptID', $department, PDO::PARAM_STR);
            $stmt->bindValue(':userID', $_REQUEST['id'], PDO::PARAM_INT);

            if($stmt->execute()) {
                unlink('../photos/'.$userRow['image']);
                move_uploaded_file($tmp_name, '../photos/'.$new_img_name);
                $success['suc'] = "User has been updated successfully";
            }
        }
    }

    $res = $conn->query("SELECT * FROM user WHERE userID = '{$_SESSION['uid']}'");
    $row = $res->fetch(PDO::FETCH_ASSOC);

    $user = $conn->query("SELECT * FROM user WHERE userID = '{$_REQUEST['id']}'");
    $userRow = $user->fetch(PDO::FETCH_ASSOC);
    

    switch($userRow['usertype']) {
        case 'Admin':
            $admin ="selected";
            $emp = "";
            $sup = "";
            break;
        case 'Employee':
            $admin = "";
            $emp = "selected";
            $sup = "";
            break;
        case 'Supplier':
            $admin = "";
            $emp = "";
            $sup = "selected";
            break;
        default:
            $admin = "";
            $emp = "";
            $sup = "";
            break;
    }

    switch($userRow['gender']) {
        case 'male':
            $male = "selected";
            $female = "";
            break;
        case 'female':
            $male = "";
            $female = "selected";
            break;
        default:
            $male = "";
            $female = "";
            break;
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit user | UMS</title>
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
                <h3 class="bread-cumb"><a href="index.php">Dashboard</a> > <a href="users.php">Users</a> > <?=$userRow['firstname']." ".$userRow['lastname']?></h3>
                <span><img src="../photos/<?=$row['image']?>"><?=$fullname?></span>
                <a href="logout.php?logout_id=<?=$row['userID']?>">Logout</a>
            </header>

            <div class="grid">
                <div class="user-add">
                    <form action="" class="regform" method="post" enctype="multipart/form-data" autocomplete="off">
                    <h3>Edit User</h3>
                        <div class="row">
                            <div class="input">
                                <label>Firstname</label>
                                <input type="text" name="firstname" value="<?=$userRow['firstname'] ?? ''?>">
                                <div class="err"><?=$errors['firstname']?></div>
                            </div>
                            <div class="input">
                                <label>Lastname</label>
                                <input type="text" name="lastname" value="<?=$userRow['lastname'] ?? ''?>">
                                <div class="err"><?=$errors['lastname']?></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input">
                                <label>Email</label>
                                <input type="text" name="email" value="<?=$userRow['email'] ?? ''?>">
                                <div class="err"><?=$errors['email']?></div>
                            </div>
                            <div class="input">
                                <label>Image</label>
                                <input type="file" name="image">
                                <div class="err"><?=$errors['img'] ?? ''?></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input">
                                <label>Phone Number</label>
                                <input type="text" name="phone" value="<?=$userRow['phone'] ?? ''?>">
                                <div class="err"><?=$errors['phone'] ?? ''?></div>
                            </div>
                            <div class="input">
                                <label>Gender</label>
                                <select name="gender">
                                    <option disabled selected>Select gender..</option>
                                    <option value="male" <?=$male?>>Male</option>
                                    <option value="female" <?=$female?>>Female</option>
                                </select>
                                <div class="err"><?=$errors['gender'] ?? ''?></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input">
                                <label>Department</label>
                                <select name="department">
                                    <option selected disabled>Select department...</option>
                                    <?php if($dep->rowCount() > 0): while($row = $dep->fetch(PDO::FETCH_ASSOC)):?>
                                        <option value="<?=$row['deptID']?>"><?=$row['deptName']?></option>
                                    <?php endwhile; endif; ?>
                                </select>
                                <div class="err"><?=$errors['dep'] ?? ''?></div>
                            </div>
                            <div class="input">
                                <label>Usertype</label>
                                <select name="usertype">
                                    <option selected disabled>Select user type</option>
                                    <option <?=$admin ?? ''?> value="Admin">Admin</option>
                                    <option <?=$emp ?? ''?> value="Employee">Employee</option>
                                    <option <?=$sup ?? ''?> value="Supplier">Supplier</option>
                                </select>
                                <div class="err"><?=$errors['utype'] ?? '' ?></div>
                            </div>
                        </div>
                        <input type="submit" class="create-btn" value="Update" name="update">
                        <div class="suc"><?=$success['suc']?></div>
                    </form>
                </div>

                <div class="pic">
                    <img src="../photos/<?=$userRow['image']?>" alt="<?=$userRow['firstname']?>'s image">
                </div>
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