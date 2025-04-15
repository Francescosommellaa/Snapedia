<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Cache;

class ResetUsers extends Command
{
    protected $signature = 'snapedia:reset-users';
    protected $description = 'Rimuove tutti gli utenti e svuota la cache OTP/verifica per Snapedia';

    public function handle(): int
    {
        if ($this->confirm('Sei sicuro di voler eliminare TUTTI gli utenti dal database?', false)) {

            // ✅ Cancella gli utenti
            User::truncate();
            $this->info('✅ Tabella users svuotata.');

            // ✅ Pulisci le cache OTP
            Cache::flush();
            $this->info('✅ Cache OTP e sessione email/verifica svuotate.');

            return Command::SUCCESS;
        }

        $this->warn('❌ Operazione annullata.');
        return Command::FAILURE;
    }
}
