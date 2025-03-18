document.addEventListener("DOMContentLoaded", function() {
    const signUpButton = document.getElementById('signUpButton');
    const signInButton = document.getElementById('signInButton');
    const signUpForm = document.getElementById('signup');
    const signInForm = document.getElementById('signIn');

    signUpButton.addEventListener('click', () => {
        signInForm.style.display = 'none';
        signUpForm.style.display = 'block';
    });

    signInButton.addEventListener('click', () => {
        signUpForm.style.display = 'none';
        signInForm.style.display = 'block';
    });
});