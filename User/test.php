  <!-- client login code -->
    <?php
        include '../DB/connection.php';
        session_start();

        $currentTime = time();

        if (isset($_SESSION['lock_time']) && $currentTime < $_SESSION['lock_time']) {
            echo "<script>alert('Your account is locked for 3 minutes!');</script>";   
        }
        else{
            if (isset($_SESSION['lock_time']) && $currentTime >= $_SESSION['lock_time']) {
                unset($_SESSION['lock_time']);
                unset($_SESSION['fail_attempt']);
            }

            if(isset($_POST['userLogin']))
            {
                $userEmail = mysqli_real_escape_string($dbconid, $_POST['userEmail']);
                $userPwd = md5($_POST['userPwd']);

                $sql = "SELECT client_ID, client_email, client_pwd FROM Client WHERE client_email='$userEmail' AND client_pwd ='$userPwd'";

                $result = mysqli_query($dbconid, $sql);

                if(mysqli_num_rows($result) == 1)
                {
                    $user = mysqli_fetch_assoc($result);               
                    $client_ID = $user['client_ID'];
                    $_SESSION['client_ID'] = $client_ID;

                    unset($_SESSION['lock_time']);
                    unset($_SESSION['fail_attempt']);
                    echo "<script>alert('Login success');window.location='index.php';</script>";
                }
                else
                { 
                    if (!isset($_SESSION['fail_attempt'])) {
                       $_SESSION['fail_attempt'] = 1;
                    } else {
                       $_SESSION['fail_attempt'] += 1;
                    }
                    
                    if($_SESSION['fail_attempt'] >= 3)
                    {
                        $_SESSION['lock_time'] = time() + 30;
                        echo "<script>alert('You failed login three times. Please try after 3 minutes.');</script>";
                    }
                    else
                    {
                        echo "<script>alert('You enter wrong email or password! Failed attempts: ".$_SESSION['fail_attempt']." of 3 times');</script>";
                    }
                } 
            } 
          }?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="user.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <?php include 'header.php'; ?>
    <section class="form-container">
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
                <div class="form-input-submit-box">
                    <input type="submit" value="Login" name="userLogin">
                </div>
                <div class="form-link">
                    <span>Don't have an account? <a href="register.php">signup</a></span>
                </div>
            </form>
        </div>
    </section> 
    <?php include 'footer.php'; ?>
    <script src="user.js"></script>
</body>
</html>