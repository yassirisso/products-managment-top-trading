<?php

namespace App\Exports;

use App\Models\ReceivingNote;
use App\Models\Supplier;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ReceivingNoteSupplierExport implements FromView
{
    protected $receivingNote;
    protected $supplier;

    public function __construct(ReceivingNote $receivingNote, Supplier $supplier)
    {
        $this->receivingNote = $receivingNote;
        $this->supplier = $supplier;
    }

    public function view(): View
    {
        $products = $this->receivingNote->products()
            ->whereHas('suppliers', fn($q) => $q->where('suppliers.id', $this->supplier->id))
            ->with('suppliers')
            ->get();

        return view(
            'receiving-notes.supplier-excel',
            [
                'receivingNote' => $this->receivingNote,
                'supplier' => $this->supplier,
                'products' => $products,
            ]
        );
    }
}
