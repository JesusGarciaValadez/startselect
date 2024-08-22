<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property float $amount
 * @method static create(array $array)
 */
class Conversion extends Model
{
    use HasFactory;

    protected $table = 'conversions';

    protected $fillable = ['from_currency_id', 'to_currency_id', 'amount', 'conversion'];

    protected $casts = [
        'from_currency_id' => 'integer',
        'to_currency_id' => 'integer',
        'amount' => 'float',
        'conversion' => 'float',
    ];

    /**
     * @return BelongsTo
     */
    public function fromCurrency(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Currency::class, 'from_currency_id');
    }

    /**
     * @return BelongsTo
     */
    public function toCurrency(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Currency::class, 'to_currency_id');
    }
}
