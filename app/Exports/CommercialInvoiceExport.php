<?php

namespace App\Exports;

use App\Models\CommercialInvoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CommercialInvoiceExport implements FromView
{
    protected $commercialInvoice;

    public function __construct(
        CommercialInvoice $commercialInvoice
    ) {
        $this->commercialInvoice = $commercialInvoice;
    }

    public function view(): View
    {
        return view(
            'commercial-invoices.excel',
            [
                'commercialInvoice' => $this->commercialInvoice
            ]
        );
    }
}