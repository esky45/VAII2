// Description: This file contains the functions used for the signup page.
function checkPasswordStrength() {
    var password = document.getElementById("signupPasswordModal").value;
    var strengthMessage = document.getElementById("passwordStrengthMessage");

    var regex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}$/;

    if (regex.test(password)) {
        strengthMessage.innerHTML = '<i class="text-success bi bi-check-circle"></i> Strong password!';
    } else {
        strengthMessage.innerHTML = '<i class="text-danger bi bi-exclamation-circle"></i> Weak password! (Must contain at least 8 characters, including numbers and both uppercase and lowercase letters)';
    }
}
