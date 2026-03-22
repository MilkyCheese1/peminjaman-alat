// Auth.js - Handling login and registration

document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');

    if (loginForm) {
        loginForm.addEventListener('submit', handleLogin);
    }

    if (registerForm) {
        registerForm.addEventListener('submit', handleRegister);
    }
});

async function handleLogin(e) {
    e.preventDefault();
    
    const username_or_email = document.getElementById('usernameOrEmail').value;
    const password = document.getElementById('password').value;
    const errorDiv = document.getElementById('errorMessage');
    const successDiv = document.getElementById('successMessage');

    // Clear messages
    errorDiv.textContent = '';
    successDiv.textContent = '';
    errorDiv.classList.remove('show');
    successDiv.classList.remove('show');

    try {
        const response = await fetch('/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value || ''
            },
            body: JSON.stringify({
                username_or_email,
                password
            })
        });

        const data = await response.json();

        if (data.success) {
            successDiv.textContent = data.message;
            successDiv.classList.add('show');
            setTimeout(() => {
                window.location.href = data.redirect || '/dashboard';
            }, 1500);
        } else {
            errorDiv.textContent = 'Error: ' + data.message;
            errorDiv.classList.add('show');
        }
    } catch (error) {
        console.error('Error:', error);
        errorDiv.textContent = 'Login failed: ' + error.message;
        errorDiv.classList.add('show');
    }
}

async function handleRegister(e) {
    e.preventDefault();
    
    const formData = {
        username: document.getElementById('username').value,
        email: document.getElementById('email').value,
        phone: document.getElementById('phone').value,
        alamat: document.getElementById('alamat').value,
        password: document.getElementById('password').value,
        password_confirmation: document.getElementById('password_confirmation').value
    };

    const errorDiv = document.getElementById('errorMessage');
    const successDiv = document.getElementById('successMessage');

    // Clear messages
    errorDiv.textContent = '';
    successDiv.textContent = '';
    errorDiv.classList.remove('show');
    successDiv.classList.remove('show');

    try {
        const response = await fetch('/api/register', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]')?.value || ''
            },
            body: JSON.stringify(formData)
        });

        const data = await response.json();

        if (data.success) {
            successDiv.textContent = data.message;
            successDiv.classList.add('show');
            setTimeout(() => {
                window.location.href = '/login';
            }, 1500);
        } else {
            errorDiv.textContent = 'Error: ' + data.message;
            errorDiv.classList.add('show');
        }
    } catch (error) {
        console.error('Error:', error);
        errorDiv.textContent = 'Registration failed: ' + error.message;
        errorDiv.classList.add('show');
    }
}
