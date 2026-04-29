<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\FastingProtocolResource;
use App\Models\FastingProtocol;
use Illuminate\Http\Request;

class FastingProtocolController extends Controller
{
    public function index(Request $request)
    {
        $protocols = FastingProtocol::with('days')->get();

        return FastingProtocolResource::collection($protocols);
    }
}
