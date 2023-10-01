<?php
session_start();

include('inactivity.php');
require('../conn/conn.php');

if(!isset($_SESSION['supplier'])) {
    header('location: ../login.php');
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Uniform Management System</title>
    <link rel="stylesheet" href="../css/print.css">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../boxicons/css/boxicons.min.css">
    <style>
        body {
            background: #fff;
        }

        form {
            margin-top: 30px;
        }
    </style>
</head>
<body>
    <div class="container-r">
        <div class="header-content print">
            <div class="divider">
                <h2>Staff Uniform Management System</h2>
                <div class="text">
                    <img src="../inc/SUMS.png" alt="">
                </div>
                <h3>Year <?=date('Y')?> - <?=date('Y')+1?> Uniform dimension</h3>
            </div>

            <div class="gird">
                <div class="uniform-app">
                    <?php
                        $uniform = $conn->query("SELECT dimensions.*, uniform.* FROM dimensions INNER JOIN uniform ON dimensions.uniformID = uniform.unID ORDER BY dimID DESC");
                        if($uniform->num_rows > 0):
                            while($dimRow = $uniform->fetch_assoc()):
                                $users = $conn->query("SELECT user.*, department.* FROM user INNER JOIN department ON department.deptID = user.deptID WHERE user.userID = {$dimRow['userID']}");
                                $row3 = $users->fetch_assoc();
                    ?>
                    <form action="" method="post" style="box-shadow:none">
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
                                <input value="<?=$row3['firstname'].' '.$row3['lastname']?>" readonly>
                            </div>
                            <div class="input">
                                <label>Gender</label>
                                <input value="<?=$row3['gender']?>" readonly id="less">
                            </div>
                            <div class="input">
                                <label>Department</label>
                                <input value="<?=$row3['deptName']?>" readonly id="less2">
                            </div>
                            <div class="input">
                                <label>Uniform Name</label>
                                <input value="<?=$dimRow['name']?>" readonly>
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
                                <input value="<?=$dimRow['neck'] ?? ''?>" readonly>
                            </div>
                            <div class="input">
                                <label>Bust</label>
                                <input value="<?=$dimRow['bust'] ?? ''?>" readonly id="less">
                            </div>
                            <div class="input">
                                <label>Back</label>
                                <input value="<?=$dimRow['back'] ?? ''?>" readonly id="less2">
                            </div>
                            <div class="input">
                                <label>Shoulder</label>
                                <input value="<?=$dimRow['shoulder'] ?? ''?>" readonly id="less2">
                            </div>
                            <div class="input">
                                <label>Chest</label>
                                <input value="<?=$dimRow['chest'] ?? ''?>" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input">
                                <label>Sleeve</label>
                                <input value="<?=$dimRow['sleeve'] ?? ''?>" readonly>
                            </div>
                            <div class="input">
                                <label>Upper Arm</label>
                                <input value="<?=$dimRow['upper_arm'] ?? ''?>" readonly id="less">
                            </div>
                            <div class="input">
                                <label>Nape to Waist</label>
                                <input value="<?=$dimRow['nape_to_waist'] ?? ''?>" readonly id="less2">
                            </div>
                            <div class="input">
                                <label>Wrist</label>
                                <input value="<?=$dimRow['wrist'] ?? ''?>" readonly id="less2">
                            </div>
                            <div class="input">
                                <label>Shoulder to Waist</label>
                                <input value="<?=$dimRow['shoulder_to_waist'] ?? ''?>" readonly>
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
                                <input value="<?=$dimRow['waist'] ?? ''?>" readonly>
                            </div>
                            <div class="input">
                                <label>Calf</label>
                                <input value="<?=$dimRow['waist'] ?? ''?>" readonly id="less">
                            </div>
                            <div class="input">
                                <label>Waist to Hip</label>
                                <input value="<?=$dimRow['waist_to_hip'] ?? ''?>" readonly id="less2">
                            </div>
                            <div class="input">
                                <label>Ankle</label>
                                <input value="<?=$dimRow['ankle'] ?? ''?>" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input">
                                <label>Outside Leg</label>
                                <input value="<?=$dimRow['outside_leg'] ?? ''?>" readonly>
                            </div>
                            <div class="input">
                                <label>Inside Leg</label>
                                <input value="<?=$dimRow['inside_leg'] ?? ''?>" readonly id="less">
                            </div>
                            <div class="input">
                                <label>Shoes Type</label>
                                <input value="<?=$dimRow['shoes_type'] ?? ''?>" readonly id="less2">
                            </div>
                            <div class="input">
                                <label>Shoes Size.</label>
                                <input value="<?=$dimRow['shoes_no'] ?? ''?>" readonly>
                            </div>
                        </div>
                        <div class="divider" style="margin-top: 30px;">
                            <div class="text">
                                <span class="innerText"> END </span>
                            </div>
                        </div>
                    </form>
                    <?php endwhile; endif?>
                </div>
            </div>
            
            <div class="hidden">
                <h4>Printed By: <?=$row['usertype']?> - <span><?php echo $fullname?></span></h4>
            </div>
        </div>
        <div class="buttons">
            <a href="received.php"><i class="bx bx-undo"></i>Go Back</a>
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