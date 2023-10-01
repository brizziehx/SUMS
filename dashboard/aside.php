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

        <?php elseif(isset($_SESSION['supplier'])): ?>

        <?php endif; ?>
    </nav>
</aside>