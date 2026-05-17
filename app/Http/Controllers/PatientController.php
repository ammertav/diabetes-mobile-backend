<?php

namespace App\Http\Controllers;

use App\Enums\UserType;
use App\Models\User;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        return view('patients-management.index');
    }

    public function loadPatients(Request $request)
    {
        $patients = User::query()
            ->where('type', UserType::MOBILE->value)
            ->with([
                'activeProtocol',
            ])
            ->latest()
            ->paginate(10);

        $protocolPatients = $patients->getCollection()
            ->filter(fn($patient) => $patient->activeProtocol)
            ->count();

        return response()->json([
            "stats" => [
                "total_patients" => $patients->count(),
                "protocol_patients" => $protocolPatients,
                "high_risk_patients" => 0,
            ],
            'patients' => $patients->through(fn($patient) => [
                'id' => $patient->id,
                'name' => $patient->name,
                'email' => $patient->email,
                'photo' => $patient->photo_url,
                'patient_code' => $patient->patient_code,
                'diabetes_type' => $patient->mobileProfile->diabetes_status,
                'protocol' => $patient->activeProtocol?->name,
                'risk_status' => $patient->risk_status,
                'last_checkin' => optional(
                    $patient->latestCheckin?->created_at
                )->diffForHumans(),
            ]),

            'pagination' => [
                'current_page' => $patients->currentPage(),
                'last_page' => $patients->lastPage(),
                'total' => $patients->total(),
            ]
        ]);
    }
}
