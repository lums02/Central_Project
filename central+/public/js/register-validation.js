// Validation en temps réel pour le formulaire d'inscription

document.addEventListener('DOMContentLoaded', function() {
    const emailInput = document.getElementById('email');
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password_confirmation');
    const registerForm = document.getElementById('registerForm');

    // Validation Email
    if (emailInput) {
        emailInput.addEventListener('blur', function() {
            validateEmail(this);
        });

        emailInput.addEventListener('input', function() {
            clearError(this);
        });
    }

    // Validation Mot de passe
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            validatePassword(this);
        });
    }

    // Validation Confirmation mot de passe
    if (passwordConfirmInput) {
        passwordConfirmInput.addEventListener('input', function() {
            validatePasswordConfirmation();
        });
    }

    // Soumission du formulaire
    if (registerForm) {
        registerForm.addEventListener('submit', function(e) {
            let isValid = true;
            let errors = [];

            if (emailInput && !validateEmail(emailInput)) {
                isValid = false;
                errors.push('Email invalide ou mal formaté');
            }

            if (passwordInput && !validatePassword(passwordInput)) {
                isValid = false;
                errors.push('Mot de passe ne respecte pas les critères de sécurité');
            }

            if (passwordConfirmInput && !validatePasswordConfirmation()) {
                isValid = false;
                errors.push('Les mots de passe ne correspondent pas');
            }

            if (!isValid) {
                e.preventDefault();
                const errorMessage = 'Veuillez corriger les erreurs suivantes :\n\n' + 
                                   errors.map((err, index) => `${index + 1}. ${err}`).join('\n');
                showAlert(errorMessage, 'error');
            }
        });
    }
});

// Fonction de validation d'email
function validateEmail(input) {
    const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    const value = input.value.trim();

    clearError(input);

    if (value === '') {
        showError(input, 'L\'adresse email est obligatoire.');
        return false;
    }

    if (!emailRegex.test(value)) {
        showError(input, 'Format d\'email invalide. Exemple : votremail@gmail.com');
        return false;
    }

    // Vérifier les domaines courants
    const commonDomains = ['gmail.com', 'yahoo.com', 'hotmail.com', 'outlook.com', 'yahoo.fr'];
    const domain = value.split('@')[1];
    
    if (domain && !commonDomains.includes(domain.toLowerCase())) {
        showWarning(input, 'Êtes-vous sûr de votre adresse email ? Les domaines courants sont : gmail.com, yahoo.com, etc.');
    }

    showSuccess(input);
    return true;
}

