<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TicketExport implements FromView, ShouldAutoSize
{
    protected $data;
    protected $tickets;
    protected $from;
    protected $to;
    protected $categories;
    protected $subcategories;
    protected $data_dept;
    protected $data_subdept;
    protected $data_manager;
    protected $status;
    protected $sla;

    public function __construct($data)
    {
        $this->tickets       = $data['ticketReport'];
        $this->from          = $data['from'];
        $this->to            = $data['to'];
        $this->categories    = $data['categoryReport'];
        $this->subcategories = $data['subcategoryReport'];
        $this->data_dept     = $data['deptReport'];
        $this->data_subdept  = $data['subdeptReport'];
        $this->data_manager  = $data['managerReport'];
        $this->status        = $data['statusReport'];
        $this->sla           = $data['slaCount'];
    }

    public function view(): View
    {
        return view('admin.export.sla-report', [
            'from'          => $this->from,
            'to'            => $this->to,
            'tickets'       => $this->tickets,
            'status'        => $this->status,
            'categories'    => $this->categories,
            'subcategories' => $this->subcategories,
            'depts'         => $this->data_dept,
            'subdepts'      => $this->data_subdept,
            'managers'      => $this->data_manager,
            'slas'          => $this->sla,
        ]);
    }
}
