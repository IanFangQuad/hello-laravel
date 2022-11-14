<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Member extends Authenticatable
{

    use HasFactory;

    protected $table = 'member';
    protected $primaryKey = 'id';
    protected $guarded = [];
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function leaves()
    {
        return $this->hasMany(Leave::class, 'member_id', 'id');
    }

}
