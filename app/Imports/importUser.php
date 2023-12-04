<?php

namespace App\Imports;

use App\Models\importUserModel;
use Maatwebsite\Excel\Concerns\ToModel;

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
            'name'=>$row[0],
            'date'=>$row[1],
            'section'=>$row[2],
            'department'=>$row[3],
            'category_1'=>$row[4],
            'category_2'=>$row[5],
            'category_3'=>$row[6],
            'category_4'=>$row[7],
            'category_5'=>$row[8],
            'category_6'=>$row[9],
            'barcode'=>$row[10],
            'bill_quantity'=>$row[11],
            'mrp_amount'=>$row[12],
            'net_amount'=>$row[13],
            'tax_desc'=>$row[14],
            'tax_amt'=>$row[15],
            'dis_amt'=>$row[16],
            'mobile_no'=>$row[16],
            'status'=>'active',
            // 'updated_at'=>'',
            // 'created_at'=>'',
        ]);
    }
}
