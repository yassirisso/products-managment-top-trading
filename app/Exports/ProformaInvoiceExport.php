<?php

namespace App\Exports;

use App\Models\ProformaInvoice;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ProformaInvoiceExport implements FromView
{
    protected $proformaInvoice;

    public function __construct(ProformaInvoice $proformaInvoice)
    {
        $this->proformaInvoice = $proformaInvoice;
    }

    public function view(): View
    {
        return view(
            'proforma-invoices.excel',
            [
                'proformaInvoice' => $this->proformaInvoice
            ]
        );
    }
}