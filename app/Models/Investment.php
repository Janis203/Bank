<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Investment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'account_id',
        'amount',
        'invested_at',
    ];

    protected $dates = ['invested_at'];

    protected $casts = [
        'invested_at' => 'datetime',
    ];

    public function account()
    {
        return $this->belongsTo(Accounts::class);
    }

    public function calculateCurrentValue(): float|int
    {
        $days = now()->diffInDays($this->invested_at) + 0.125;
        $rate = 0.10;
        return $this->amount *= (1 + $rate) ** $days;
    }


}
