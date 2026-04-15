// Variables globales
let paymentModal;
let selectedPlan = '';
let selectedPeriod = '';

// Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', () => {
    const modalElement = document.getElementById('paymentModal');
    if (modalElement) {
        paymentModal = new bootstrap.Modal(modalElement);
    }

    // Toggles de prix
    document.querySelectorAll('.price-option').forEach(option => {
        option.addEventListener('click', function() {
            const toggle = this.parentElement;
            const card = toggle.closest('.pricing-card');
            
            toggle.querySelectorAll('.price-option').forEach(opt => opt.classList.remove('active'));
            this.classList.add('active');
            
            const period = this.dataset.period;
            const amountElement = card.querySelector('.amount');
            const periodElement = card.querySelector('.period');
            const savingsElement = card.querySelector('.yearly-savings');
            const button = card.querySelector('.start-subscription');
            
            if (period === 'yearly') {
                const yearlyPrice = parseInt(button.dataset.priceYearly);
                amountElement.textContent = yearlyPrice;
                periodElement.textContent = '/an';
                savingsElement.style.display = 'block';
            } else {
                const monthlyPrice = parseInt(button.dataset.priceMonthly);
                amountElement.textContent = monthlyPrice;
                periodElement.textContent = '/mois';
                savingsElement.style.display = 'none';
            }
        });
    });

    // Gestion des boutons d'abonnement
    document.querySelectorAll('.start-subscription').forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const plan = this.dataset.plan;
            const card = this.closest('.pricing-card');
            const activePeriod = card.querySelector('.price-option.active').dataset.period;
            const priceMonthly = parseInt(this.dataset.priceMonthly);
            const priceYearly = parseInt(this.dataset.priceYearly);
            
            selectedPlan = plan;
            selectedPeriod = activePeriod;
            
            // Mettre à jour la modal
            document.getElementById('planName').textContent = plan.charAt(0).toUpperCase() + plan.slice(1);
            document.getElementById('planPrice').textContent = activePeriod === 'yearly' ? `'$'${priceYearly}/an` : `'$'${priceMonthly}/mois`;
            document.getElementById('totalPrice').textContent = activePeriod === 'yearly' ? `'$'${priceYearly}` : `'$'${priceMonthly}`;
            
            // Mettre à jour les montants dans les formulaires
            const amount = activePeriod === 'yearly' ? priceYearly : priceMonthly;
            document.getElementById('orangeAmount').value = `€${amount}`;
            document.getElementById('airtelAmount').value = `'$'${amount}`;
            document.getElementById('mpesaAmount').value = `'$'${amount}`;
            
            paymentModal.show();
        });
    });
});

// Fonction : Choisir une méthode de paiement
function selectPayment(method) {
    document.querySelectorAll('.payment-method').forEach(el => el.classList.remove('selected'));
    event.currentTarget.classList.add('selected');

    document.querySelectorAll('.payment-form').forEach(form => form.style.display = 'none');
    const form = document.getElementById(method + 'Form');
    if (form) form.style.display = 'block';
}

// Fonction : Traiter paiement carte
function processCardPayment() {
    Swal.fire({
        title: 'Traitement en cours',
        html: '<div class="spinner-border text-primary mb-3"></div><p>Veuillez patienter...</p>',
        allowOutsideClick: false,
        showConfirmButton: false
    });

    setTimeout(() => {
        showPaymentSuccess('Carte bancaire');
    }, 3000);
}

// Fonction : Demander paiement Orange
function requestOrangePayment() {
    const orangeNumber = document.getElementById('orangeNumber').value;
    if (!orangeNumber) {
        Swal.fire('Erreur', 'Veuillez entrer votre numéro Orange Money', 'error');
        return;
    }

    paymentModal.hide();
    const orangePasswordModal = new bootstrap.Modal(document.getElementById('orangePasswordModal'));
    orangePasswordModal.show();

    setTimeout(() => {
        document.getElementById('orangePassword').focus();
    }, 500);
}

// Fonction : Confirmer paiement Orange
function confirmOrangePayment() {
    const password = document.getElementById('orangePassword').value;
    if (password.length !== 4) {
        Swal.fire('Erreur', 'Le code secret doit contenir 4 chiffres', 'error');
        return;
    }

    const orangePasswordModal = bootstrap.Modal.getInstance(document.getElementById('orangePasswordModal'));
    orangePasswordModal.hide();

    Swal.fire({
        title: 'Traitement en cours',
        html: '<div class="spinner-border text-warning mb-3"></div><p>Veuillez patienter...</p>',
        allowOutsideClick: false,
        showConfirmButton: false
    });

    setTimeout(() => {
        showOrangeReceipt();
    }, 3000);
}

