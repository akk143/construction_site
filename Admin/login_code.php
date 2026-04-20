<?php
include '../DB/connection.php';
session_start();

if(isset($_POST['minLogin']))
{
    $minEmail = $_POST['minEmail'];
    $minPwd = md5($_POST['minPwd']);

    if(!isset($_SESSION['fail_attempt']))
        $_SESSION['fail_attempt'] = 0;

    if(!isset($_SESSION['lock_time']))
        $_SESSION['lock_time'] = 0;

    // Check if locked
    if($_SESSION['fail_attempt'] >= 3)
    {
        $current_time = time();

        if(($current_time - $_SESSION['lock_time']) < 600)
        {
            echo "<script>alert('You failed 3 times. Try again after 10 minutes.');</script>";
            exit();
        }
        else
        {
            // Reset after 10 minutes
            $_SESSION['fail_attempt'] = 0;
            $_SESSION['lock_time'] = 0;
        }
    }

    // Check login
    $sql = "SELECT admin_ID, admin_email, admin_pwd 
            FROM Administrator_tbl 
            WHERE admin_email='$minEmail' 
            AND admin_pwd='$minPwd'";

    $result = mysqli_query($dbconid, $sql);

    if(mysqli_num_rows($result) == 1)
    {
        $row = mysqli_fetch_assoc($result);
        $_SESSION['minid'] = $row['admin_ID'];

        // Reset after success
        $_SESSION['fail_attempt'] = 0;
        $_SESSION['lock_time'] = 0;

        echo "<script>alert('Login success');window.location='index.php';</script>";
    }
    else
    {
        $_SESSION['fail_attempt']++;

        if($_SESSION['fail_attempt'] == 3)
        {
            $_SESSION['lock_time'] = time();
            echo "<script>alert('Failed 3 times! Locked for 10 minutes.');</script>";
        }
        else
        {
            echo "<script>alert('Wrong email or password! Attempt ".$_SESSION['fail_attempt']." of 3');</script>";
        }
    }
}
?>

<?php
session_start();
include_once("../DB/connection.php");

if(isset($_POST['minLogin']))
{
    $minEmail = $_POST['minEmail'];
    $minPwd = md5($_POST['minPwd']);

    if(isset($_SESSION['lock_time']))
{
    if(time() < $_SESSION['lock_time'])
    {
        $remain = ceil(($_SESSION['lock_time'] - time()) / 60);
        echo "<script>alert('Account locked! Try again after $remain minutes.');</script>";
    }
    else
    {
        // Unlock automatically
        unset($_SESSION['lock_time']);
        unset($_SESSION['try']);
    }
}
}
if(isset($_POST['login']))
{
    // If still locked, stop here
    if(isset($_SESSION['lock_time']) && time() < $_SESSION['lock_time'])
    {
        exit();
    }

    $email = mysqli_real_escape_string($dbconnect, $_POST['admin_email']);
    $password = md5($_POST['admin_pwd']);

    $sql = "SELECT * FROM Admin_tb 
            WHERE minEmail='$email' 
            AND minPwd='$password'";

    $result = mysqli_query($dbconnect, $sql);

    if(mysqli_num_rows($result) == 1)
    {
        $data = mysqli_fetch_assoc($result);
        $_SESSION['admin_id'] = $data['mid'];

        // Reset attempts after success
        unset($_SESSION['try']);
        unset($_SESSION['lock_time']);

        header("Location: dashboard.php");
        exit();
    }
    else
    {
        if(!isset($_SESSION['try']))
            $_SESSION['try'] = 1;
        else
            $_SESSION['try']++;

        if($_SESSION['try'] >= 3)
        {
            // Lock for 3 minutes (180 seconds)
            $_SESSION['lock_time'] = time() + 180;
            echo "<script>alert('You failed 3 times. Locked for 3 minutes.');</script>";
        }
        else
        {
            echo "<script>alert('Wrong email or password! Attempt ".$_SESSION['try']." of 3');</script>";
        }
    }
}
?>

