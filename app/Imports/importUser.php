<?php

namespace App\Imports;

use App\Models\importUserModel;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class importUser implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {

        return new importUserModel([
            'storeId'=>$row[0],
            'name'=>$row[1],
            'division'=>$row[2],
            'section'=>$row[3],
            'department'=>$row[4],
            'category_1'=>$row[5],
            'category_2'=>$row[6],
            'category_3'=>$row[7],
            'category_4'=>$row[8],
            'category_5'=>$row[9],
            'category_6'=>$row[10],
            'barcode'=>$row[11],
            'stock_quantity'=>$row[12],
            'stock_amount'=>$row[13],
        ]);
    }
    public function chunkSize(): int
    {
        return 1000;
    }
}