// Fonction de validation de mot de passe
function validatePassword(input) {
    const value = input.value;
    const strengthIndicator = document.getElementById('password-strength');

    clearError(input);

    if (value === '') {
        showError(input, 'Le mot de passe est obligatoire.');
        updatePasswordStrength('', strengthIndicator);
        return false;
    }

    if (value.length < 8) {
        showError(input, 'Le mot de passe doit contenir au moins 8 caractères.');
        updatePasswordStrength('weak', strengthIndicator);
        return false;
    }

    const hasUpperCase = /[A-Z]/.test(value);
    const hasLowerCase = /[a-z]/.test(value);
    const hasNumbers = /\d/.test(value);
    const hasSpecialChar = /[@$!%*?&#]/.test(value);

    let strength = 'weak';
    let missingCriteria = [];

    if (!hasUpperCase) missingCriteria.push('1 majuscule');
    if (!hasLowerCase) missingCriteria.push('1 minuscule');
    if (!hasNumbers) missingCriteria.push('1 chiffre');
    if (!hasSpecialChar) missingCriteria.push('1 caractère spécial (@$!%*?&#)');

    if (missingCriteria.length > 0) {
        showError(input, 'Mot de passe faible. Il manque : ' + missingCriteria.join(', '));
        updatePasswordStrength('weak', strengthIndicator);
        return false;
    }

    if (value.length >= 12) {
        strength = 'strong';
    } else if (value.length >= 10) {
        strength = 'medium';
    }

    updatePasswordStrength(strength, strengthIndicator);
    showSuccess(input);
    return true;
}

// Fonction de validation de confirmation de mot de passe
function validatePasswordConfirmation() {
    const passwordInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');

    if (!confirmInput || !passwordInput) return true;

    clearError(confirmInput);

    if (confirmInput.value === '') {
        showError(confirmInput, 'Veuillez confirmer votre mot de passe.');
        return false;
    }

    if (confirmInput.value !== passwordInput.value) {
        showError(confirmInput, 'Les mots de passe ne correspondent pas.');
        return false;
    }

    showSuccess(confirmInput);
    return true;
}

// Mise à jour de l'indicateur de force du mot de passe
function updatePasswordStrength(strength, indicator) {
    if (!indicator) return;

    indicator.className = 'password-strength';
    
    if (strength === '') {
        indicator.innerHTML = '';
        return;
    }

    const strengthConfig = {
        weak: {
            text: 'Mot de passe faible',
            class: 'weak',
            color: '#dc3545'
        },
        medium: {
            text: 'Mot de passe moyen',
            class: 'medium',
            color: '#ffc107'
        },
        strong: {
            text: 'Mot de passe fort',
            class: 'strong',
            color: '#28a745'
        }
    };

    const config = strengthConfig[strength];
    indicator.className = 'password-strength ' + config.class;
    indicator.innerHTML = `<i class="fas fa-shield-alt"></i> ${config.text}`;
    indicator.style.color = config.color;
}

// Fonctions d'affichage des messages
function showError(input, message) {
    const formGroup = input.closest('.form-group') || input.parentElement;
    const errorDiv = document.createElement('div');
    errorDiv.className = 'invalid-feedback d-block';
    errorDiv.innerHTML = `<i class="fas fa-exclamation-circle"></i> ${message}`;
    
    input.classList.add('is-invalid');
    input.classList.remove('is-valid');
    
    formGroup.appendChild(errorDiv);
}

function showWarning(input, message) {
    const formGroup = input.closest('.form-group') || input.parentElement;
    const warningDiv = document.createElement('div');
    warningDiv.className = 'text-warning small mt-1';
    warningDiv.innerHTML = `<i class="fas fa-exclamation-triangle"></i> ${message}`;
    
    formGroup.appendChild(warningDiv);
}

function showSuccess(input) {
    input.classList.remove('is-invalid');
    input.classList.add('is-valid');
}

function clearError(input) {
    const formGroup = input.closest('.form-group') || input.parentElement;
    const feedback = formGroup.querySelector('.invalid-feedback');
    const warning = formGroup.querySelector('.text-warning');
    
    if (feedback) feedback.remove();
    if (warning) warning.remove();
    
    input.classList.remove('is-invalid', 'is-valid');
}

function showAlert(message, type) {
    if (typeof Swal !== 'undefined') {
        // Convertir le message avec retours à la ligne en HTML
        const htmlMessage = message.split('\n').map(line => {
            if (line.trim() === '') return '<br>';
            // Si c'est une ligne numérotée, la mettre en gras
            if (/^\d+\./.test(line.trim())) {
                return `<div style="text-align: left; margin: 8px 0; padding-left: 20px;">
                    <strong style="color: #dc3545;">✗</strong> ${line.trim().substring(2)}
                </div>`;
            }
            return `<div>${line}</div>`;
        }).join('');

        Swal.fire({
            title: type === 'error' ? '⚠️ Erreur de validation' : 'Attention',
            html: htmlMessage,
            icon: type,
            confirmButtonText: 'Corriger',
            confirmButtonColor: '#003366',
            customClass: {
                popup: 'swal-wide',
                title: 'swal-title-custom'
            }
        });
    } else {
        alert(message);
    }
}

