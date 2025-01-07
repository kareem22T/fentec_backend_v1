<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class DynamicExport implements FromCollection, WithHeadings, WithColumnWidths
{
    protected $table;
    protected $includeHeadings;
    protected $conditions;

    public function __construct($table, $includeHeadings = false, $conditions = [])
    {
        $this->table = $table;
        $this->includeHeadings = $includeHeadings;
        $this->conditions = $conditions;
    }

    public function collection()
    {
        $query = DB::table($this->table);

        // Apply conditions dynamically
        foreach ($this->conditions as $condition) {
            $column = $condition->column ?? null;
            $operator = $condition->operator ?? '=';
            $value = $condition->value ?? null;

            if ($column) {
                $query->where($column, $operator, $value);
            }
        }

        return $query->get();
    }

    public function headings(): array
    {
        if ($this->includeHeadings) {
            return DB::getSchemaBuilder()->getColumnListing($this->table);
        }

        return [];
    }

    public function columnWidths(): array
    {
        $columns = DB::getSchemaBuilder()->getColumnListing($this->table);
        $columnWidths = [];

        foreach ($columns as $index => $column) {
            $columnWidths[chr(65 + $index)] = 20; // Adjust column width as needed
        }

        return $columnWidths;
    }
}
