const form = document.getElementById('reg_form');
const messageBox = document.getElementById('formMessage');
const submitBtn = document.getElementById('submitBtn');

form.addEventListener('submit', function(e) {
    e.preventDefault();

    const password = document.getElementById('clientPwd').value;
    const confirm = document.getElementById('ConfirmPwd').value;

    // RESET MESSAGE
    messageBox.className = "form-message";
    messageBox.innerText = "";

    // VALIDATION RULES
    const rules = [
        { test: password.length >= 8, msg: "Password must be at least 8 characters!" },
        { test: /[A-Z]/.test(password), msg: "Must include uppercase letter!" },
        { test: /[a-z]/.test(password), msg: "Must include lowercase letter!" },
        { test: /[0-9]/.test(password), msg: "Must include a number!" },
        { test: /[!@#$%^&*]/.test(password), msg: "Must include a symbol (!@#$%^&*)!" },
        { test: password === confirm, msg: "Passwords do not match!" }
    ];

    for (let rule of rules) {
        if (!rule.test) {
            showMessage(rule.msg, "error");
            return;
        }
    }

    // LOADING
    submitBtn.disabled = true;
    const originalText = submitBtn.value;
    submitBtn.value = "Signing up...";

    const formData = new FormData(form);
    formData.append('userReg', '1');

    fetch('../DB/insert.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        if (data.status === "success") {
            showMessage(data.message, "success");
            setTimeout(() => window.location.href = "login.php", 1200);
        } else {
            resetButton(originalText);
            showMessage(data.message, "error");
        }
    })
    .catch(() => {
        resetButton(originalText);
        showMessage("Server error. Check insert.php response.", "error");
    });
});

function showMessage(msg, type) {
    messageBox.innerText = msg;
    messageBox.classList.add(type);

    setTimeout(() => {
        messageBox.style.opacity = "0";
        setTimeout(() => {
            messageBox.innerText = "";
            messageBox.className = "form-message";
            messageBox.style.opacity = "1";
        }, 400);
    }, 3000);
}

function resetButton(text) {
    submitBtn.disabled = false;
    submitBtn.value = text;
}

const steps = Array.from(document.querySelectorAll('.form-step'));
const indicators = Array.from(document.querySelectorAll('.stepper .step'));

const nextBtn = document.getElementById('nextBtn');
const prevBtn = document.getElementById('prevBtn');


let state = {
    step: 0,
    unlocked: [0],
    loading: false
};

init();

/* ======================
   INIT
====================== */
function init() {
    bindEvents();
    render();
}

/* ======================
   EVENTS
====================== */
function bindEvents() {

    nextBtn.addEventListener('click', () => {
        if (!validateStep(state.step)) return;

        unlock(state.step + 1);
        state.step++;
        render();
    });

    prevBtn.addEventListener('click', () => {
        state.step--;
        render();
    });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();

        if (!validateStep(state.step)) return;

        const pwd = pwdValue();
        const confirm = confirmValue();

        if (pwd !== confirm) {
            return showMessage("Passwords do not match", "error");
        }

        const strength = validatePassword(pwd);
        if (!strength.valid) return showMessage(strength.msg, "error");

        setLoading(true);

        try {
            const res = await fetch('../DB/insert.php', {
                method: 'POST',
                body: new FormData(form)
            });

            const data = await res.json();

            if (data.status === "success") {
                showMessage(data.message, "success");
                setTimeout(() => location.href = "login.php", 1200);
            } else {
                showMessage(data.message, "error");
                setLoading(false);
            }

        } catch {
            showMessage("Server error", "error");
            setLoading(false);
        }
    });
}

/* ======================
   RENDER
====================== */
function render() {

    steps.forEach((step, i) => {
        step.classList.toggle('active', i === state.step);
        step.classList.toggle('hidden', !state.unlocked.includes(i));
    });

    indicators.forEach((el, i) => {
        el.classList.remove('active', 'completed');

        if (i === state.step) el.classList.add('active');
        else if (i < state.step) el.classList.add('completed');
    });

    prevBtn.style.display = state.step === 0 ? 'none' : 'inline-flex';
    nextBtn.style.display = state.step === steps.length - 1 ? 'none' : 'inline-flex';
    submitBtn.style.display = state.step === steps.length - 1 ? 'inline-flex' : 'none';
}

/* ======================
   VALIDATION
====================== */
function validateStep(step) {
    const inputs = steps[step].querySelectorAll('input');

    for (const input of inputs) {

        if (input.type === "file" && input.required && !input.files.length) {
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

function validatePassword(pwd) {
    if (pwd.length < 8) return { valid: false, msg: "Min 8 characters" };
    if (!/[a-z]/.test(pwd)) return { valid: false, msg: "Add lowercase" };
    if (!/[A-Z]/.test(pwd)) return { valid: false, msg: "Add uppercase" };
    if (!/[0-9]/.test(pwd)) return { valid: false, msg: "Add number" };
    if (!/[!@#$%^&*(),.?\":{}|<>]/.test(pwd)) return { valid: false, msg: "Add special char" };
    return { valid: true };
}

/* ======================
   HELPERS
====================== */
function unlock(step) {
    if (!state.unlocked.includes(step)) {
        state.unlocked.push(step);
    }
}

function pwdValue() {
    return document.getElementById('pwd').value;
}

function confirmValue() {
    return document.getElementById('confirmPwd').value;
}

/* ======================
   UI
====================== */
function showMessage(msg, type) {
    messageBox.textContent = msg;
    messageBox.className = `form-message ${type}`;

    clearTimeout(messageBox.timer);
    messageBox.timer = setTimeout(() => {
        messageBox.textContent = "";
        messageBox.className = "form-message";
    }, 2500);
}

function setLoading(state) {
    submitBtn.disabled = state;
    nextBtn.disabled = state;
    prevBtn.disabled = state;
    submitBtn.textContent = state ? "Creating..." : "Create account";
}