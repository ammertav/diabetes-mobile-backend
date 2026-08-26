<?php

namespace App\Http\Controllers;

use App\Models\FastingProtocol;
use App\Models\FastingProtocolDay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FastingProtocolController extends Controller
{
    public function index(Request $request)
    {
        $protocols = FastingProtocol::with('days')->latest()->get();
        return view('fasting-protocol.index', compact('protocols'));
    }

    public function create()
    {
        return view('fasting-protocol.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:sunnah,intermittent,custom'],
            'start_time' => ['nullable', 'string', 'max:10'],
            'end_time' => ['nullable', 'string', 'max:10'],
            'duration_hours' => ['required', 'integer', 'min:1', 'max:168'],
            'description' => ['nullable', 'string'],
            'days' => ['required', 'array', 'min:1'],
            'days.*' => ['integer', 'between:1,7'],
        ]);

        DB::transaction(function () use ($validated) {
            $protocol = FastingProtocol::create([
                'name' => $validated['name'],
                'type' => $validated['type'],
                'start_time' => $validated['start_time'] ?? '18:00',
                'end_time' => $validated['end_time'] ?? '10:00',
                'duration_hours' => $validated['duration_hours'],
                'description' => $validated['description'] ?? null,
            ]);

            foreach ($validated['days'] as $day) {
                FastingProtocolDay::create([
                    'fasting_protocol_id' => $protocol->id,
                    'day' => $day,
                ]);
            }
        });

        return redirect()
            ->route('fasting-protocols')
            ->with('success', 'Protokol puasa berhasil dibuat.');
    }

    public function update(\App\Http\Requests\UpdateFastingProtocolRequest $request, string $id, \App\Actions\Fasting\UpdateFastingProtocolAction $action)
    {
        $protocol = FastingProtocol::findOrFail($id);
        $action->execute($protocol, $request->validated());

        return redirect()
            ->route('fasting-protocols')
            ->with('success', 'Protokol puasa berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $protocol = FastingProtocol::findOrFail($id);
        $protocol->delete();

        return redirect()
            ->route('fasting-protocols')
            ->with('success', 'Protokol puasa berhasil dihapus.');
    }
}
