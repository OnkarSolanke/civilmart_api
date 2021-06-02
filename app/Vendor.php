<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = 'vendor';
    protected $guarded = ['id'];

    public function address(){
       return $this->hasMany(Address::class,'user_id','user_id');
    }
}
