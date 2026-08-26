<?php

namespace Database\Seeders;

use App\Models\AuditTrail;
use App\Models\User;
use Illuminate\Database\Seeder;

class AuditTrailSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::with(['mobileProfile', 'adminProfile'])->get();

        $actionTemplates = [
            [
                'action' => 'protocol_updated',
                'action_label' => 'Update Protocol',
                'role' => 'Chief Admin',
                'descriptions' => [
                    'Memperbarui jam & durasi pada protokol Puasa Senin-Kamis.',
                    'Menambahkan instruksi klinis baru pada protokol Intermittent 16:8.',
                    'Mengubah hari aktif untuk protokol weekend fasting.',
                    'Menyetujui pembaruan target puasa pasien.',
                ],
            ],
            [
                'action' => 'patient_reviewed',
                'action_label' => 'Patient Review',
                'role' => 'Clinician',
                'descriptions' => [
                    'Melihat tren FGB dan memberikan penyesuaian dosis.',
                    'Meninjau catatan kepatuhan puasa pasien dan memberikan catatan klinis.',
                    'Mengkaji fluktuasi kadar gula darah puasa mingguan.',
                    'Memberikan konsultasi ulang mengenai durasi puasa harian.',
                ],
            ],
            [
                'action' => 'fasting_confirmed',
                'action_label' => 'Konfirmasi Puasa',
                'role' => 'Patient',
                'descriptions' => [
                    'Mengonfirmasi penyelesaian sesi puasa 16 jam.',
                    'Melaporkan mood pasca puasa: Good (Sangat Sehat).',
                    'Mengirimkan catatan pemenuhan target puasa harian.',
                    'Mengonfirmasi puasa Sunnah Senin selesai tepat waktu.',
                ],
            ],
            [
                'action' => 'cms_published',
                'action_label' => 'Publikasi CMS',
                'role' => 'Chief Admin',
                'descriptions' => [
                    'Mempublikasikan artikel "Tips Hidrasi Saat Puasa".',
                    'Mempublikasikan materi edukasi "Manajemen Gula Darah Pagi Hari".',
                    'Memperbarui panduan nutrisi berbuka puasa untuk penderita diabetes.',
                    'Mempublikasikan pengingat minum air putih harian.',
                ],
            ],
            [
                'action' => 'fgb_recorded',
                'action_label' => 'Catat FGB',
                'role' => 'Patient',
                'descriptions' => [
                    'Mencatat kadar gula darah puasa: 110 mg/dL.',
                    'Mencatat kadar gula darah puasa: 98 mg/dL.',
                    'Mencatat kadar gula darah puasa: 125 mg/dL.',
                    'Mencatat kadar gula darah puasa: 104 mg/dL.',
                ],
            ],
        ];

        $ips = ['127.0.0.1', '192.168.1.10', '192.168.1.45', '114.122.34.89', '180.252.12.33', '10.0.0.12'];

        $records = [];
        for ($i = 0; $i < 125; $i++) {
            $tmpl = $actionTemplates[$i % count($actionTemplates)];
            $user = $users->isNotEmpty() ? $users[$i % $users->count()] : null;
            $userName = $user ? ($user->adminProfile->name ?? $user->mobileProfile->name ?? $user->email) : 'System Admin';

            $records[] = [
                'id' => (string) \Illuminate\Support\Str::uuid(),
                'user_id' => $user?->id,
                'user_name' => $userName,
                'user_role' => $tmpl['role'],
                'action' => $tmpl['action'],
                'action_label' => $tmpl['action_label'],
                'description' => $tmpl['descriptions'][$i % count($tmpl['descriptions'])],
                'ip_address' => $ips[$i % count($ips)],
                'created_at' => now()->subMinutes($i * 35),
                'updated_at' => now()->subMinutes($i * 35),
            ];
        }

        if (AuditTrail::count() >= 50) {
            return;
        }

        AuditTrail::insert($records);
    }
}
