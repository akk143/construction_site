<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- KEEP YOUR CSS -->
    <link rel="stylesheet" href="user.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
</head>
<body>

<?php include 'header.php'; ?>

<section class="form-container">
    <div class="form-card">

        <form action="../DB/insert.php" method="POST">
            <h2>Login</h2>

            <div class="form-input-box">
                <input type="email" name="userEmail" placeholder="Enter your email"
                value="<?= $_SESSION['old_email'] ?? '' ?>" required>
                <i class="bi bi-envelope email"></i>
            </div>

            <div class="form-input-box">
                <input type="password" name="userPwd" placeholder="Enter your password" required>
                <i class="bi bi-lock password"></i>
                <i class="bi bi-eye-slash password-hide"></i>
            </div>
            
            <?php if(isset($_SESSION['error'])): ?>
                <div id="form-error" class="form-message error">
                    <i class="bi bi-exclamation-circle"></i>
                    <span><?= $_SESSION['error']; ?></span>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <div class="form-input-submit-box">
                <button type="submit" name="userLogin">Login</button>
            </div>

            <div class="form-link">
                <span>Don't have an account? <a href="register.php">Signup</a></span>
            </div>
        </form>
    </div>
</section>

<?php include 'footer.php'; ?>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const formError = document.getElementById("form-error");

        if (formError) {
            setTimeout(() => {
                formError.style.opacity = "0";
                formError.style.transform = "translateY(-10px)";

                setTimeout(() => {
                    formError.remove();
                }, 300);
            }, 3000);
        }

        const pwdToggle = document.querySelector('.password-hide');
        const pwdInput = document.querySelector('input[name="userPwd"]');

        if (pwdToggle && pwdInput) {
            pwdToggle.addEventListener('click', () => {
                const isText = pwdInput.type === 'text';
                pwdInput.type = isText ? 'password' : 'text';
                pwdToggle.classList.toggle('bi-eye', !isText);
                pwdToggle.classList.toggle('bi-eye-slash', isText);
            });
        }
    });
</script>

</body>
</html>