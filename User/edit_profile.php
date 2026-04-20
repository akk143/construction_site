<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link rel="stylesheet" href="user.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>
    <?php include 'header.php'; ?>
       <!-- edit profile form -->
    <section class="form-container">     
        <div class="form reg-form">
            <form action="../DB/insert.php" method="POST" enctype="multipart/form-data" id="reg_form">
                <?php
                    include_once("../DB/connection.php");
                    if(isset($_GET['clientid'])){
                        $client_ID=$_GET['clientid'];
                        $client_sql = "SELECT * FROM Client WHERE client_ID='$client_ID'";
                        $sql_qry = mysqli_query($dbconid, $client_sql);
                        while($user_qry=mysqli_fetch_assoc($sql_qry))                                   
                        {
                            $clientImg="../Admin/imgUpload/".$user_qry['client_profile'];
                    ?> 
                <input type="hidden" name="clientID" value="<?php echo $user_qry['client_ID'];?>">
                <h2>Edit your profile</h2>
                <div class="reg-input">
                    <div class="form-input-box">
                        <input type="text" name="clientName" value="<?php echo $user_qry['client_name'];?>">
                        <i class="bi bi-person-fill name"></i>
                    </div>
                    <div class="form-input-box">
                        <input type="email" name="clientEmail" value="<?php echo $user_qry['client_email'];?>">
                        <i class="bi bi-envelope email"></i>
                    </div>
                </div>
                <div class="reg-input">
                    <div class="form-input-box">
                        <input type="text" name="clientAddress" value="<?php echo $user_qry['client_address'];?>">
                        <i class="bi bi-geo-alt address"></i>
                    </div>
                    <div class="form-input-box">
                        <input type="tel" name="clientTel" value="<?php echo $user_qry['client_phone'];?>">
                        <i class="bi bi-telephone phno"></i>
                    </div>
                </div>
                <div class="form-profile-box">
                    <label>Your Current Profile</label><br>
                    <img src="<?php echo $clientImg;?>">
                </div>
                <div class="form-input-upload-box" style="margin-bottom: 2.5rem;">
                    <label>Upload new profile</label>
                    <input type="file" name="clientImg" accept="../img/*">
                </div>       
                <span>Change Your Password</span>
                <div class="form-input-box">
                    <input type="password" name="oldPassword" placeholder="Enter your old password">
                    <i class="bi bi-lock password"></i>
                    <i class="bi bi-eye-slash password-hide"></i>
                </div>
                <div class="form-input-box">
                    <input type="password" name="newPassword" id="clientPwd" placeholder="Enter your new password">
                    <i class="bi bi-lock password"></i>
                    <i class="bi bi-eye-slash password-hide"></i>
                </div>
                <div class="form-input-box">
                    <input type="password" name="confirmNewPw" placeholder="Confirm your password">
                    <i class="bi bi-lock password"></i>
                    <i class="bi bi-eye-slash password-hide"></i>
                </div>      
                <span id="errorMsg"></span>  
                <div class="form-input-submit-box">
                    <input type="submit" value="Change" name="editProfile">
                </div> 
                <?php
                    } }
                ?>
            </form>
        </div>
    </section>
    <?php include 'footer.php'; ?>
    <script>
    document.getElementById('reg_form').addEventListener('submit', function(e) {

    var oldPw = document.querySelector('[name="oldPassword"]').value;
    var newPw = document.getElementById('clientPwd').value;
    var confirmPw = document.querySelector('[name="confirmNewPw"]').value;
    var error = document.getElementById('errorMsg');

    var upperChars = /[A-Z]/;
    var lowerChars = /[a-z]/;
    var numbers = /[0-9]/;
    var symbols = /[!@#$%^&*]/;

    if(oldPw !== "" || newPw !== "" || confirmPw !== "") {

        if(oldPw === "" || newPw === "" || confirmPw === ""){
            error.innerText = "Please fill all password fields!";
            e.preventDefault();
            return;
        }

        if(newPw.length < 8){
            error.innerText = 'Password must be at least 8 characters!';
            e.preventDefault();
            return;
        }

        if(!upperChars.test(newPw)){
            error.innerText = 'Password must have uppercase letter!';
            e.preventDefault();
            return;
        }

        if(!lowerChars.test(newPw)){
            error.innerText = 'Password must have lowercase letter!';
            e.preventDefault();
            return;
        }

        if(!numbers.test(newPw)){
            error.innerText = 'Password must have number!';
            e.preventDefault();
            return;
        }

        if(!symbols.test(newPw)){
            error.innerText = 'Password must have a symbol (!@#$%^&*)!';
            e.preventDefault();
            return;
        }

        if(newPw !== confirmPw){
            error.innerText = 'Passwords do not match!';
            e.preventDefault();
            return;
        }
    }
    });
    </script>
    <script src="user.js"></script>
</body>
</body>
</html>