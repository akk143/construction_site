    <!-- client login code -->
    <?php
        include '../DB/connection.php';
        session_start();

        if(!isset($_SESSION['fail_attempt']))
            $_SESSION['fail_attempt'] = 0;

        if(!isset($_SESSION['lock_time']))
            $_SESSION['lock_time'] = 0;

        if(isset($_POST['userLogin']))
        {
            $userEmail = mysqli_real_escape_string($dbconid, $_POST['userEmail']);
            $userPwd = md5($_POST['userPwd']);

            if($_SESSION['fail_attempt'] >= 3)
            {
                $current_time = time();
                if($current_time < $lock_time)
                {
                    $_SESSION['login_error']="Account is locked. Try again after 10 minutes.";
                    header("Location: header.php");
                    exit();
                }
                else
                {
                    $_SESSION['fail_attempt'] = 0;
                    $_SESSION['lock_time'] = 0;
                }
            }

            $sql = "SELECT * FROM Client WHERE client_email='$userEmail' AND client_pwd ='$userPwd'";

            $result = mysqli_query($dbconid, $sql);

            if(mysqli_num_rows($result) == 1)
            {
                $row = mysqli_fetch_assoc($result);
                $_SESSION['client_id'] = $row['client_ID'];

                $_SESSION['fail_attempt'] = 0;
                $_SESSION['lock_time'] = 0;

                echo "<script>alert('Login success');</script>";
                header("Location: index.php");
                exit();
            }
            else
            {
                $_SESSION['fail_attempt']++;

                if($_SESSION['fail_attempt'] == 3)
                {
                    $_SESSION['lock_time'] = time() + 600;
                    $lock_time = $_SESSION['lock_time'];
                    $_SESSION['login_error'] = "Login failed 3 times! Locked for 10 minutes.";
                }
                else
                {
                    $_SESSION['login_error'] = "You enter wrong email or password! Failed attempts ".$_SESSION['fail_attempt']." of 3 times";
                }
                header("Location: header.php");
                exit();
            }
        } ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Header</title>
    <link rel="stylesheet" href="user.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<style>
    /* registration and login form */
