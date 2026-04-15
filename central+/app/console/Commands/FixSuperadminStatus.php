<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Utilisateur;

class FixSuperadminStatus extends Command
{
    protected $signature = 'fix:superadmin-status';
    protected $description = 'Corrige le statut du superadmin à approved';

    public function handle()
    {
        $this->info('🔧 Correction du statut du superadmin...');

        $superadmin = Utilisateur::where('email', 'admin@central.com')->first();
        
        if (!$superadmin) {
            $this->error('❌ Superadmin non trouvé !');
            return 1;
        }

        $superadmin->update(['status' => 'approved']);
        
        $this->info('✅ Statut du superadmin corrigé à "approved"');
        $this->line("👑 {$superadmin->nom} ({$superadmin->email}) - Status: {$superadmin->status}");
        
        return 0;
    }
}
