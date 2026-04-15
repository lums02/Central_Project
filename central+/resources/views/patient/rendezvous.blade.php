@extends('layouts.admin')

@section('title', 'Mes Rendez-vous')
@section('page-title', 'Mes Rendez-vous')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4"><i class="fas fa-calendar-alt me-2"></i>Mes Rendez-vous</h3>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Date & Heure</th>
                            <th>Médecin</th>
                            <th>Hôpital</th>
                            <th>Motif</th>
                            <th>Statut</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rendezvous as $rdv)
                        <tr>
                            <td>
                                <strong>{{ \Carbon\Carbon::parse($rdv->date_rendezvous)->format('d/m/Y') }}</strong><br>
                                <small class="text-muted">{{ substr($rdv->heure_rendezvous, 0, 5) }}</small>
                            </td>
                            <td>Dr. {{ $rdv->medecin->nom }}</td>
                            <td>{{ $rdv->hopital->nom }}</td>
                            <td>{{ Str::limit($rdv->motif, 50) }}</td>
                            <td>
                                @php
                                    $statusClass = match($rdv->statut) {
                                        'confirme' => 'success',
                                        'termine' => 'secondary',
                                        'annule' => 'danger',
                                        default => 'warning'
                                    };
                                @endphp
                                <span class="badge bg-{{ $statusClass }}">{{ ucfirst($rdv->statut) }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="fas fa-calendar-times fa-3x text-muted mb-3"></i>
                                <p class="text-muted mb-0">Aucun rendez-vous</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if($rendezvous->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $rendezvous->links() }}
    </div>
    @endif
</div>
@endsection

