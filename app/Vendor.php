<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = 'vendor';
    protected $guarded = ['id'];
    protected $fillable = [
        'first_name','last_name' ,'email', 'mobile','user_id','midle_name'
    ];
}
