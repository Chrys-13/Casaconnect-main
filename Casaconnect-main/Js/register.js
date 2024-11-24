document.addEventListener('DOMContentLoaded', function () {
    // Input field references
    const inputs = {
        firstName: document.getElementById('signup-firstname'),
        lastName: document.getElementById('signup-lastname'),
        email: document.getElementById('signup-email'),
        password: document.getElementById('signup-password'),
        confirmPassword: document.getElementById('signup-confirm-password'),
    };
    const submitButton = document.querySelector('.btn');

    // Validators for input fields are defined in the form element below.
    const validators = {
        email: (value) => /\S+@\S+\.\S+/.test(value), // Valid email format string..
        password: (value) => /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/.test(value), // For strong password Mr. Sampah shouldn't have it easy Lol ....
    };

    // Disable fields and button initially, so as Mr. Sampah doesn't have a default button for input fields that are not required by the form element itself.
    Object.values(inputs).forEach((input) => (input.disabled = true));
    submitButton.disabled = true;

    /**
     * Generic function to enable or disable the next input based on validation.
     * @param {HTMLElement} currentInput - Current input element
     * @param {HTMLElement} nextInput - Next input element to enable/disable
     * @param {Function} [validator] - Validation function (optional)
     */
    function handleValidation(currentInput, nextInput, validator = () => true) {
        currentInput.addEventListener('input', function () {
            const isValid = currentInput.value.trim() && validator(currentInput.value);
            nextInput.disabled = !isValid;
            if (!isValid) {
                nextInput.value = ''; // Clear the next input if invalid
            }
        });
    }

    // Setup field validation chain
    handleValidation(inputs.firstName, inputs.lastName); // User First Name -> User Last Name
    handleValidation(inputs.lastName, inputs.email); // Last Name -> Email
    handleValidation(inputs.email, inputs.password, validators.email); // Email -> Password
    handleValidation(inputs.password, inputs.confirmPassword, validators.password); // Password -> Confirm Password

    // Confirm Password validation and button enabling
    inputs.confirmPassword.addEventListener('input', function () {
        const passwordsMatch = inputs.confirmPassword.value === inputs.password.value;
        const passwordIsValid = validators.password(inputs.password.value);
        submitButton.disabled = !(passwordsMatch && passwordIsValid);
    });
});
