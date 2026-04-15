// Variables globales
let paymentModal;
let selectedPlan = '';

// Initialisation au chargement de la page
document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM chargé, initialisation...');
    const modalElement = document.getElementById('paymentModal');
    if (modalElement) {
        paymentModal = new bootstrap.Modal(modalElement);
        console.log('Modal de paiement initialisé');
    } else {
        console.log('Modal de paiement non trouvé');
    }
});

// Fonction : Démarrer l'essai gratuit
function startTrial() {
    console.log('startTrial appelé');
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Commencer votre essai gratuit',
            html: 'Profitez de 30 jours gratuits pour tester toutes nos fonctionnalités !',
            icon: 'info',
            showCancelButton: true,
            confirmButtonText: 'Commencer',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                console.log('Redirection vers /register/hopital');
                window.location.href = '/register/hopital';
            }
        });
    } else {
        console.error('SweetAlert2 non chargé, utilisation d\'alert');
        alert('Commencer votre essai gratuit - Redirection vers /register/hopital');
        window.location.href = '/register/hopital';
    }
}

// Fonction : Choisir un plan
function selectPlan(plan) {
    console.log('selectPlan appelé avec:', plan);
    selectedPlan = plan;
    
    if (typeof bootstrap !== 'undefined' && paymentModal) {
        const planName = document.getElementById('planName');
        const planPrice = document.getElementById('planPrice');
        const totalPrice = document.getElementById('totalPrice');

        if (!planName || !planPrice || !totalPrice) {
            console.error('Éléments du modal non trouvés');
            return;
        }

        if (plan === 'monthly') {
            planName.textContent = 'Plan Mensuel';
            planPrice.textContent = '4.99$/mois';
            totalPrice.textContent = '4.99$';
        } else {
            planName.textContent = 'Plan Annuel';
            planPrice.textContent = '49$/an';
            totalPrice.textContent = '49$';
        }
        paymentModal.show();
    } else {
        console.error('Bootstrap ou modal non disponible, redirection directe');
        window.location.href = '/register/hopital';
    }
}

// Fonction : Choisir une méthode de paiement
function selectPayment(method, event = null) {
    console.log('selectPayment appelé avec:', method);
    document.querySelectorAll('.payment-method').forEach(el => el.classList.remove('selected'));
    if (event) event.currentTarget.classList.add('selected');

    document.querySelectorAll('.payment-form').forEach(form => form.style.display = 'none');
    const form = document.getElementById(method + 'Form');
    if (form) form.style.display = 'block';

    if (method === 'orange') {
        const amount = selectedPlan === 'monthly' ? '4.99' : '49';
        const orangeAmount = document.getElementById('orangeAmount');
        if (orangeAmount) orangeAmount.value = amount + ' $';
    }
}

// Fonction : Demander paiement Orange
function requestOrangePayment() {
    console.log('requestOrangePayment appelé');
    const orangeNumber = document.getElementById('orangeNumber').value;

    if (!orangeNumber) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: 'Veuillez entrer votre numéro Orange Money'
            });
        } else {
            alert('Erreur: Veuillez entrer votre numéro Orange Money');
        }
        return;
    }

    if (paymentModal) {
        paymentModal.hide();
    }

    const orangePasswordModalElement = document.getElementById('orangePasswordModal');
    if (orangePasswordModalElement && typeof bootstrap !== 'undefined') {
        const orangePasswordModal = new bootstrap.Modal(orangePasswordModalElement);
        orangePasswordModal.show();

        setTimeout(() => {
            const input = document.getElementById('orangePassword');
            if (input) input.focus();
        }, 500);
    }
}

// Fonction : Confirmer paiement Orange
function confirmOrangePayment() {
    console.log('confirmOrangePayment appelé');
    const password = document.getElementById('orangePassword').value;
    const orangeNumber = document.getElementById('orangeNumber').value;
    const amount = document.getElementById('orangeAmount').value;

    if (password.length !== 4) {
        if (typeof Swal !== 'undefined') {
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: 'Le code secret doit contenir 4 chiffres'
            });
        } else {
            alert('Erreur: Le code secret doit contenir 4 chiffres');
        }
        return;
    }

    const orangePasswordModalElement = document.getElementById('orangePasswordModal');
    if (orangePasswordModalElement && typeof bootstrap !== 'undefined') {
        const modalInstance = bootstrap.Modal.getInstance(orangePasswordModalElement);
        if (modalInstance) modalInstance.hide();
    }

    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Confirmer la transaction',
            html: `
                <div class="text-center">
                    <div class="orange-money-logo mb-3"><i class="fas fa-wallet"></i></div>
                    <p>Voulez-vous confirmer le paiement de <strong>${amount}</strong> ?</p>
                    <p class="text-muted">Numéro: ${orangeNumber}</p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Confirmer',
            cancelButtonText: 'Annuler',
            confirmButtonColor: '#ff6b00',
            cancelButtonColor: '#6c757d'
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: 'Traitement en cours',
                    html: `
                        <div class="text-center">
                            <div class="spinner-border text-warning mb-3" role="status"></div>
                            <p>Veuillez patienter pendant que nous traitons votre paiement...</p>
                        </div>
                    `,
                    allowOutsideClick: false,
                    showConfirmButton: false
                });

                setTimeout(() => {
                    showOrangeReceipt(orangeNumber, amount);
                }, 3000);
            }
        });
    } else {
        // Fallback sans SweetAlert2
        if (confirm(`Confirmer le paiement de ${amount} ?`)) {
            alert('Traitement en cours...');
            setTimeout(() => {
                showOrangeReceipt(orangeNumber, amount);
            }, 1000);
        }
    }
}

// Fonction : Afficher le reçu
function showOrangeReceipt(number, amount) {
    console.log('showOrangeReceipt appelé');
    const date = new Date().toLocaleString();
    const transactionId = 'OM' + Math.random().toString(36).substr(2, 9).toUpperCase();

    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Paiement réussi !',
            html: `
                <div class="orange-money-receipt">
                    <div class="receipt-header">
                        <div class="receipt-logo"><i class="fas fa-wallet"></i></div>
                        <h5>Orange Money</h5>
                        <p class="text-success">Transaction réussie</p>
                    </div>
                    <div class="receipt-details">
                        <div class="receipt-row"><span>Numéro</span><strong>${number}</strong></div>
                        <div class="receipt-row"><span>Montant</span><strong>${amount}</strong></div>
                        <div class="receipt-row"><span>Date</span><strong>${date}</strong></div>
                        <div class="receipt-row"><span>Transaction ID</span><strong>${transactionId}</strong></div>
                    </div>
                    <div class="receipt-footer">
                        <p class="mb-1">Un SMS de confirmation a été envoyé à votre numéro</p>
                        <small class="text-muted">Conservez ce reçu comme preuve de paiement</small>
                    </div>
                </div>
            `,
            confirmButtonText: 'Accéder à mon espace',
            allowOutsideClick: false,
            confirmButtonColor: '#28a745'
        }).then(() => {
            window.location.href = '/register/hopital';
        });
    } else {
        // Fallback sans SweetAlert2
        alert(`Paiement réussi !\nMontant: ${amount}\nTransaction ID: ${transactionId}`);
        window.location.href = '/register/hopital';
    }
}

// Rendre les fonctions globales pour compatibilité
window.startTrial = startTrial;
window.selectPlan = selectPlan;
window.selectPayment = selectPayment;
window.requestOrangePayment = requestOrangePayment;
window.confirmOrangePayment = confirmOrangePayment;
