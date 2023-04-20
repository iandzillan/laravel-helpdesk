<?php

namespace App\Exports;

use App\Models\Ticket;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class TicketExport implements FromView
{
    protected $tickets;

    public function __construct($tickets)
    {
        $this->tickets = $tickets;
    }

    public function view(): View
    {
        return view('admin.export.sla-report', [
            'tickets' => $this->tickets
        ]);
    }
}
