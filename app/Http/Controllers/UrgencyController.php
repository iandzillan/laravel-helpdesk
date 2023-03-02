<?php

namespace App\Http\Controllers;

use App\Models\Urgency;
use Illuminate\Http\Request;

class UrgencyController extends Controller
{
    public function index()
    {
        $urgencies = Urgency::orderBy('hours', 'ASC')->get();

        return view('admin.urgency.index', [
            'title'     => 'Urgencies - Helpdesk Ticketing System',
            'urgencies' => $urgencies
        ]);
    }
}
