<?php
session_start();
include '../DB/connection.php';
?>
    <section class="header-container">
        <section class="header-top">
            <div class="header-top-contact">
                <i class="bi bi-envelope-at-fill"></i>
                <p>lotusSkylineconstruction@gmail.com</p>
            </div>
        <?php                           
            include '../DB/connection.php';           
            if (isset($_SESSION['client_ID'])) {
                $client_ID=$_SESSION['client_ID'];

                $client_sql = "SELECT * FROM Client WHERE client_ID='$client_ID'";

                $sql_qry = mysqli_query($dbconid, $client_sql);

                while($user_qry=mysqli_fetch_assoc($sql_qry))
                {
                $clientImg="../Admin/imgUpload/".$user_qry['client_profile'];
                ?>
            <ul class="clientProfileContainer">
                <li class="clientProfile">
                    <div class="profileArea">
                        <img src="<?php echo $clientImg;?>">
                        <p><?php echo $user_qry['client_name'];?></p>
                    </div>
                    <ul class="profileSubmenu">
                        <li><a href="edit_profile.php?clientid=<?php echo $user_qry['client_ID'];?>"><i class="bi bi-person-circle"></i> Edit Profile</a></li>
                        <li><a href="#"><i class="bi bi-clock-history"></i> Service History</a></li>
                        <li><a href="#"><i class="bi bi-clock-history"></i> Property History</a></li>
                        <li><a href="invoice.php"><i class="bi bi-receipt"></i> Invoices</a></li>
                        <li><a href="#"><i class="bi bi-bookmark-check"></i> Your Save</a></li>
                        <li><a href="logout.php"><i class="bi bi-arrow-right-circle"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
            <?php
            } }
            else{
            ?>
            <div class="header-top-form">
                <a href="register.php" class="reg-form-popup">Register</a>
                <a href="login.php" class="login-form-popup">Login</a>
            </div>
            <?php 
            } ?>
        </section>
        <header>
            <div class="header-left">
                <img src="../img/main_logo.jpg" alt="">
                <h1>Lotus Skyline Construction</h1>
            </div>           
            <label for="menu-icon"><i class="bi bi-list"></i></label>
            <input type="checkbox" name="" id="menu-icon">
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.html">About Us</a></li>
                    <li><a href="service.html">Service</a></li>
                    <li><a href="property.php">Property</a></li>
                    <li><a href="#" class="dropdown-links">Project <i class="bi bi-caret-down-fill"></i></a>
                        <ul>
                            <li><a href="ongoingProj.html">Ongoing Projects</a></li>
                            <li><a href="completeProj.html">Completed Projects</a></li>
                        </ul>
                    </li>                
                    <li><a href="blog.html">Blog</a></li>
                    <li><a href="contact.html">Contact</a></li>
                </ul>
            </nav>
        </header>
    </section>
