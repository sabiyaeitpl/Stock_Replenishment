<?php

namespace App\Imports;

use App\Models\rolsModel;
use Maatwebsite\Excel\Concerns\ToModel;

class importRol implements ToModel
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
}
