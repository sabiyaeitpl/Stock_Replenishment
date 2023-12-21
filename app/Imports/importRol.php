<?php

namespace App\Imports;

use App\Models\rolsModel;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Contracts\Queue\ShouldQueue; 
use Maatwebsite\Excel\Concerns\WithChunkReading;

class importRol implements ToModel ,WithChunkReading, ShouldQueue
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new rolsModel([
            'storeId'=>$row[0],
            'effective_to'=>$row[1],
            'effective_from'=>$row[2],
            'sku'=>$row[3],
            'styleCode'=>$row[4],
            'artical'=>$row[5],
            'size'=>$row[6],
            'size1'=>$row[7],
            'shadeCode'=>$row[8],
            'brand'=>$row[9],
            'mrp'=>$row[10],
            'quantity'=>$row[11],
        ]);
    }
    public function chunkSize(): int
    {
        return 1000;
    }
}
