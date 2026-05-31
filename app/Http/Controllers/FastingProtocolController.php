<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FastingProtocolController extends Controller
{
    public function index(Request $request)
    {
        return view('fasting-protocol.index');
    }
}
