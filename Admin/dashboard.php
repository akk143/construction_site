<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="dashboard">
    <section class="admin-header">
        <h1>Lotus Skyline Construction</h1>
        <h3>Welcome to Admin Dashboard</h3>
    </section>
    <section class="form-section">
        <div class="center">
            <button id="show-register">Register</button>
            <button id="show-login">Login</button>
        </div>
        <!-- Login Popup -->
    <div class="popup" id="login-popup">
    <div class="close-btn">&times;</div>
    <div class="form">
        <h2>Login</h2>
        <form action="" method="POST">
            <div class="form-element">
            <label>Email</label>
            <input type="email" name="minEmail" id="memail" placeholder="Enter your email">
        </div>
        <div class="form-element">
            <label>Password</label>
            <input type="password" name="minPwd" id="mPwd" placeholder="Enter password">
        </div>
        <div class="form-element">
           <input type="submit" name="minLogin" id="mLogin" value="Sign in">
        </div>
        <div class="form-element">
            <p>If you don't have an account,
                <a href="#" id="go-register">Create account</a>
            </p>
        </div>
        </form>
    </div>
</div>

<?php
session_start();
include_once("../DB/connection.php");

if(!isset($_SESSION['fail_attempt']))
    $_SESSION['fail_attempt'] = 0;

if(!isset($_SESSION['lock_time']))
    $_SESSION['lock_time'] = 0;

if($_SESSION['lock_time'] > time())
{
    $remain= ($_SESSION['lock_time'] - time()) / 60;
    echo "<script>alert('Your account is locked. Please try again after " . round($remain, 2) . " minutes.');</script>";
}
else
{
    if($_SESSION['lock_time'] != 0 && $_SESSION['lock_time'] <= time())
    {
        $_SESSION['fail_attempt'] = 0;
        $_SESSION['lock_time'] = 0;
    }

    if(isset($_POST['minLogin']))
    {
        $minEmail = $_POST['minEmail'];
        $minPwd = md5($_POST['minPwd']);

        $sql = "SELECT * FROM Administrator_tbl 
                WHERE admin_email='$minEmail' 
                AND admin_pwd ='$minPwd'";

        $result = mysqli_query($dbconid, $sql);

        if(mysqli_num_rows($result) == 1)
        {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['admin_id'] = $row['admin_ID'];

            $_SESSION['fail_attempt'] = 0;
            $_SESSION['lock_time'] = 0;

            header("Location: index.php");
            exit();
        }
        else
        {
            $_SESSION['fail_attempt']++;

            if($_SESSION['fail_attempt'] >= 3)
            {
                $_SESSION['lock_time'] = time() + 180;
                echo "<script>alert('Failed 3 times! Locked for 3 minutes.');</script>";
            }
            else
            {
                echo "<script>alert('Wrong email or password! Attempt ".$_SESSION['fail_attempt']." of 3');</script>";
            }
        }
    }
}
?>
<!-- Register Popup -->
<div class="popup" id="register-popup">
    <div class="close-btn">&times;</div>
    <div class="form">
        <h2>Register</h2>
        <form action="../DB/insert.php" method="post" enctype="multipart/form-data">
            <div class="form-element">
            <label>Name</label>
            <input type="text" name="minName" placeholder="Enter your name" required>
        </div>
        <div class="form-element">
            <label>Email</label>
            <input type="email" name="minEmail" placeholder="Enter your email" required>
        </div>
        <div class="form-element">
            <label>Phone Number</label>
            <input type="tel" name="minPhNo" placeholder="Enter your phone number" required>
        </div>
        <div class="form-element">
            <label>Address</label>
            <input type="text" name="minAddress" placeholder="Enter your address" required>
        </div>
        <div class="form-element">
            <label>Profile</label>
            <input type="file" name="minImg" accept="../img/*" required>
        </div>
        <div class="form-element">
            <label>Password</label>
            <input type="password" name="minPwd" id="minPwd" placeholder="Enter password" required>
        </div>
        <div class="form-element">
            <label>Confirm Password</label>
            <input type="password" name="minRe-pwd" id="minRe-pwd" placeholder="Confirm password" required>
        </div>
        <div class="form-element">
           <input type="submit" value="Submit" name="minRegister">
        </div>
        <div class="form-element">
            <p>Already have an account?
                <a href="#" id="go-login">Login</a>
            </p>
        </div>
        </form>
    </div>
</div>       
    </section>
    <script src="script.js"></script>
</body>
</html>

