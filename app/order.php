<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class order extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function product(){
        return $this->hasOne(Product::class,'id','product_id');
    }

    public function vendor(){
        return $this->hasOne(Vendor::class,'user_id','vendor_id');
    }

    public function customer(){
        return $this->hasOne(User::class,'id','user_id');
    }

    public function address(){
        return $this->hasOne(Address::class,'id','address_id');
    }
}
