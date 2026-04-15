<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="card-title mb-0">Activités récentes</h5>
                    <select class="form-select" id="activityFilter">
                        <option value="all">Toutes les activités</option>
                        <option value="appointment">Rendez-vous</option>
                        <option value="patient">Patients</option>
                        <option value="system">Système</option>
                    </select>
                </div>
                <div class="activity-list" id="recentActivities">
                    <!-- Contenu dynamique chargé via JS/Ajax -->
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Rendez-vous à venir</h5>
                <div class="appointment-list" id="upcomingAppointments">
                    <!-- Contenu dynamique chargé via JS/Ajax -->
                </div>
            </div>
        </div>
    </div>
</div>
