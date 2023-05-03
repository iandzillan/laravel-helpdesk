<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TicketExport implements FromView, ShouldAutoSize
{
    protected $tickets;
    protected $validate;
    protected $categories;

    public function __construct($tickets, $validate, $categories)
    {
        $this->tickets = $tickets;
        $this->validate = $validate;
        $this->categories = $categories;
    }

    public function view(): View
    {
        return view('admin.export.sla-report', [
            'tickets' => $this->tickets,
            'validate' => $this->validate,
            'categories' => $this->categories,
        ]);
    }
}
