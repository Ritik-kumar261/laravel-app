<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class CustomerDataExport implements FromCollection
{
    protected $levelData;

    public function __construct(Collection $levelData)
    {
        $this->levelData = $levelData;
    }

    public function collection()
    {
        return $this->levelData;
    }

    
}
