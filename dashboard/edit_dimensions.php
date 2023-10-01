<?php
    session_start();
    include('inactivity.php');
    require('../conn/pdo.php');

    if(!isset($_SESSION['employee'])) {
        header("Location: ../login.php");
    }

    if(isset($_SESSION['change'])) {
        header('location: changepassword.php');
    }

    $stmt = $conn->prepare('SELECT * FROM dimensions WHERE dimID = :dimID');
    $stmt->bindValue(':dimID', $_REQUEST['id'], PDO::PARAM_INT);
    $stmt->execute();
    $dimRow = $stmt->fetch(PDO::FETCH_BOTH);


    $uniform = $conn->query("SELECT * FROM dimensions WHERE (userID = '{$_SESSION['uid']}' AND year(app_date_time) = year(current_date())) AND permission <> 1");
    if($uniform->rowCount() > 0) {
        header('location: apply_uniform.php');
        exit();
    }

    $res = $conn->query("SELECT * FROM user WHERE userID = '{$_SESSION['uid']}'");
    $row = $res->fetch(PDO::FETCH_ASSOC);
    $fullname = $row['firstname']." ".$row['lastname'];

    $errors = [];


    if(isset($_POST['submit'])) {
        date_default_timezone_set('Africa/Nairobi');

        $neck = trim($_POST['neck']);
        $bust = trim($_POST['bust']);
        $back = trim($_POST['back']);
        $shoulder = trim($_POST['shoulder']);
        $chest = trim($_POST['chest']);
        $sleeve = trim($_POST['sleeve']);
        $upperarm = trim($_POST['upperarm']);
        $napetowaist = trim($_POST['napetowaist']);
        $shouldertowaist = trim($_POST['shouldertowaist']);
        $wrist = trim($_POST['wrist']);
        $waist = trim($_POST['waist']);
        $calf = trim($_POST['calf']);
        $waisttohip = trim($_POST['waisttohip']);
        $ankle = trim($_POST['ankle']);
        $outsideleg = trim($_POST['outsideleg']);
        $insideleg = trim($_POST['insideleg']);
        $shoestype = trim($_POST['shoestype'] ?? '');
        $shoessize = trim($_POST['shoessize']);
        $app_date_time = date('Y-m-d H:i:s');
        $userID = $_SESSION['uid'];

        if(empty($neck)) {
            $errors['neck'] = "Neck size is required";
        } else {
            if(!preg_match('/^([\d]{1,2})(\.[\d]{1,2})?$/', $neck)) {
                $errors['neck'] = "Invalid neck size!";
            }
        }

        if(empty($bust)) {
            $errors['bust'] = "Bust size is required";
        } else {
            if(!preg_match('/^([\d]{1,2})(\.[\d]{1,2})?$/', $bust)) {
                $errors['bust'] = "Invalid bust size!";
            }
        }

        if(empty($back)) {
            $errors['back'] = "Back size is required";
        } else {
            if(!preg_match('/^([\d]{1,2})(\.[\d]{1,2})?$/', $back)) {
                $errors['back'] = "Invalid back size!";
            }
        }

        if(empty($shoulder)) {
            $errors['shoulder'] = "Shoulder size is required";
        } else {
            if(!preg_match('/^([\d]{1,2})(\.[\d]{1,2})?$/', $shoulder)) {
                $errors['shoulder'] = "Invalid shoulder size!";
            }
        }

        if(empty($chest)) {
            $errors['chest'] = "Chest size is required";
        } else {
            if(!preg_match('/^([\d]{1,2})(\.[\d]{1,2})?$/', $chest)) {
                $errors['chest'] = "Invalid chest size!";
            }
        }

        if(empty($sleeve)) {
            $errors['sleeve'] = "Sleeve size is required";
        } else {
            if(!preg_match('/^([\d]{1,2})(\.[\d]{1,2})?$/', $sleeve)) {
                $errors['sleeve'] = "Invalid sleeve size!";
            }
        }
        
        if(empty($upperarm)) {
            $errors['upperarm'] = "Upper arm size is required";
        } else {
            if(!preg_match('/^([\d]{1,2})(\.[\d]{1,2})?$/', $upperarm)) {
                $errors['upperarm'] = "Invalid upperarm size!";
            }
        }

        
        if(empty($napetowaist)) {
            $errors['nape2waist'] = "Nape to waist size is required";
        } else {
            if(!preg_match('/^([\d]{1,2})(\.[\d]{1,2})?$/', $napetowaist)) {
                $errors['nape2waist'] = "Invalid nape to waist size!";
            }
        }

        if(empty($shouldertowaist)) {
            $errors['shoulder2waist'] = "Shoulder to waist size is required";
        } else {
            if(!preg_match('/^([\d]{1,2})(\.[\d]{1,2})?$/', $shouldertowaist)) {
                $errors['shoulder2waist'] = "Invalid shoulder to waist size!";
            }
        }

        
        if(empty($wrist)) {
            $errors['wrist'] = "Wrist size is required";
        } else {
            if(!preg_match('/^([\d]{1,2})(\.[\d]{1,2})?$/', $wrist)) {
                $errors['wrist'] = "Invalid wrist size!";
            }
        }

        if(empty($waist)) {
            $errors['waist'] = "Waist size is required";
        } else {
            if(!preg_match('/^([\d]{1,2})(\.[\d]{1,2})?$/', $waist)) {
                $errors['waist'] = "Invalid waist size!";
            }
        }

        
        if(empty($calf)) {
            $errors['calf'] = "Calf size is required";
        } else {
            if(!preg_match('/^([\d]{1,2})(\.[\d]{1,2})?$/', $calf)) {
                $errors['calf'] = "Invalid calf size!";
            }
        }

        if(empty($waisttohip)) {
            $errors['waist2hip'] = "Waist to hip size is required";
        } else {
            if(!preg_match('/^([\d]{1,2})(\.[\d]{1,2})?$/', $waisttohip)) {
                $errors['waist2hip'] = "Invalid waist to hip size!";
            }
        }

        if(empty($ankle)) {
            $errors['ankle'] = "Ankle size is required";
        } else {
            if(!preg_match('/^([\d]{1,2})(\.[\d]{1,2})?$/', $ankle)) {
                $errors['ankle'] = "Invalid ankle size!";
            }
        }

        if(empty($outsideleg)) {
            $errors['outsideleg'] = "Outside leg size is required";
        } else {
            if(!preg_match('/^([\d]{1,2})(\.[\d]{1,2})?$/', $outsideleg)) {
                $errors['outsideleg'] = "Invalid outside leg size!";
            }
        }

        if(empty($insideleg)) {
            $errors['insideleg'] = "Inside leg size is required";
        } else {
            if(!preg_match('/^([\d]{1,2})(\.[\d]{1,2})?$/', $insideleg)) {
                $errors['insideleg'] = "Invalid inside leg size!";
            }
        }

        if(empty($shoessize)) {
            $errors['ssize'] = "Shoes size is required";
        } else {
            if(!preg_match('/^\d{2}$/', $shoessize)) {
                $errors['ssize'] = "Invalid shoes size!";
            }
        }

        if(!array_filter($errors)) {
            $res = $conn->query("SELECT * FROM user WHERE userID = '{$_SESSION['uid']}'");
            $row = $res->fetch(PDO::FETCH_ASSOC);
            $row2 = $conn->query("SELECT department.*, uniform.* FROM department INNER JOIN uniform ON department.deptID = uniform.deptID WHERE department.deptID = '{$row['deptID']}' ")->fetch(PDO::FETCH_ASSOC);
    
            
            $stmt = $conn->prepare('UPDATE dimensions SET neck = :neck,bust = :bust,back = :back,shoulder = :shoulder,chest = :chest,sleeve = :sleeve,upper_arm  = :upper_arm,nape_to_waist = :nape_to_waist,wrist = :wrist,shoulder_to_waist = :shoulder_to_waist,waist = :waist,calf = :calf,waist_to_hip = :waist_to_hip,ankle = :ankle,outside_leg = :outside_leg,inside_leg = :inside_leg,shoes_type = :shoes_type,shoes_no = :shoes_no,app_date_time = :app_date_time,uniformID = :uniformID,permission = :permission,userID = :userID,status = :status WHERE dimID = :dimID');
            $stmt->bindValue(':neck', $neck, PDO::PARAM_STR);
            $stmt->bindValue(':bust', $bust, PDO::PARAM_STR);
            $stmt->bindValue(':back', $back, PDO::PARAM_STR);
            $stmt->bindValue(':shoulder', $shoulder, PDO::PARAM_STR);
            $stmt->bindValue(':chest', $chest, PDO::PARAM_STR);
            $stmt->bindValue(':sleeve', $sleeve, PDO::PARAM_STR);
            $stmt->bindValue(':upper_arm', $upperarm, PDO::PARAM_STR);
            $stmt->bindValue(':nape_to_waist', $napetowaist, PDO::PARAM_STR);
            $stmt->bindValue(':wrist', $wrist, PDO::PARAM_STR);
            $stmt->bindValue(':shoulder_to_waist', $shouldertowaist, PDO::PARAM_STR);
            $stmt->bindValue(':waist', $waist, PDO::PARAM_STR);
            $stmt->bindValue(':calf', $calf, PDO::PARAM_STR);
            $stmt->bindValue(':waist_to_hip', $waisttohip, PDO::PARAM_STR);
            $stmt->bindValue(':ankle', $ankle, PDO::PARAM_STR);
            $stmt->bindValue(':outside_leg', $outsideleg, PDO::PARAM_STR);
            $stmt->bindValue(':inside_leg', $insideleg, PDO::PARAM_STR);
            $stmt->bindValue(':shoes_type', $shoestype, PDO::PARAM_STR);
            $stmt->bindValue(':shoes_no', $shoessize, PDO::PARAM_INT);
            $stmt->bindValue(':app_date_time', $app_date_time, PDO::PARAM_STR);
            $stmt->bindValue(':uniformID', $row2['unID'], PDO::PARAM_INT);
            $stmt->bindValue(':permission', 0, PDO::PARAM_INT);
            $stmt->bindValue(':userID', $userID, PDO::PARAM_INT);
            $stmt->bindValue(':status', 'pending', PDO::PARAM_STR);
            $stmt->bindValue(':dimID', $_REQUEST['id'], PDO::PARAM_INT);
            $stmt->execute();

            if($stmt->rowCount() > 0) {
                $_SESSION['msg'] = "<script>vt.success('Uniform has been Updated Successfully!', {duration: 3000, position: 'bottom-right'}); setTimeout(() => location.href = 'apply_uniform.php', 4000);</script>";
            }
        }
       

    }

    $res = $conn->query("SELECT * FROM user WHERE userID = '{$_SESSION['uid']}'");

    $row = $res->fetch(PDO::FETCH_ASSOC);

    $row2 = $conn->query("SELECT department.*, uniform.* FROM department INNER JOIN uniform ON department.deptID = uniform.deptID WHERE department.deptID = '{$row['deptID']}' ")->fetch(PDO::FETCH_ASSOC);
    
    
    $stmt = $conn->prepare("SELECT * FROM dimensions WHERE dimID = :dimID");
    $stmt->bindValue(':dimID', $_REQUEST['id'], PDO::PARAM_INT);
    $stmt->execute();
    $dimRow = $stmt->fetch(PDO::FETCH_BOTH);

    switch($dimRow['shoes_type']) {
        case 'Normail':
            $normal = "selected";
            $safe = "";
            break;
        case 'Safe Boots':
            $safe = "selected";
            $normal = "";
            break;
        default:
            $safe = "";
            $normal = "";
            break;
    }
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
                <?php if(isset($_SESSION['employee'])): ?>
                    <li>
                        <a href="index.php"><i class="bx bx-grid-alt icon"></i>Dashboard</a>
                    </li>

                    <li>
                        <a class="active" href="apply_uniform.php"><i class="bx bx-add-to-queue icon"></i> Uniform Application</a>
                    </li>

                    <li>
                        <a href="feeds.php"><i class="bx bx-message-square-detail  icon"></i>Notications</a>
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
                <h3 class="bread-cumb"><a href="index.php">Dashboard</a> > <a href="apply_uniform.php">Uniform Application</a> > Apply Uniform</h3>
                <span><img src="../photos/<?=$row['image']?>"><?=$fullname?></span>
                <a href="logout.php?logout_id=<?=$row['userID']?>">Logout</a>
            </header>

            <div class="grid">
                <div class="uniform-app">
                    <form action="" method="post">
                        <h3>Update Uniform Application</h3>
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
                                <input type="text" name="name" value="<?=$fullname?>" readonly>
                            </div>
                            <div class="input">
                                <label>Gender</label>
                                <input type="text" name="gen" value="<?=$row['gender']?>" readonly id="less">
                            </div>
                            <div class="input">
                                <label>Department</label>
                                <input type="text" name="department" value="<?=$row2['deptName']?>" readonly id="less2">
                            </div>
                            <div class="input">
                                <label>Uniform Name</label>
                                <input type="text" name="uname" value="<?=$row2['name']?>" readonly>
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
                                <input type="text" name="neck" value="<?=$dimRow['neck']?>" placeholder="">
                                <div class="err"><?=$errors['neck'] ?? ''?></div>
                            </div>
                            <div class="input">
                                <label>Bust</label>
                                <input type="text" name="bust" value="<?=$dimRow['bust']?>" placeholder="" id="less">
                                <div class="err"><?=$errors['bust'] ?? ''?></div>
                            </div>
                            <div class="input">
                                <label>Back</label>
                                <input type="text" name="back" value="<?=$dimRow['back']?>" placeholder="" id="less2">
                                <div class="err"><?=$errors['back'] ?? ''?></div>
                            </div>
                            <div class="input">
                                <label>Shoulder</label>
                                <input type="text" name="shoulder" value="<?=$dimRow['shoulder']?>" placeholder="" id="less2">
                                <div class="err"><?=$errors['shoulder'] ?? ''?></div>
                            </div>
                            <div class="input">
                                <label>Chest</label>
                                <input type="text" name="chest" value="<?=$dimRow['chest']?>" placeholder="">
                                <div class="err"><?=$errors['chest'] ?? ''?></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input">
                                <label>Sleeve</label>
                                <input type="text" name="sleeve" value="<?=$dimRow['sleeve']?>" placeholder="">
                                <div class="err"><?=$errors['sleeve'] ?? ''?></div>
                            </div>
                            <div class="input">
                                <label>Upper Arm</label>
                                <input type="text" name="upperarm" value="<?=$dimRow['upper_arm']?>" placeholder="" id="less">
                                <div class="err"><?=$errors['upperarm'] ?? ''?></div>
                            </div>
                            <div class="input">
                                <label>Nape to Waist</label>
                                <input type="text" name="napetowaist" value="<?=$dimRow['nape_to_waist']?>" placeholder="" id="less2">
                                <div class="err"><?=$errors['nape2waist'] ?? ''?></div>
                            </div>
                            <div class="input">
                                <label>Wrist</label>
                                <input type="text" name="wrist" value="<?=$dimRow['wrist']?>" placeholder="" id="less2">
                                <div class="err"><?=$errors['wrist'] ?? ''?></div>
                            </div>
                            <div class="input">
                                <label>Shoulder to Waist</label>
                                <input type="text" name="shouldertowaist" value="<?=$dimRow['shoulder_to_waist']?>" placeholder="">
                                <div class="err"><?=$errors['shoulder2waist'] ?? ''?></div>
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
                                <input type="text" name="waist" value="<?=$dimRow['waist']?>" placeholder="">
                                <div class="err"><?=$errors['waist'] ?? ''?></div>
                            </div>
                            <div class="input">
                                <label>Calf</label>
                                <input type="text" name="calf" value="<?=$dimRow['calf']?>" placeholder="" id="less">
                                <div class="err"><?=$errors['calf'] ?? ''?></div>
                            </div>
                            <div class="input">
                                <label>Waist to Hip</label>
                                <input type="text" name="waisttohip" value="<?=$dimRow['waist_to_hip']?>" placeholder="" id="less2">
                                <div class="err"><?=$errors['waist2hip'] ?? ''?></div>
                            </div>
                            <div class="input">
                                <label>Ankle</label>
                                <input type="text" name="ankle" value="<?=$dimRow['ankle']?>" placeholder="">
                                <div class="err"><?=$errors['ankle'] ?? ''?></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input">
                                <label>Outside Leg</label>
                                <input type="text" name="outsideleg" value="<?=$dimRow['outside_leg']?>" placeholder="">
                                <div class="err"><?=$errors['outsideleg'] ?? ''?></div>
                            </div>
                            <div class="input">
                                <label>Inside Leg</label>
                                <input type="text" name="insideleg" value="<?=$dimRow['inside_leg']?>" placeholder="" id="less">
                                <div class="err"><?=$errors['insideleg'] ?? ''?></div>
                            </div>
                            <div class="input">
                                <label>Shoes Type</label>
                                <!-- <input type="text" name="shoestype" placeholder="eg. Normal" id="less2"> -->
                                <select class="custom-select" name="shoestype" id="less2" style="border-radius: 0">
                                    <option disabled>Select shoes type..</option>
                                    <option value="Normal" <?=$normal?>>Normal</option>
                                    <option value="Safe Boots" <?=$safe?>>Safe Boots</option>
                                </select>
                                <div class="err"><?=$errors['stype'] ?? ''?></div>
                            </div>
                            <div class="input">
                                <label>Shoes Size.</label>
                                <input type="text" name="shoessize" value="<?=$dimRow['shoes_no']?>" placeholder="">
                                <div class="err"><?=$errors['ssize'] ?? ''?></div>
                            </div>
                        </div>
                        <div class="btn-sub">
                            <input type="submit" value="UPDATE" name="submit" class="create-btn">
                        </div>
                    </form>
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