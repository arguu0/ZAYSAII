<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;

class ProductsImport implements ToModel, WithHeadingRow, WithUpserts
{
    public function model(array $row): Model|null
    {
        return new Product([
            'name'     => $row['name'],
            'category' => $row['category'] ?? 'General',
            'quantity' => $row['quantity'],
            'price'    => $row['price'],
        ]);
    }

    public function uniqueBy(): string
    {
        return 'name';
    }
}
