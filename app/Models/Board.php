<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Board extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'board_id';

    // 작성을 하지 않으면 인서트가 오류와 함꼐 안 됨
    protected $fillable = [
        'user_id',
        'content',
        'img',
        'like'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id')->select('user_id', 'name');
    }
}
