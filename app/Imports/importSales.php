<?php

namespace App\Imports;

use App\Models\salesModel;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Contracts\Queue\ShouldQueue; // Fix the case here
use Maatwebsite\Excel\Concerns\WithChunkReading;

class importSales implements ToModel, WithChunkReading, ShouldQueue
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
       
        return new salesModel([
            'storeId'=>$row[0],
            'name'=>$row[1],
            'date'=>$row[2],
            'section'=>$row[3],
            'department'=>$row[4],
            'category_1'=>$row[5],
            'category_2'=>$row[6],
            'category_3'=>$row[7],
            'category_4'=>$row[8],
            'category_5'=>$row[9],
            'category_6'=>$row[10],
            'barcode'=>$row[11],
            'bill_quantity'=>$row[12],
            'mrp_amount'=>$row[13],
            'net_amount'=>$row[14],
            'tax_desc'=>$row[15],
            'tax_amt'=>$row[16],
            'dis_amt'=>$row[17],
            'mobile_no'=>$row[18]
        ]);
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
