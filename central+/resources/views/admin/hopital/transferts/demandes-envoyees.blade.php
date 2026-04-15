@extends('layouts.admin')
@section('title', 'Demandes Envoyées')
@section('page-title', 'Demandes de Transfert Envoyées')
@section('content')
<div class="container-fluid py-4">
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <div class="card">
        <div class="card-body p-0">
            <table class="table">
                <thead>
                    <tr><th>Patient</th><th>Hôpital Détenteur</th><th>Date Demande</th><th>Statut</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    @foreach($demandes as $demande)
                        <tr>
                            <td>{{ $demande->patient->nom }}</td>
                            <td>{{ $demande->hopitalDetenteur->nom }}</td>
                            <td>{{ $demande->date_demande->format('d/m/Y H:i') }}</td>
                            <td><span class="badge bg-{{ $demande->statut_color }}">{{ $demande->statut_format }}</span></td>
                            <td>
                                @if($demande->statut == 'transfere')
                                    <a href="{{ route('admin.hopital.patients.dossier', [$demande->patient_id, $demande->dossier_medical_id]) }}" class="btn btn-sm btn-success">
                                        <i class="fas fa-eye"></i> Voir Dossier
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    {{ $demandes->links() }}
</div>
@endsection

