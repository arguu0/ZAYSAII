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
        // Support both old (price) and new (buy_price / sell_price) column names
        $buyPrice  = $row['buy_price']  ?? $row['price'] ?? 0;
        $sellPrice = $row['sell_price'] ?? $row['price'] ?? 0;

        return new Product([
            'name'       => $row['name'],
            'category'   => $row['category'] ?? 'General',
            'quantity'   => $row['quantity']  ?? 0,
            'buy_price'  => $buyPrice,
            'sell_price' => $sellPrice,
        ]);
    }

    public function uniqueBy(): string
    {
        return 'name';
    }
}
