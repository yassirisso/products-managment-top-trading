<?php

namespace App\Exports;

use App\Models\ReceivingNote;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ReceivingNoteExport implements FromView
{
    protected $receivingNote;

    public function __construct(ReceivingNote $receivingNote)
    {
        $this->receivingNote = $receivingNote;
    }

    public function view(): View
    {
        return view(
            'receiving-notes.excel',
            [
                'receivingNote' => $this->receivingNote
            ]
        );
    }
}
