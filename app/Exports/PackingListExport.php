<?php

namespace App\Exports;

use App\Models\PackingList;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PackingListExport implements
    FromView,
    ShouldAutoSize,
    WithStyles
{
    protected $packingList;

    public function __construct(PackingList $packingList)
    {
        $this->packingList = $packingList;
    }

    public function view(): View
    {
        return view('packing-lists.excel', [
            'packingList' => $this->packingList
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        return [

            // TITLE
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 16,
                ],
            ],

            // TABLE HEADER
            10 => [
                'font' => [
                    'bold' => true,
                ],
            ],
        ];
    }
}