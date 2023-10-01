<?php
    session_start();
    include('inactivity.php');
    require('../conn/conn.php');

    if(!isset($_SESSION['supplier'])) {
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
    <title>Payments | UMS</title>
    <link rel="stylesheet" href="../css/style.css">
    <link rel="shortcut icon" href="../inc/fav.ico" type="image/x-icon">
    <link rel="stylesheet" href="../boxicons/css/boxicons.min.css">
    <script src="../swal/sweetalert2.min.js"></script>
    <link rel="stylesheet" href="../swal/sweetalert2.css">
    <style>
         .custom-select {
            border-radius: 0 !important;
        }
    </style>
</head>
<body>
        <script>
            function permissionON() {
                Swal.fire({
                    position: 'center',
                    icon: 'success',
                    title: 'Permission Granted',
                    showConfirmButton: false,
                    timer: 1500
                })
            }
            
        </script>
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
                <?php if(isset($_SESSION['supplier'])): ?>
                    <li>
                        <a href="index.php"><i class="bx bx-grid-alt icon"></i>Dashboard</a>
                    </li>

                    <li>
                        <a href="received.php"><i class="bx bx-add-to-queue icon"></i>Received Uniforms</a>
                    </li>

                    <li>
                        <a href="" class="active"><i class="bx bx-money icon"></i>Payments</a>
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
                <h3 class="bread-cumb"><a href="index.php">Dashboard</a> > <a href="payments.php">Payments</a> > Payment Form</h3>
                <span><img src="../photos/<?=$row['image']?>"><?=$fullname?></span>
                <a href="logout.php?logout_id=<?=$row['userID']?>">Logout</a>
            </header>

            <div class="grid">
                <div class="uniform-app">
                    <form action="" method="post">
                        <h3>Payment Form</h3>
                        <div class="divider">
                            <div class="text">
                                <span class="innerText">
                                    Supplier Details
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input">
                                <label>Full name</label>
                                <input value="<?=$fullname?>" readonly>
                            </div>
                            <div class="input">
                                <label>Gender</label>
                                <input value="<?=$row['gender']?>" readonly id="less">
                            </div>
                            <div class="input">
                                <label>Email Address</label>
                                <input value="<?=$row['email']?>" readonly id="less2">
                            </div>
                            <div class="input">
                                <label>Phone Number</label>
                                <input value="<?=$row['phone']?>" readonly>
                            </div>
                        </div>
                        <div class="divider">
                            <div class="text">
                                <span class="innerText">
                                    Payment Details
                                </span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input">
                                <label>Uniform Name</label>
                                <select name="uniform" class="custom-select">
                                    
                                </select>
                            </div>
                            <div class="input">
                                <label>Description</label>
                                <input name="description" id="less">
                            </div>
                            <div class="input">
                                <label>Amount</label>
                                <input name="amount"id="less2">
                            </div>
                            <div class="input">
                                <label>Quantity</label>
                                <input name="quantity">
                            </div>
                        </div>
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