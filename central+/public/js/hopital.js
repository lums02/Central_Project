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
            const priceMonthly = parseInt(this.dataset.priceMonthly);
            const priceYearly = parseInt(this.dataset.priceYearly);
            
            // Si c'est l'essai gratuit, rediriger directement
            if (plan === 'decouverte' || priceMonthly === 0) {
                Swal.fire({
                    title: 'Essai Gratuit',
                    html: `
                        <div class="text-start">
                            <p><strong>Plan :</strong> Plan Découverte</p>
                            <p><strong>Prix :</strong> Gratuit</p>
                            <p><strong>Durée :</strong> 30 jours</p>
                            <hr>
                            <p class="text-muted">Vous allez être redirigé vers la page d'inscription pour commencer votre essai gratuit.</p>
                        </div>
                    `,
                    icon: 'success',
                    showCancelButton: true,
                    confirmButtonColor: '#003366',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Commencer l\'essai',
                    cancelButtonText: 'Annuler'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Rediriger vers l'inscription avec les paramètres
                        const registerUrl = `/register?type=hopital&plan=decouverte&period=trial&payment_method=gratuit&amount=0`;
                        window.location.href = registerUrl;
                    }
                });
                return;
            }
            
            // Pour les plans payants, afficher le modal de paiement
            const card = this.closest('.pricing-card');
            const activePeriod = card.querySelector('.price-option.active') ? card.querySelector('.price-option.active').dataset.period : 'monthly';
            
            selectedPlan = plan;
            selectedPeriod = activePeriod;
            
            // Mettre à jour la modal
            document.getElementById('planName').textContent = plan.charAt(0).toUpperCase() + plan.slice(1);
            document.getElementById('planPrice').textContent = activePeriod === 'yearly' ? `$${priceYearly}/an` : `$${priceMonthly}/mois`;
            document.getElementById('totalPrice').textContent = activePeriod === 'yearly' ? `$${priceYearly}` : `$${priceMonthly}`;
            
            // Mettre à jour les montants dans les formulaires
            const amount = activePeriod === 'yearly' ? priceYearly : priceMonthly;
            document.getElementById('orangeAmount').value = `$${amount}`;
            document.getElementById('airtelAmount').value = `$${amount}`;
            document.getElementById('mpesaAmount').value = `$${amount}`;
            
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
    const passwordModal = new bootstrap.Modal(document.getElementById('orangePasswordModal'));
    passwordModal.show();
}

// Fonction : Confirmer paiement Orange
function confirmOrangePayment() {
    const password = document.getElementById('orangePassword').value;
    if (!password || password.length !== 4) {
        Swal.fire('Erreur', 'Veuillez entrer un code secret valide', 'error');
        return;
    }

    const passwordModal = bootstrap.Modal.getInstance(document.getElementById('orangePasswordModal'));
    passwordModal.hide();

    Swal.fire({
        title: 'Traitement en cours',
        html: '<div class="spinner-border text-primary mb-3"></div><p>Vérification du code secret...</p>',
        allowOutsideClick: false,
        showConfirmButton: false
    });

    setTimeout(() => {
        showPaymentSuccess('Orange Money');
    }, 2000);
}

// Fonction : Demander paiement Airtel
function requestAirtelPayment() {
    const airtelNumber = document.getElementById('airtelNumber').value;
    if (!airtelNumber) {
        Swal.fire('Erreur', 'Veuillez entrer votre numéro Airtel Money', 'error');
        return;
    }

    paymentModal.hide();
    const passwordModal = new bootstrap.Modal(document.getElementById('airtelPasswordModal'));
    passwordModal.show();
}

// Fonction : Confirmer paiement Airtel
function confirmAirtelPayment() {
    const password = document.getElementById('airtelPassword').value;
    if (!password || password.length !== 4) {
        Swal.fire('Erreur', 'Veuillez entrer un code secret valide', 'error');
        return;
    }

    const passwordModal = bootstrap.Modal.getInstance(document.getElementById('airtelPasswordModal'));
    passwordModal.hide();

    Swal.fire({
        title: 'Traitement en cours',
        html: '<div class="spinner-border text-primary mb-3"></div><p>Vérification du code secret...</p>',
        allowOutsideClick: false,
        showConfirmButton: false
    });

    setTimeout(() => {
        showPaymentSuccess('Airtel Money');
    }, 2000);
}

// Fonction : Demander paiement M-Pesa
function requestMpesaPayment() {
    const mpesaNumber = document.getElementById('mpesaNumber').value;
    if (!mpesaNumber) {
        Swal.fire('Erreur', 'Veuillez entrer votre numéro M-Pesa', 'error');
        return;
    }

    paymentModal.hide();
    const passwordModal = new bootstrap.Modal(document.getElementById('mpesaPasswordModal'));
    passwordModal.show();
}

// Fonction : Confirmer paiement M-Pesa
function confirmMpesaPayment() {
    const password = document.getElementById('mpesaPassword').value;
    if (!password || password.length !== 4) {
        Swal.fire('Erreur', 'Veuillez entrer un code secret valide', 'error');
        return;
    }

    const passwordModal = bootstrap.Modal.getInstance(document.getElementById('mpesaPasswordModal'));
    passwordModal.hide();

    Swal.fire({
        title: 'Traitement en cours',
        html: '<div class="spinner-border text-primary mb-3"></div><p>Vérification du code secret...</p>',
        allowOutsideClick: false,
        showConfirmButton: false
    });

    setTimeout(() => {
        showPaymentSuccess('M-Pesa');
    }, 2000);
}

// Fonction : Afficher succès paiement
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
    const registerUrl = `/register?type=hopital&plan=${selectedPlan}&period=${selectedPeriod}&payment_method=${paymentMethod}&amount=${amount}`;
    window.location.href = registerUrl;
}

// Rendre les fonctions globales pour compatibilité
window.selectPayment = selectPayment;
window.processCardPayment = processCardPayment;
window.requestOrangePayment = requestOrangePayment;
window.confirmOrangePayment = confirmOrangePayment;
window.requestAirtelPayment = requestAirtelPayment;
window.confirmAirtelPayment = confirmAirtelPayment;
window.requestMpesaPayment = requestMpesaPayment;
window.confirmMpesaPayment = confirmMpesaPayment;

