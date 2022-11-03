<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Collection;

class Member extends Authenticatable {

    use HasFactory;

    //存資料時不要自動帶入update_at  create_at 欄位
    //public $timestamps = false;
    //指定資料表名稱
    protected $table = 'member';
    protected $primaryKey = 'id';
//    protected $fillable = ['MemEmail', 'MemPwd', 'MemIdentity', 'MemName', 'MemCompanyName', 'MemPhone'];
    //所有欄位可以批量更新
    protected $guarded = [];

    public function findMember(array $whereClause) : Collection
    {
        return $this->where($whereClause)->get();
    }
    //一對多關聯
    // public function cases() {
    //     return $this->hasMany('App\Models\Cases', 'MemID', 'MemID');
    // }

}