.form-section{
    justify-content: center;
    align-items: center;
}
.form-container{
    max-width: 480px;
    width: 100%;
    /* position: absolute;
    top: 30%;
    left: 45%;
    z-index: 101; */
    /* transform: translate(-50%, -50%) scale(1.2);
    opacity: 0;
    pointer-events: none; */
    background: #E7EDF7;
    border-radius: 15px;
    padding: 27px;
    box-shadow: lightgray;
    /* transition: all 0.4s ease-out; */
}
/* .form-section.show .form-container{
    opacity: 1;
    pointer-events: auto;
    transform: translate(-50%, -50%) scale(1);
}
.form-container.active .reg-form{
    display: block;
}
.form-container.active .login-form{
    display: none;
}
.reg-form{
    display: none;
} */
/* .form-close-btn{
    position: absolute;
    top: 10px;
    right: 20px;
    font-size: 23px;
    color: #195395;
    cursor: pointer;
    opacity: 0.7; 
} */
.form-container h2{
    font-size: 25px;
    color: #335e8f;
    text-align: center;
}
.reg-input{
    display: flex;
}
.form-input-box, .form-input-upload-box{
    position: relative;
    width: 100%;
    height: 40px;
    margin-top: 29px;
}
.form-input-box input,
.form-input-upload-box input{
    width: 90%;
    height: 100%;
    border: none;
    outline: none;
    border-bottom: 2px solid #4775aa;
    color: #335e8f;
    background: #E7EDF7;
    padding: 0px 30px;
    transition: all 0.2s ease;
}
.form-input-upload-box input{
    border: none;
    padding-top: 10px;
}
.form-input-upload-box label{
    font-size: 17px;
    font-weight: 800;
    color: #335e8f;
}
.form-input-box i{
    font-size: 20px;
    position: absolute;
    top: 50%;
    transform: translateY(-50%);   
}
.form-input-box i.email,
.form-input-box i.password,
.form-input-box i.name,
.form-input-box i.address,
.form-input-box i.phno{
    left: 0;
    color: #335e8f;
}
.form-input-box i.password-hide{
    right: 0;
    font-size: 18px;
    color: #335e8f;
    cursor: pointer;
}
#errorMessage{
    color: red;
}
.form-input-submit-box{
    margin: 17px 0px;
    padding: 0.2rem 7rem;
}
.form-input-submit-box input{
    background: #d4dfee;;
    width: 100%;
    height: 2.2rem;
    color: #4a638d;
    font-size: 0.9rem;
    font-weight: bolder;
    border-radius: 10px;
    border: 1px solid #335e8f;
}
.form-input-submit-box input:hover{
    color: #fff;
    background-color: #6e88b5;
    cursor: pointer;
}
.form-link span{
    font-size: 16px;
    font-family: Bahnschrift;
    color: #335e8f;
}
.form-link span a{
    text-decoration: none;
}
.form-link span a:hover{
    text-decoration: underline;
}
@media (max-width: 1390px) {
    .form-container{ 
        top: 70%;
        left: 50%;
    }
}
@media (max-width: 600px) {
    .form-container{ 
        max-width: 430px;
    }
}
</style>
<body>
    <section class="header-container">
        <section class="header-top">
            <div class="header-top-contact">
                <i class="bi bi-envelope-at-fill"></i>
                <p>lotusSkylineconstruction@gmail.com</p>
            </div>
            <div class="header-top-form">
                <a href="register.php" class="reg-form-popup">Register</a>
                <a href="login.php" class="login-form-popup">Login</a>
            </div>
            <!-- <div class="clientProfile_container">
                <div class="client_profile">
                    <img src="../img/f9.jpg" alt="" class="client_img"> 
                    <p>Pyone Zon Phu</p>
                    <ul class="profile-submenu">
                        <li><a href="#">Edit Profile</a></li>
                        <li><a href="#">Service History</a></li>
                        <li><a href="#">Property History</a></li>
                        <li><a href="#">Wishlist</a></li>
                        <li><a href="#">Logout</a></li>
                    </ul>
                </div>
            </div> -->
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
                    <li><a href="index.html">Home</a></li>
                    <li><a href="about.html">About Us</a></li>
                    <li><a href="service.html">Service</a></li>
                    <li><a href="property.html">Property</a></li>
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
    <section class="form-section">
        <div class="form-container">
            <i class="bi bi-x-circle form-close-btn"></i>
            <!-- login-form -->
             <div class="form login-form">
                <form action="" method="POST">
                    <h2>Login</h2>
                    <div class="form-input-box">
                        <input type="email" name="userEmail" placeholder="Enter your email" required>
                        <i class="bi bi-envelope email"></i>
                    </div>
                    <div class="form-input-box">
                        <input type="password" name="userPwd" placeholder="Enter your password" required>
                        <i class="bi bi-lock password"></i>
                        <i class="bi bi-eye-slash password-hide"></i>
                    </div>
                    <span id="errorMessage"></span>
                    <div class="form-input-submit-box">
                        <input type="submit" value="Login" name="userLogin">
                    </div>
                    <div class="form-link">
                        <span>Don't have an account? <a href="#" id="signup">signup</a></span>
                    </div>
                </form>
            </div>
              <!-- register-form -->
            <div class="form reg-form">
                <form action="../DB/insert.php" method="POST" enctype="multipart/form-data" id="reg_form">
                    <h2>Signup</h2>
                    <div class="reg-input">
                        <div class="form-input-box">
                            <input type="text" name="clientName" placeholder="Enter your full name" required>
                            <i class="bi bi-person-fill name"></i>
                        </div>
                        <div class="form-input-box">
                            <input type="email" name="clientEmail" placeholder="Enter your email" required>
                            <i class="bi bi-envelope email"></i>
                        </div>
                    </div>
                    <div class="reg-input">
                        <div class="form-input-box">
                            <input type="text" name="clientAddress" placeholder="Enter your address" required>
                            <i class="bi bi-geo-alt address"></i>
                        </div>
                        <div class="form-input-box">
                            <input type="tel" name="clientTel" placeholder="Enter your phno" required>
                            <i class="bi bi-telephone phno"></i>
                        </div>
                    </div>
                    <div class="form-input-upload-box">
                        <label>Upload your profile</label>
                        <input type="file" name="clientImg" accept="../img/*" required>
                    </div>                  
                    <div class="form-input-box">
                        <input type="password" name="clientPwd" id="clientPwd" placeholder="Enter your password" required>
                        <i class="bi bi-lock password"></i>
                        <i class="bi bi-eye-slash password-hide"></i>
                    </div>
                    <div class="form-input-box">
                        <input type="password" id="ConfirmPwd" placeholder="Confirm your password" required>
                        <i class="bi bi-lock password"></i>
                        <i class="bi bi-eye-slash password-hide"></i>
                    </div>      
                    <span id="errorMsg"></span>  
                    <div class="form-input-submit-box">
                       <input type="submit" value="Signup" name="userReg">
                    </div>
                    <div class="form-link">
                        <span>Already have an account? <a href="#" id="login">Login</a></span>
                    </div>
                </form>
            </div>
        </div>
    </section> 
    <script src="user.js"></script>
    <?php 
    if(isset($_SESSION['login_error'])){ 
    ?>
    <script>
        window.addEventListener("DOMContentLoaded", function() {

        document.querySelector(".form-section").classList.add("show");
        document.querySelector(".form-container").classList.remove("active");
        document.getElementById("errorMessage").innerText = "<?php echo $_SESSION['login_error']; ?>";
    });
    </script>
    <?php 
    unset($_SESSION['login_error']);
    } 
    ?>
</body>
</html>