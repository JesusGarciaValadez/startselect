<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property float $amount
 * @property string $currency
 * @method static create(array $array)
 * @method static latest()
 * @method static findOrFail(string $id)
 */
class Operation extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'operations';

    protected $visible = ['currency_id', 'operation', 'operand1', 'operand2', 'result'];

    protected $fillable = ['currency_id', 'operation', 'operand1', 'operand2', 'result'];

    protected $casts = [
        'currency_id' => 'integer',
        'operation' => 'string',
        'operand1' => 'float',
        'operand2' => 'float',
        'result' => 'float',
    ];

    /**
     * @return BelongsTo
     */
    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
