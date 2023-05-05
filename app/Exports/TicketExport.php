<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class TicketExport implements FromView, ShouldAutoSize
{
    protected $data;
    protected $tickets;
    protected $validate;
    protected $categories;
    protected $subcategories;
    protected $data_dept;
    protected $data_subdept;
    protected $data_manager;
    protected $status;
    protected $sla;

    public function __construct($data)
    {
        $this->tickets       = $data['tickets'];
        $this->validate      = $data['validate'];
        $this->categories    = $data['categories'];
        $this->subcategories = $data['subcategories'];
        $this->data_dept     = $data['data_dept'];
        $this->data_subdept  = $data['data_subdept'];
        $this->data_manager  = $data['data_manager'];
        $this->status        = $data['status'];
        $this->sla           = $data['sla'];
    }

    public function view(): View
    {
        return view('admin.export.sla-report', [
            'tickets'       => $this->tickets,
            'validate'      => $this->validate,
            'categories'    => $this->categories,
            'subcategories' => $this->subcategories,
            'data_dept'     => $this->data_dept,
            'data_subdept'  => $this->data_subdept,
            'data_manager'  => $this->data_manager,
            'status'        => $this->status,
            'sla'           => $this->sla,
        ]);
    }
}
