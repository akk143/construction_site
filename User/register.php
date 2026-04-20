<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register</title>

<link rel="stylesheet" href="user.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

<style>
.form-step { display: none; }
.form-step.active { display: block; }
.form-step.locked { display: none; }
</style>
</head>

<body>

<?php include 'header.php'; ?>

<section class="form-container">
<div class="form-card">

    <div class="form-intro">
        <span class="eyebrow">Join Lotus Skyline</span>
        <h2>Create a new account</h2>
        <p>Sign up to start viewing properties, making purchases, and tracking bookings.</p>
    </div>

    <div class="stepper">
        <div class="step active">Account</div>
        <div class="step">Profile</div>
        <div class="step">Security</div>
    </div>

    <form id="reg_form" enctype="multipart/form-data" novalidate>

        <!-- STEP 1 -->
        <div class="form-step active">
            <div class="form-grid">
                <input type="text" name="clientName" placeholder="Full name" required>
                <input type="email" name="clientEmail" placeholder="Email address" required>
            </div>
        </div>

        <!-- STEP 2 -->
        <div class="form-step">
            <div class="form-grid">
                <input type="text" name="clientAddress" placeholder="Address" required>
                <input type="tel" name="clientTel" placeholder="Phone number" required>
            </div>

            <input type="file" name="clientImg" accept="image/*" required>
        </div>

        <!-- STEP 3 -->
        <div class="form-step password-step">
            <div class="form-grid">
                <div class="form-input-box">
                    <input type="password" name="clientPwd" id="pwd" placeholder="Password" required>
                    <i class="bi bi-lock password"></i>
                    <i class="bi bi-eye-slash password-hide" id="registerPwdToggle"></i>
                </div>
                <div class="form-input-box">
                    <input type="password" name="confirmPwd" id="confirmPwd" placeholder="Confirm password" required>
                    <i class="bi bi-lock password"></i>
                    <i class="bi bi-eye-slash password-hide" id="registerConfirmToggle"></i>
                </div>
            </div>

            <div class="password-rules" id="pwdRules">
                <p id="rule-length">At least 8 characters</p>
                <p id="rule-lower">One lowercase letter</p>
                <p id="rule-upper">One uppercase letter</p>
                <p id="rule-number">One number</p>
                <p id="rule-special">One special character</p>
            </div>
        </div>

        <p id="formMessage"></p>

        <div class="step-actions">
            <button type="button" id="prevBtn">Back</button>
            <button type="button" id="nextBtn">Next</button>
            <button type="submit" id="submitBtn">Create account</button>
        </div>

    </form>
</div>
</section>

<?php include 'footer.php'; ?>

<script>
/* ======================
   STATE
====================== */
const form = document.getElementById('reg_form');
const steps = document.querySelectorAll('.form-step');
const indicators = document.querySelectorAll('.stepper .step');

const nextBtn = document.getElementById('nextBtn');
const prevBtn = document.getElementById('prevBtn');
const submitBtn = document.getElementById('submitBtn');
const messageBox = document.getElementById('formMessage');
const passwordField = document.getElementById('pwd');
const confirmField = document.getElementById('confirmPwd');
const passwordRules = {
    length: document.getElementById('rule-length'),
    lower: document.getElementById('rule-lower'),
    upper: document.getElementById('rule-upper'),
    number: document.getElementById('rule-number'),
    special: document.getElementById('rule-special')
};

let currentStep = 0;
let unlockedSteps = [0];

/* ======================
   INIT
====================== */
render();
updatePasswordRules(passwordField.value);

passwordField.addEventListener('input', () => updatePasswordRules(passwordField.value));

const registerPwdToggle = document.getElementById('registerPwdToggle');
const registerConfirmToggle = document.getElementById('registerConfirmToggle');

if (registerPwdToggle) {
    registerPwdToggle.addEventListener('click', () => {
        const isText = passwordField.type === 'text';
        passwordField.type = isText ? 'password' : 'text';
        registerPwdToggle.classList.toggle('bi-eye', !isText);
        registerPwdToggle.classList.toggle('bi-eye-slash', isText);
    });
}

