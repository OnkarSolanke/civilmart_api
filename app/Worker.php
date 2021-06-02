<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Worker extends Model
{
    use HasFactory;
    protected $table = 'workers';
    protected $guarded = ['id'];

    public function address(){
        return $this->hasMany(Address::class,'user_id','id');
     }
}
