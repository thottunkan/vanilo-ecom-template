<?php

namespace App\Exports;

use Vanilo\Foundation\Models\Taxon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class CategoryExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    protected int $serial = 0;

    public function collection()
    {
        return Taxon::with('parent')
            ->orderBy('name')
            ->get();
    }

    public function headings(): array
    {
        return [
            '#',
            'Name',
            'Slug',
            'Parent Category',
            'Created At',
            'Updated At',
        ];
    }

    public function map($taxon): array
    {
        return [
            ++$this->serial,
            $taxon->name,
            $taxon->slug,
            optional($taxon->parent)->name ?? '-',
            optional($taxon->created_at)->format('d M Y H:i'),
            optional($taxon->updated_at)->format('d M Y H:i'),
        ];
    }
}