if (registerConfirmToggle) {
    registerConfirmToggle.addEventListener('click', () => {
        const isText = confirmField.type === 'text';
        confirmField.type = isText ? 'password' : 'text';
        registerConfirmToggle.classList.toggle('bi-eye', !isText);
        registerConfirmToggle.classList.toggle('bi-eye-slash', isText);
    });
}

/* ======================
   EVENTS
====================== */
nextBtn.addEventListener('click', () => {
    if (!validateStep(currentStep)) return;

    unlockStep(currentStep + 1);
    currentStep++;
    render();
});

prevBtn.addEventListener('click', () => {
    currentStep--;
    render();
});

form.addEventListener('submit', async (e) => {
    e.preventDefault();

    if (!validateStep(currentStep)) return;

    const pwd = passwordField.value;
    const confirm = confirmField.value;

    if (pwd !== confirm) {
        showMessage("Passwords do not match", "error");
        return;
    }

    if (!validatePassword(pwd)) {
        showMessage("Password must meet all listed criteria", "error");
        return;
    }

    setLoading(true);

    try {
        const data = new FormData(form);
        data.append('userReg', '1');

        const res = await fetch('../DB/insert.php', {
            method: 'POST',
            body: data
        });

        const result = await res.json().catch(() => null);

        if (!result || result.status !== "success") {
            showMessage(result?.message || "Server error", "error");
            setLoading(false);
            return;
        }

        showMessage(result.message, "success");
        setTimeout(() => location.href = "login.php", 1200);

    } catch {
        showMessage("Server error", "error");
        setLoading(false);
    }
});

/* ======================
   STEP CONTROL
====================== */
function render() {

    steps.forEach((step, i) => {

        if (!unlockedSteps.includes(i)) {
            step.classList.remove('active');
            step.classList.add('locked');
            return;
        }

        step.classList.remove('locked');
        step.classList.toggle('active', i === currentStep);
    });

    indicators.forEach((el, i) => {
        el.classList.remove('active', 'completed');

        if (i === currentStep) el.classList.add('active');
        else if (i < currentStep) el.classList.add('completed');
    });

    prevBtn.style.display = currentStep === 0 ? 'none' : 'inline-block';
    nextBtn.style.display = currentStep === steps.length - 1 ? 'none' : 'inline-block';
    submitBtn.style.display = currentStep === steps.length - 1 ? 'inline-block' : 'none';
}

function unlockStep(step) {
    if (!unlockedSteps.includes(step)) {
        unlockedSteps.push(step);
    }
}

/* ======================
   VALIDATION
====================== */
function validateStep(stepIndex) {
    const inputs = steps[stepIndex].querySelectorAll('input');

    for (let input of inputs) {

        if (input.type === "file" && input.required && input.files.length === 0) {
            input.reportValidity();
            return false;
        }

        if (!input.checkValidity()) {
            input.reportValidity();
            return false;
        }
    }

    return true;
}

function validatePassword(password) {
    const checks = [
        { id: 'length', valid: password.length >= 8 },
        { id: 'lower', valid: /[a-z]/.test(password) },
        { id: 'upper', valid: /[A-Z]/.test(password) },
        { id: 'number', valid: /[0-9]/.test(password) },
        { id: 'special', valid: /[!@#$%^&*(),.?\":{}|<>]/.test(password) }
    ];

    let passed = true;
    checks.forEach(item => {
        updatePasswordRule(item.id, item.valid);
        if (!item.valid) passed = false;
    });

    return passed;
}

function updatePasswordRules(password) {
    validatePassword(password);
}

function updatePasswordRule(id, valid) {
    const rule = passwordRules[id];
    if (!rule) return;

    rule.classList.toggle('valid', valid);
    rule.classList.toggle('invalid', !valid);
}

/* ======================
   UI HELPERS
====================== */
function showMessage(msg, type) {
    messageBox.textContent = msg;
    messageBox.style.color = type === "success" ? "green" : "red";

    setTimeout(() => messageBox.textContent = "", 2500);
}

function setLoading(state) {
    nextBtn.disabled = state;
    prevBtn.disabled = state;
    submitBtn.disabled = state;
    submitBtn.textContent = state ? "Creating..." : "Create account";
}
</script>

</body>
</html>