<?php

namespace App\Console\Commands;

use App\Models\PbiReactivation;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class CheckStalledTicketsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-stalled-tickets {--days=14 : Batas hari pengusulan ke kementerian}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cek usulan reaktivasi PBI-JK ke Kementerian Sosial yang tertahan melebihi batas waktu (SLA)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $days = (int) $this->option('days');
        $threshold = Carbon::now()->subDays($days);

        $stalledPbis = PbiReactivation::query()
            ->with('serviceRequest')
            ->whereNotNull('proposed_to_ministry_at')
            ->where('proposed_to_ministry_at', '<', $threshold)
            ->whereNull('ministry_decided_at')
            ->get();

        $count = $stalledPbis->count();

        if ($count === 0) {
            $this->info("Tidak ada usulan PBI yang tertahan melebihi {$days} hari.");

            return Command::SUCCESS;
        }

        $this->warn("Ditemukan {$count} usulan PBI tertahan melebihi {$days} hari.");

        foreach ($stalledPbis as $pbi) {
            if ($pbi->serviceRequest && ! $pbi->serviceRequest->is_priority) {
                $pbi->serviceRequest->update(['is_priority' => true]);
                $this->line(" - Tiket {$pbi->serviceRequest->request_number} ({$pbi->participant_name}) ditandai prioritas darurat.");
            }
        }

        // Kirim Notifikasi Filament ke Admin & Petugas Dinsos
        $recipients = User::role(['administrator', 'petugas_dinsos', 'pimpinan'])->get();

        if ($recipients->isNotEmpty()) {
            Notification::make()
                ->title("Peringatan: {$count} Usulan PBI Tertahan > {$days} Hari")
                ->body("Terdapat {$count} usulan reaktivasi PBI ke Kementerian Sosial yang belum menerima keputusan dan telah ditandai sebagai prioritas.")
                ->warning()
                ->sendToDatabase($recipients);
        }

        $this->info('Notifikasi sistem berhasil dikirimkan ke petugas dan pimpinan.');

        return Command::SUCCESS;
    }
}
