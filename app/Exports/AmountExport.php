<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
class AmountExport implements  FromView, ShouldAutoSize, WithEvents
{
    public function  __construct($info)
    {
      $this->informacion = $info;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function(AfterSheet $event) {
                $cellRange = 'A1:B1'; // All headers
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(14);
            },
        ];
    }

    public function view(): View
    {
        return view('exports.AmountReport',[
            'informacion' => $this->informacion
        ]);
    }
}
