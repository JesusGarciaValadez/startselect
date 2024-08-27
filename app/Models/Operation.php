<?php

namespace App\Models;

use Database\Factories\OperationFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property float $amount
 * @property string $currency
 *
 * @method static create(array $array)
 * @method static latest()
 * @method static findOrFail(string $id)
 *
 * @property int $id
 * @property int $currency_id
 * @property string $operation
 * @property float $operand1
 * @property float $operand2
 * @property float $result
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
 * @method static OperationFactory factory($count = null, $state = [])
 * @method static Builder|Operation newModelQuery()
 * @method static Builder|Operation newQuery()
 * @method static Builder|Operation query()
 * @method static Builder|Operation whereCreatedAt($value)
 * @method static Builder|Operation whereCurrencyId($value)
 * @method static Builder|Operation whereId($value)
 * @method static Builder|Operation whereOperand1($value)
 * @method static Builder|Operation whereOperand2($value)
 * @method static Builder|Operation whereOperation($value)
 * @method static Builder|Operation whereResult($value)
 * @method static Builder|Operation whereUpdatedAt($value)
 *
 * @mixin Eloquent
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

    public function currency(): BelongsTo
    {
        return $this->belongsTo(Currency::class);
    }
}
