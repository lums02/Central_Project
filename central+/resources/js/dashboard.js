document.addEventListener('DOMContentLoaded', function () {
        const sidebar = document.getElementById('sidebar');
        const toggleBtn = document.getElementById('sidebarToggle');
        const overlay = document.getElementById('sidebarOverlay');

        toggleBtn.addEventListener('click', function () {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        });

        overlay.addEventListener('click', function () {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });
    });
        // Fonction pour exporter le rapport
        function exportReport() {
            alert('Exportation du rapport...');
        }

        // Fonction pour sauvegarder un nouveau rendez-vous
        function saveAppointment() {
            alert('Rendez-vous enregistré avec succès!');
            $('#newAppointmentModal').modal('hide');
        }

        // Fonction pour générer les initiales d'un patient
        function generateInitials(name) {
            return name.split(' ').map(n => n[0]).join('').toUpperCase();
        }

        // Fonction pour sauvegarder un nouveau patient
        function savePatient() {
            const form = document.getElementById('patientForm');
            
            if (!form.checkValidity()) {
                form.reportValidity();
                return;
            }
            
            const firstname = form.querySelector('[name="firstname"]').value;
            const lastname = form.querySelector('[name="lastname"]').value;
            const birthdate = form.querySelector('[name="birthdate"]').value;
            const gender = form.querySelector('[name="gender"]').value;
            
            const patientName = `${firstname} ${lastname}`;
            const patientInfo = `${birthdate} - ${gender}`;
            const initials = generateInitials(patientName);
            
            // Mettre à jour les informations dans les modals
            document.getElementById('patientName').textContent = patientName;
            document.getElementById('patientInfo').textContent = patientInfo;
            document.getElementById('patientNameMedecin').textContent = patientName;
            document.getElementById('patientInfoMedecin').textContent = patientInfo;
            
            // Mettre à jour les avatars avec les initiales
            const avatars = document.querySelectorAll('.patient-avatar');
            avatars.forEach(avatar => {
                avatar.innerHTML = initials;
            });
            
            // Fermer le modal de nouveau patient
            const newPatientModal = bootstrap.Modal.getInstance(document.getElementById('newPatientModal'));
            newPatientModal.hide();
            
            // Afficher le modal de direction
            const directionModal = new bootstrap.Modal(document.getElementById('directionPatientModal'));
            directionModal.show();
        }

        // Fonction pour diriger vers le caissier
        function dirigerVersCaissier() {
            // Fermer le modal de direction
            const directionModal = bootstrap.Modal.getInstance(document.getElementById('directionPatientModal'));
            directionModal.hide();
            
            // Rediriger directement vers la page de paiement
            window.location.href = 'paiement.php';
        }

        // Fonction pour diriger vers le médecin
        function dirigerVersMedecin() {
            const directionModal = bootstrap.Modal.getInstance(document.getElementById('directionPatientModal'));
            directionModal.hide();
            
            // Charger les médecins simulés
            const medecins = [
                { id: 1, nom: "Dr. Jean Dupont", specialite: "Médecine générale" },
                { id: 2, nom: "Dr. Marie Martin", specialite: "Cardiologie" },
                { id: 3, nom: "Dr. Pierre Dubois", specialite: "Pédiatrie" }
            ];
            
            const select = document.getElementById('medecinSelect');
            select.innerHTML = '<option value="">Sélectionner un médecin</option>';
            medecins.forEach(medecin => {
                select.innerHTML += `<option value="${medecin.id}">${medecin.nom} - ${medecin.specialite}</option>`;
            });
            
            // Afficher le modal de sélection du médecin
            const medecinModal = new bootstrap.Modal(document.getElementById('selectionMedecinModal'));
            medecinModal.show();
        }

        // Fonction pour confirmer le médecin
        function confirmerMedecin() {
            const medecinId = document.getElementById('medecinSelect').value;
            if (!medecinId) {
                Swal.fire({
                    title: 'Attention',
                    text: 'Veuillez sélectionner un médecin',
                    icon: 'warning',
                    confirmButtonText: 'OK'
                });
                return;
            }

            // Récupérer le nom du médecin sélectionné
            const medecinSelect = document.getElementById('medecinSelect');
            const medecinNom = medecinSelect.options[medecinSelect.selectedIndex].text;

            // Fermer d'abord le modal
            const medecinModal = bootstrap.Modal.getInstance(document.getElementById('selectionMedecinModal'));
            medecinModal.hide();

            // Afficher la notification de succès
            Swal.fire({
                title: 'Médecin assigné',
                html: `
                    <div class="text-center">
                        <p>Le patient a été assigné à :</p>
                        <h4 class="text-primary">${medecinNom}</h4>
                        <p class="mt-3">Le médecin a été notifié. Le patient peut se diriger vers la salle d'attente.</p>
                    </div>
                `,
                icon: 'success',
                confirmButtonText: 'OK',
                allowOutsideClick: false,
                allowEscapeKey: false
            });
        }

        // Fonction pour charger les activités récentes
        function loadRecentActivities() {
            const activities = [
                {
                    icon: 'user-plus',
                    title: 'Nouveau patient',
                    message: 'Marie Martin a été ajoutée à la base de données',
                    time: 'Il y a 5 minutes'
                },
                {
                    icon: 'calendar-plus',
                    title: 'Nouveau rendez-vous',
                    message: 'Consultation avec Dr. Dupont programmée pour demain',
                    time: 'Il y a 15 minutes'
                },
                {
                    icon: 'file-medical',
                    title: 'Nouveau dossier',
                    message: 'Dossier médical créé pour Jean Dupont',
                    time: 'Il y a 30 minutes'
                }
            ];

            const container = document.getElementById('recentActivities');
            container.innerHTML = activities.map(activity => `
                <div class="activity-item">
                    <div class="activity-icon">
                        <i class="fas fa-${activity.icon}"></i>
                    </div>
                    <div class="activity-content">
                        <h6>${activity.title}</h6>
                        <p>${activity.message}</p>
                        <small class="activity-time">${activity.time}</small>
                    </div>
                </div>
            `).join('');
        }

        // Fonction pour charger les rendez-vous à venir
        function loadUpcomingAppointments() {
            const appointments = [
                {
                    time: '10:00',
                    patient: 'Jean Dupont',
                    doctor: 'Dr. Marie Martin',
                    type: 'Consultation'
                },
                {
                    time: '14:30',
                    patient: 'Marie Martin',
                    doctor: 'Dr. Jean Dupont',
                    type: 'Suivi'
                }
            ];

            const container = document.getElementById('upcomingAppointments');
            container.innerHTML = appointments.map(apt => `
                <div class="appointment-item">
                    <div class="appointment-time">${apt.time}</div>
                    <div class="appointment-info">
                        <h6>${apt.patient}</h6>
                        <p>${apt.doctor} - ${apt.type}</p>
                    </div>
                </div>
            `).join('');
        }

        // Fonction pour simuler le chargement des résultats
        function loadResults() {
            const results = [
                {
                    date: '2024-04-05',
                    type: 'Bilan lipidique',
                    values: [
                        { label: 'Cholestérol total', value: '2.15 g/L', range: '1.50 - 2.50 g/L' },
                        { label: 'HDL', value: '0.65 g/L', range: '0.40 - 0.60 g/L' },
                        { label: 'LDL', value: '1.20 g/L', range: '1.00 - 1.60 g/L' },
                        { label: 'Triglycérides', value: '1.10 g/L', range: '0.40 - 1.50 g/L' }
                    ],
                    comment: 'Résultats normaux. Continuer le suivi actuel.'
                },
                {
                    date: '2024-04-22',
                    type: 'Numération formule sanguine',
                    values: [
                        { label: 'Hémoglobine', value: '14.2 g/dL', range: '13.0 - 17.0 g/dL' },
                        { label: 'Leucocytes', value: '7.5 x 10^9/L', range: '4.0 - 10.0 x 10^9/L' },
                        { label: 'Plaquettes', value: '250 x 10^9/L', range: '150 - 450 x 10^9/L' }
                    ],
                    comment: 'Hémogramme normal. Pas d\'anomalie détectée.'
                }
            ];

            const resultsList = document.querySelector('.results-list');
            if (resultsList) {
                resultsList.innerHTML = results.map(result => `
                    <div class="result-item">
                        <div class="result-header">
                            <span class="result-date">${formatDate(result.date)}</span>
                            <span class="result-type">${result.type}</span>
                        </div>
                        <div class="result-content">
                            <div class="result-details">
                                ${result.values.map(value => `
                                    <div class="result-row">
                                        <span class="result-label">${value.label}</span>
                                        <span class="result-value">${value.value}</span>
                                        <span class="result-range">Normale: ${value.range}</span>
                                    </div>
                                `).join('')}
                            </div>
                            <div class="result-comment">
                                <strong>Commentaire du médecin :</strong>
                                <p>${result.comment}</p>
                            </div>
                        </div>
                    </div>
                `).join('');
            }
        }

        // Fonction pour afficher l'onglet sélectionné
        function showTab(tabId) {
            // Cacher tous les contenus d'onglets
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Désactiver tous les onglets
            document.querySelectorAll('.tab').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Afficher le contenu de l'onglet sélectionné
            const selectedTab = document.getElementById(tabId + '-tab');
            if (selectedTab) {
                selectedTab.classList.add('active');
            }
            
            // Activer l'onglet sélectionné
            event.target.classList.add('active');

            // Si c'est l'onglet résultats, charger les résultats
            if (tabId === 'resultats') {
                loadResults();
            }
        }

        // Charger les données au chargement de la page
        loadRecentActivities();
        loadUpcomingAppointments();
        loadResults();