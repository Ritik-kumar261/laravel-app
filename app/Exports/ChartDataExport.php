<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class ChartDataExport implements FromCollection, WithHeadings
{
    protected $data;

    // Constructor to receive the data
    public function __construct(Collection $data)
    {
        $this->data = $data;
    }

    // Method to return the data collection
    public function collection()
    {
        return $this->data;
    }
    public function headings(): array
    {
        return [
            'Category Title',
            'Total Workout Sessions',
            'Start Date',
            'End Date',
        ];
    }

   
}
