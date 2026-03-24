<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Plat;
use App\Models\User;

class Recommendation extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_READY = 'ready';
    const STATUS_FAILED = 'failed';

    protected $fillable = [
        'user_id',
        'plat_id',
        'score',
        'compatible',
        'reasoning',
        'warning_message',
        'status'
    ];

    protected $casts = [
        'compatible' => 'boolean',
        'warnings'   => 'array',
    ];

    public function plat()
    {
        return $this->belongsTo(Plat::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
