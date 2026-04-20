<?php
require "connection.php";
session_start();

/*
|--------------------------------------------------------------------------
| HELPER FUNCTION
|--------------------------------------------------------------------------
*/
function redirect($path) {
    header("Location: $path");
    exit();
}

function validate_password_strength($password) {
    if (strlen($password) < 8) {
        return 'Password must be at least 8 characters long.';
    }

    if (!preg_match('/[a-z]/', $password)) {
        return 'Password must contain at least one lowercase letter.';
    }

    if (!preg_match('/[A-Z]/', $password)) {
        return 'Password must contain at least one uppercase letter.';
    }

    if (!preg_match('/[0-9]/', $password)) {
        return 'Password must contain at least one number.';
    }

    if (!preg_match('/[!@#$%^&*()_+\-=[\]{};:\"\\|,.<>\/\?]/', $password)) {
        return 'Password must contain at least one special character.';
    }

    return '';
}

/*
|--------------------------------------------------------------------------
| CLIENT REGISTRATION
|--------------------------------------------------------------------------
*/
if(isset($_POST['userReg'])) {

    header('Content-Type: application/json');

    $name     = mysqli_real_escape_string($dbconid, $_POST['clientName']);
    $email    = mysqli_real_escape_string($dbconid, $_POST['clientEmail']);
    $phone    = mysqli_real_escape_string($dbconid, $_POST['clientTel']);
    $address  = mysqli_real_escape_string($dbconid, $_POST['clientAddress']);
    $password = $_POST['clientPwd'];
    $confirm  = $_POST['confirmPwd'];

    if($password !== $confirm){
        echo json_encode([
            "status" => "error",
            "message" => "Passwords do not match!"
        ]);
        exit();
    }

    $passwordError = validate_password_strength($password);
    if ($passwordError) {
        echo json_encode([
            "status" => "error",
            "message" => $passwordError
        ]);
        exit();
    }

    $password = md5($password);

    // IMAGE
    $imgName = $_FILES['clientImg']['name'];
    $imgTmp  = $_FILES['clientImg']['tmp_name'];

    $allowed = ['jpg','jpeg','png','gif'];
    $ext = strtolower(pathinfo($imgName, PATHINFO_EXTENSION));

    if(!in_array($ext, $allowed)){
        echo json_encode([
            "status" => "error",
            "message" => "Invalid image format!"
        ]);
        exit();
    }

    // CHECK EMAIL
    $check = mysqli_query($dbconid, "SELECT client_ID FROM Client WHERE client_email='$email'");
    if(mysqli_num_rows($check) > 0){
        echo json_encode([
            "status" => "error",
            "message" => "Email already exists!"
        ]);
        exit();
    }

    // INSERT
    $sql = "INSERT INTO Client 
            (client_name, client_email, client_phone, client_address, client_profile, client_pwd)
            VALUES ('$name','$email','$phone','$address','$imgName','$password')";

    if(mysqli_query($dbconid, $sql)){

        move_uploaded_file($imgTmp, "../Admin/imgUpload/$imgName");

        echo json_encode([
            "status" => "success",
            "message" => "Registration successful!"
        ]);

    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Database error!"
        ]);
    }

    exit();
}

/*
|--------------------------------------------------------------------------
| CLIENT LOGIN WITH LOCK SYSTEM
|--------------------------------------------------------------------------
*/
elseif(isset($_POST['userLogin'])) {

    $email = mysqli_real_escape_string($dbconid, $_POST['userEmail']);
    $password = md5($_POST['userPwd']);
    $currentTime = time();

    if(!isset($_SESSION['login'][$email])){
        $_SESSION['login'][$email] = [
            'fails' => 0,
            'lockTime' => 0
        ];
    }

    $fails = &$_SESSION['login'][$email]['fails'];
    $lockTime = &$_SESSION['login'][$email]['lockTime'];

    if($lockTime > $currentTime){
        $remaining = ceil(($lockTime - $currentTime) / 60);

        $_SESSION['error'] = "Locked. Try again in $remaining minute(s)";
        $_SESSION['old_email'] = $email;

        redirect("../User/login.php");
    }

    if($lockTime > 0 && $lockTime <= $currentTime){
        $fails = 0;
        $lockTime = 0;
    }

    $sql = "SELECT client_ID, client_pwd FROM Client WHERE client_email='$email'";
    $result = mysqli_query($dbconid, $sql);

    if($row = mysqli_fetch_assoc($result)){

        if($row['client_pwd'] === $password){

            $_SESSION['client_ID'] = $row['client_ID'];

            unset($_SESSION['login'][$email]);
            unset($_SESSION['error']);
            unset($_SESSION['old_email']);

            redirect("../User/index.php");

        } else {

            $fails++;

            if($fails >= 3){
                $lockTime = time() + (5 * 60);
                $_SESSION['error'] = "Too many attempts! Locked for 5 minutes";
            } else {
                $remaining = 3 - $fails;
                $_SESSION['error'] = "Wrong password! $remaining attempt(s) left";
            }

            $_SESSION['old_email'] = $email;

            redirect("../User/login.php");
        }

    } else {

        $_SESSION['error'] = "Email or Password is incorrect!";
        $_SESSION['old_email'] = $email;

        redirect("../User/login.php");
    }
}

/*
|--------------------------------------------------------------------------
| PROPERTY INSERT
|--------------------------------------------------------------------------
*/
elseif(isset($_POST['property'])) {

    $name  = mysqli_real_escape_string($dbconid, $_POST['pname']);
    $price = mysqli_real_escape_string($dbconid, $_POST['price']);

    $sql = "INSERT INTO Property(property_name, property_price)
            VALUES('$name','$price')";

    if(mysqli_query($dbconid, $sql)){
        $_SESSION['success'] = "Property added!";
        redirect("../Admin/property.php");
    } else {
        die(mysqli_error($dbconid));
    }
}

/*
|--------------------------------------------------------------------------
| DEFAULT
|--------------------------------------------------------------------------
*/
else {
    echo "Invalid request!";
}
?>