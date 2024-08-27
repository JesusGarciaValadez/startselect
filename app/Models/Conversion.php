<?php

namespace App\Models;

use Database\Factories\ConversionFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 *
 *
 * @property float $amount
 * @method static create(array $array)
 * @property int $id
 * @property int $from_currency_id
 * @property int $to_currency_id
 * @property float $conversion
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Currency $fromCurrency
 * @property-read Currency $toCurrency
 * @method static ConversionFactory factory($count = null, $state = [])
 * @method static Builder|Conversion newModelQuery()
 * @method static Builder|Conversion newQuery()
 * @method static Builder|Conversion query()
 * @method static Builder|Conversion whereAmount($value)
 * @method static Builder|Conversion whereConversion($value)
 * @method static Builder|Conversion whereCreatedAt($value)
 * @method static Builder|Conversion whereFromCurrencyId($value)
 * @method static Builder|Conversion whereId($value)
 * @method static Builder|Conversion whereToCurrencyId($value)
 * @method static Builder|Conversion whereUpdatedAt($value)
 * @mixin Eloquent
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
    public function fromCurrency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'from_currency_id');
    }

    /**
     * @return BelongsTo
     */
    public function toCurrency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'to_currency_id');
    }
}
