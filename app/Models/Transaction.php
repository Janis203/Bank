<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'from_account_id',
        'to_account_id',
        'type',
        'amount',
        'message',
        'user_id',
    ];

    public const TYPE_TRANSFER = 'transfer';
    public const TYPE_BUY = 'buy';
    public const TYPE_SELL = 'sell';

    public function fromAccount(): BelongsTo
    {
        return $this->belongsTo(Accounts::class, 'from_account_id');
    }

    public function toAccount(): BelongsTo
    {
        return $this->belongsTo(Accounts::class, 'to_account_id');
    }
}
