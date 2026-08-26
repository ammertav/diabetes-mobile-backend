<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Klinis - {{ $patient->mobileProfile->name ?? $patient->email }}</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css'])
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-headline { font-family: 'Manrope', sans-serif; }
        @media print {
            .no-print { display: none !important; }
            body { background: white; color: black; }
            .print-border { border: 1px solid #cbd5e1 !important; }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 p-8">

    <!-- Action Bar (Hide in Print) -->
    <div class="max-w-4xl mx-auto mb-6 no-print flex justify-between items-center bg-white p-4 rounded-xl shadow-sm border border-slate-100">
        <span class="text-sm font-semibold text-slate-500">Laporan tergenerasi untuk pencetakan klinis.</span>
        <button onclick="window.print()" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-bold flex items-center gap-2 transition-all shadow-sm">
            <span class="material-symbols-outlined text-sm">print</span>
            Cetak Laporan
        </button>
    </div>

    <!-- Report Canvas -->
    <div class="max-w-4xl mx-auto bg-white p-12 rounded-2xl shadow-sm border border-slate-100 print-border space-y-8">
        <!-- Header -->
        <div class="border-b border-slate-200 pb-6 flex justify-between items-start">
            <div>
                <h1 class="text-3xl font-extrabold text-blue-700 font-headline">Clinical Sanctuary</h1>
                <p class="text-xs text-slate-500 uppercase tracking-widest font-semibold mt-1">Diabetes Care Administration</p>
            </div>
            <div class="text-right">
                <h2 class="text-lg font-bold text-slate-700 font-headline">Laporan Kesehatan Pasien</h2>
                <p class="text-xs text-slate-500">Periode: {{ $startDate->format('d M Y') }} s/d {{ $endDate->format('d M Y') }}</p>
            </div>
        </div>

        <!-- Patient Metadata -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 bg-slate-50 p-6 rounded-xl text-sm">
            <div>
                <p class="text-slate-400 text-xs">Nama Pasien</p>
                <p class="font-bold text-slate-700 mt-0.5">{{ $patient->mobileProfile->name ?? '-' }}</p>
            </div>
            <div>
                <p class="text-slate-400 text-xs">Umur / Gender</p>
                <p class="font-bold text-slate-700 mt-0.5">{{ $patient->mobileProfile->age ?? '-' }} Thn / {{ $patient->mobileProfile->gender?->value ?? '-' }}</p>
            </div>
            <div>
                <p class="text-slate-400 text-xs">Status Diabetes</p>
                <p class="font-bold text-slate-700 mt-0.5">{{ $patient->mobileProfile->diabetes_status?->value ?? '-' }}</p>
            </div>
            <div>
                <p class="text-slate-400 text-xs">Protokol Aktif</p>
                <p class="font-bold text-slate-700 mt-0.5">{{ $patient->activeProtocol?->protocol?->name ?? 'Tidak Ada' }}</p>
            </div>
        </div>

        <!-- Stats Cards Grid -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="border border-slate-100 p-4 rounded-xl text-center">
                <p class="text-slate-400 text-xs font-medium">Rerata FBG</p>
                <p class="text-xl font-extrabold text-slate-800 mt-1" x-text="'{{ $fbgStats['avg'] ?? '-' }} mg/dL'">{{ $fbgStats['avg'] ?? '-' }} mg/dL</p>
            </div>
            <div class="border border-slate-100 p-4 rounded-xl text-center">
                <p class="text-slate-400 text-xs font-medium">Rentang FBG</p>
                <p class="text-xl font-extrabold text-slate-800 mt-1">{{ $fbgStats['min'] ?? '-' }} - {{ $fbgStats['max'] ?? '-' }} mg/dL</p>
            </div>
            <div class="border border-slate-100 p-4 rounded-xl text-center">
                <p class="text-slate-400 text-xs font-medium">Kepatuhan Puasa</p>
                <p class="text-xl font-extrabold text-slate-800 mt-1">{{ $fastingStats['adherence'] }}%</p>
            </div>
            <div class="border border-slate-100 p-4 rounded-xl text-center">
                <p class="text-slate-400 text-xs font-medium">Peringatan Kritis</p>
                <p class="text-xl font-extrabold text-rose-600 mt-1">{{ $alertStats['total'] }} Kali</p>
            </div>
        </div>

        <!-- Alert Logs -->
        <div class="space-y-4">
            <h3 class="text-lg font-bold text-slate-700 border-b border-slate-100 pb-2 font-headline">Riwayat Peringatan Medis (Maks. 10 Terakhir)</h3>
            <table class="min-w-full text-xs">
                <thead>
                    <tr class="text-slate-400 border-b border-slate-100 font-semibold text-left">
                        <th class="pb-2">Tipe Alert</th>
                        <th class="pb-2">Keterangan / Pesan</th>
                        <th class="pb-2">Tindakan User</th>
                        <th class="pb-2">Penyelesaian</th>
                        <th class="pb-2">Timestamp</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50 text-slate-600">
                    @forelse ($alertStats['list'] as $alert)
                        <tr>
                            <td class="py-2.5 font-bold uppercase {{ str_contains($alert->type, 'severe') ? 'text-rose-600' : 'text-amber-600' }}">
                                {{ str_replace('_', ' ', $alert->type) }}
                            </td>
                            <td class="py-2.5">{{ $alert->message }}</td>
                            <td class="py-2.5 italic">{{ $alert->action_taken ?? '-' }}</td>
                            <td class="py-2.5 font-medium">
                                {{ $alert->acknowledged_at ? 'Resolved' : 'Unresolved' }}
                            </td>
                            <td class="py-2.5 text-slate-400">{{ $alert->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-6 text-center text-slate-400">Tidak ada riwayat peringatan medis selama periode ini.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Footer / Signature -->
        <div class="border-t border-slate-100 pt-8 flex justify-between text-xs text-slate-400">
            <p>Dicetak pada: {{ now()->format('d M Y, H:i') }}</p>
            <div class="text-center w-48">
                <p>Tanda Tangan Clinician,</p>
                <div class="h-16 border-b border-slate-200"></div>
                <p class="mt-2 font-semibold text-slate-600">{{ auth()->user()->adminProfile->name ?? 'Petugas Medis' }}</p>
            </div>
        </div>
    </div>

</body>
</html>
