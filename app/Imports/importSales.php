<?php

namespace App\Imports;

use App\Models\salesModel;
use Maatwebsite\Excel\Concerns\ToModel;

class importSales implements ToModel
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
            'date'=>$row[1],
            'sku'=>$row[2],
            'styleCode'=>$row[3],
            'artical'=>$row[4],
            'size'=>$row[5],
            'size1'=>$row[6],
            'shadeCode'=>$row[7],
            'brand'=>$row[8],
            'mrp'=>$row[9],
            'quantity'=>$row[10],
        ]);
    }
}