// Fonction : Demander paiement Airtel
function requestAirtelPayment() {
    const airtelNumber = document.getElementById('airtelNumber').value;
    if (!airtelNumber) {
        Swal.fire('Erreur', 'Veuillez entrer votre numéro Airtel Money', 'error');
        return;
    }

    paymentModal.hide();
    const airtelPasswordModal = new bootstrap.Modal(document.getElementById('airtelPasswordModal'));
    airtelPasswordModal.show();
}

// Fonction : Confirmer paiement Airtel
function confirmAirtelPayment() {
    const password = document.getElementById('airtelPassword').value;
    if (password.length !== 4) {
        Swal.fire('Erreur', 'Le code secret doit contenir 4 chiffres', 'error');
        return;
    }

    const airtelPasswordModal = bootstrap.Modal.getInstance(document.getElementById('airtelPasswordModal'));
    airtelPasswordModal.hide();

    Swal.fire({
        title: 'Traitement en cours',
        html: '<div class="spinner-border text-danger mb-3"></div><p>Veuillez patienter...</p>',
        allowOutsideClick: false,
        showConfirmButton: false
    });

    setTimeout(() => {
        showAirtelReceipt();
    }, 3000);
}

// Fonction : Demander paiement M-Pesa
function requestMpesaPayment() {
    const mpesaNumber = document.getElementById('mpesaNumber').value;
    if (!mpesaNumber) {
        Swal.fire('Erreur', 'Veuillez entrer votre numéro M-Pesa', 'error');
        return;
    }

    paymentModal.hide();
    const mpesaPasswordModal = new bootstrap.Modal(document.getElementById('mpesaPasswordModal'));
    mpesaPasswordModal.show();
}

// Fonction : Confirmer paiement M-Pesa
function confirmMpesaPayment() {
    const password = document.getElementById('mpesaPassword').value;
    if (password.length !== 4) {
        Swal.fire('Erreur', 'Le code secret doit contenir 4 chiffres', 'error');
        return;
    }

    const mpesaPasswordModal = bootstrap.Modal.getInstance(document.getElementById('mpesaPasswordModal'));
    mpesaPasswordModal.hide();

    Swal.fire({
        title: 'Traitement en cours',
        html: '<div class="spinner-border text-success mb-3"></div><p>Veuillez patienter...</p>',
        allowOutsideClick: false,
        showConfirmButton: false
    });

    setTimeout(() => {
        showMpesaReceipt();
    }, 3000);
}

// Fonction : Afficher reçu Orange Money
function showOrangeReceipt() {
    const amount = document.getElementById('orangeAmount').value;
    const number = document.getElementById('orangeNumber').value;
    const date = new Date().toLocaleString();
    const transactionId = 'OM' + Math.random().toString(36).substr(2, 9).toUpperCase();

    Swal.fire({
        title: 'Paiement réussi !',
        html: `
            <div class="orange-money-receipt">
                <div class="receipt-header text-center mb-3">
                    <div class="orange-money-logo mb-2"><i class="fas fa-wallet"></i></div>
                    <h5>Orange Money</h5>
                    <p class="text-success">Transaction réussie</p>
                </div>
                <div class="receipt-details">
                    <div class="d-flex justify-content-between mb-2"><span>Numéro</span><strong>${number}</strong></div>
                    <div class="d-flex justify-content-between mb-2"><span>Montant</span><strong>${amount}</strong></div>
                    <div class="d-flex justify-content-between mb-2"><span>Date</span><strong>${date}</strong></div>
                    <div class="d-flex justify-content-between mb-2"><span>Transaction ID</span><strong>${transactionId}</strong></div>
                </div>
                <div class="receipt-footer mt-3">
                    <p class="mb-1 small">Un SMS de confirmation a été envoyé</p>
                    <small class="text-muted">Conservez ce reçu comme preuve</small>
                </div>
            </div>
        `,
        confirmButtonText: 'Accéder à mon espace',
        allowOutsideClick: false
    }).then(() => {
        redirectToRegister('orange-money');
    });
}

