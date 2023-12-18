<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class importUserModel extends Model
{
    use Notifiable;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'storeId','name','division','section','department','category_1','category_2','category_3','category_4','category_5','category_6','barcode','stock_quantity','stock_amount'
    ];
    // protected $fillable = [
    //     'storeId','date','sku','styleCode','artical','size','size1','shadeCode','brand','mrp','quantity'
    // ];

    // use HasFactory;
    // protected $table= "sales";

    // protected $table= "importexcel";
    protected $table= "stock";
}  
