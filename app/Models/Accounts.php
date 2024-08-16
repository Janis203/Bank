<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Accounts extends Model
{
    use HasFactory;

    const CURRENCIES = ['EUR', 'USD'];
    protected $fillable = [
        'type',
        'currency',
        'name',
        'balance',
        'user_id',
        'iban'
    ];

    public const TYPE_CHECKING = 'checking';
    public const TYPE_INVESTMENT = 'investment';
    public const TYPE_BUSINESS = 'business';
    public const TYPES = [
        self::TYPE_CHECKING,
        self::TYPE_INVESTMENT,
        self::TYPE_BUSINESS
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function ($account) {
            $account->iban = 'LV' . rand(100, 999) . 'BANK' . rand(1000, 9999) . $account->user_id;
        });
        static::created(function ($account) {
            $account->iban .= $account->id;
            $account->save();
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function transactionsFrom(): HasMany
    {
        return $this->hasMany(Transaction::class, 'from_account_id');
    }

    public function transactionsTo(): HasMany
    {
        return $this->hasMany(Transaction::class, 'to_account_id');
    }
}
