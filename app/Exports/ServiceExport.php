<?php

namespace App\Exports;

use App\Services;
use App\Admin;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Auth;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Illuminate\Contracts\View\View;

class ServiceExport implements FromView,WithTitle
{
    public $folder  = "admin/report.";
    /**
    * @return \Illuminate\Support\Collection
    */

    
    public function title(): string
    {
        return 'Reporte de servicios';
    }

    public function view(): view
    {
        $res = new Services;
        $Request = [
            'from' => $_POST['from'],
            'to'   => $_POST['to'],
            'client_id' => $_POST['client_id']
        ];

		return View($this->folder.'report',[
            'data' => $res->getReport($Request),
            'from' => $_POST['from'] ? date('d-M-Y',strtotime($_POST['from'])) : null,
            'to'   => $_POST['to'] ? date('d-M-Y',strtotime($_POST['to'])) : null,
            'user' => new Admin
		]);
    }
}
