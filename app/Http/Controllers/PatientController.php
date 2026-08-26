<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientListRequest;
use App\DTO\PatientFilterData;
use App\Actions\Patient\ListPatientsAction;
use App\Http\Resources\PatientResource;
use App\Models\FastingProtocol;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $protocols = FastingProtocol::all();
        return view('patients-management.index', compact('protocols'));
    }

    public function loadPatients(PatientListRequest $request, ListPatientsAction $action)
    {
        $validated = $request->validated();
        $page = (int) $request->input('page', 1);

        $dto = PatientFilterData::fromRequest($validated, $page);
        
        $result = $action->execute($dto);
        $patients = $result['patients'];

        return response()->json([
            'stats' => $result['stats'],
            'patients' => PatientResource::collection($patients)->response()->getData(true),
            'pagination' => [
                'current_page' => $patients->currentPage(),
                'last_page' => $patients->lastPage(),
                'total' => $patients->total(),
            ]
        ]);
    }
}
