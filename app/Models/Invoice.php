<?php

namespace App\Models;

use App\Models\User;
use App\Models\Customer;
use App\Models\InvoiceProduct;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'total',
        'discount',
        'vat',
        'payable',
        'user_id',
        'customer_id',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }//end user

    public function customer(){
        return $this->belongsTo(Customer::class);
    }//end customer

    public function invoiceProducts(){
        return $this->hasMany(InvoiceProduct::class);
    }//end invoiceProducts
}
