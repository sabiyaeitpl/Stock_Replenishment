<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class salesModel extends Model
{
    use Notifiable;

     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'storeId','name', 'date', 'section','department','category_1','category_2','category_3','category_4','category_5','category_6','barcode','bill_quantity','mrp_amount','net_amount','tax_desc','tax_amt','dis_amt','mobile_no'
    ];
   
    // use HasFactory;
    protected $table= "sales";

}  