// Fonction : Afficher reçu Airtel Money
function showAirtelReceipt() {
    const amount = document.getElementById('airtelAmount').value;
    const number = document.getElementById('airtelNumber').value;
    const date = new Date().toLocaleString();
    const transactionId = 'AM' + Math.random().toString(36).substr(2, 9).toUpperCase();

    Swal.fire({
        title: 'Paiement réussi !',
        html: `
            <div class="airtel-money-receipt">
                <div class="receipt-header text-center mb-3">
                    <div class="airtel-money-logo mb-2"><i class="fas fa-mobile-alt"></i></div>
                    <h5>Airtel Money</h5>
                    <p class="text-success">Transaction réussie</p>
                </div>
                <div class="receipt-details">
                    <div class="d-flex justify-content-between mb-2"><span>Numéro</span><strong>${number}</strong></div>
                    <div class="d-flex justify-content-between mb-2"><span>Montant</span><strong>${amount}</strong></div>
                    <div class="d-flex justify-content-between mb-2"><span>Date</span><strong>${date}</strong></div>
                    <div class="d-flex justify-content-between mb-2"><span>Transaction ID</span><strong>${transactionId}</strong></div>
                </div>
            </div>
        `,
        confirmButtonText: 'Accéder à mon espace',
        allowOutsideClick: false
    }).then(() => {
        redirectToRegister('airtel-money');
    });
}

// Fonction : Afficher reçu M-Pesa
function showMpesaReceipt() {
    const amount = document.getElementById('mpesaAmount').value;
    const number = document.getElementById('mpesaNumber').value;
    const date = new Date().toLocaleString();
    const transactionId = 'MP' + Math.random().toString(36).substr(2, 9).toUpperCase();

    Swal.fire({
        title: 'Paiement réussi !',
        html: `
            <div class="mpesa-receipt">
                <div class="receipt-header text-center mb-3">
                    <div class="mpesa-logo mb-2"><i class="fas fa-mobile-alt"></i></div>
                    <h5>M-Pesa</h5>
                    <p class="text-success">Transaction réussie</p>
                </div>
                <div class="receipt-details">
                    <div class="d-flex justify-content-between mb-2"><span>Numéro</span><strong>${number}</strong></div>
                    <div class="d-flex justify-content-between mb-2"><span>Montant</span><strong>${amount}</strong></div>
                    <div class="d-flex justify-content-between mb-2"><span>Date</span><strong>${date}</strong></div>
                    <div class="d-flex justify-content-between mb-2"><span>Transaction ID</span><strong>${transactionId}</strong></div>
                </div>
            </div>
        `,
        confirmButtonText: 'Accéder à mon espace',
        allowOutsideClick: false
    }).then(() => {
        redirectToRegister('mpesa');
    });
}

// Fonction : Afficher succès paiement carte
function showPaymentSuccess(method) {
    const amount = document.getElementById('totalPrice').textContent;
    const date = new Date().toLocaleString();
    const transactionId = 'CB' + Math.random().toString(36).substr(2, 9).toUpperCase();

    Swal.fire({
        title: 'Paiement réussi !',
        html: `
            <div class="card-receipt">
                <div class="receipt-header text-center mb-3">
                    <div class="card-logo mb-2"><i class="fas fa-credit-card"></i></div>
                    <h5>${method}</h5>
                    <p class="text-success">Transaction réussie</p>
                </div>
                <div class="receipt-details">
                    <div class="d-flex justify-content-between mb-2"><span>Montant</span><strong>${amount}</strong></div>
                    <div class="d-flex justify-content-between mb-2"><span>Date</span><strong>${date}</strong></div>
                    <div class="d-flex justify-content-between mb-2"><span>Transaction ID</span><strong>${transactionId}</strong></div>
                </div>
            </div>
        `,
        confirmButtonText: 'Accéder à mon espace',
        allowOutsideClick: false
    }).then(() => {
        redirectToRegister('card');
    });
}

// Fonction : Rediriger vers l'inscription
function redirectToRegister(paymentMethod) {
    const amount = document.getElementById('totalPrice').textContent.replace('$', '');
    const registerUrl = `/register?type=pharmacie&plan=${selectedPlan}&period=${selectedPeriod}&payment_method=${paymentMethod}&amount=${amount}`;
    window.location.href = registerUrl;
}

// Scroll fluide vers la section pricing
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des liens d'ancrage
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
});

// Rendre les fonctions globales
window.selectPayment = selectPayment;
window.processCardPayment = processCardPayment;
window.requestOrangePayment = requestOrangePayment;
window.confirmOrangePayment = confirmOrangePayment;
window.requestAirtelPayment = requestAirtelPayment;
window.confirmAirtelPayment = confirmAirtelPayment;
window.requestMpesaPayment = requestMpesaPayment;
window.confirmMpesaPayment = confirmMpesaPayment;
