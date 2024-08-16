<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'symbol',
        'type',
        'name',
        'rate',
        'currency',
        'rank'
    ];

    public const TYPE_FIAT = 'fiat';
    public const TYPE_CRYPTO = 'crypto';

    public function getRouteKeyName(): array
    {
        return ['symbol', 'type'];
    }

    public function accounts(): HasMany
    {
        return $this->hasMany(Accounts::class);
    }
}
