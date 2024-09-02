<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class DynamicExport implements FromCollection, WithHeadings, WithColumnWidths
{
    protected $table;
    protected $includeHeadings;

    public function __construct($table, $includeHeadings = false)
    {
        $this->table = $table;
        $this->includeHeadings = $includeHeadings;
    }

    public function collection()
    {
        return DB::table($this->table)->get();
    }

    public function headings(): array
    {
        if ($this->includeHeadings) {
            // Fetch column names dynamically
            return DB::getSchemaBuilder()->getColumnListing($this->table);
        }

        return []; // No headings if $includeHeadings is false
    }

    public function columnWidths(): array
    {
        // Here you can define the width for each column
        $columns = DB::getSchemaBuilder()->getColumnListing($this->table);
        $columnWidths = [];

        foreach ($columns as $index => $column) {
            // Adjust this value as per your requirement
            $columnWidths[chr(65 + $index)] = 20; // 20 is the width, A=65 in ASCII
        }

        return $columnWidths;
    }
}
