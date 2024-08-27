<?php

namespace App\Models;

use Database\Factories\CurrencyFactory;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @method static create(string[] $array)
 * @method static find(int $value)
 * @method static where(string[] $array)
 *
 * @property int $id
 * @property string $code
 *
 * @method static CurrencyFactory factory($count = null, $state = [])
 * @method static Builder|Currency newModelQuery()
 * @method static Builder|Currency newQuery()
 * @method static Builder|Currency query()
 * @method static Builder|Currency whereCode($value)
 * @method static Builder|Currency whereId($value)
 *
 * @mixin Eloquent
 */
class Currency extends Model
{
    use HasFactory;

    protected $table = 'currencies';

    protected $fillable = ['code'];

    public $timestamps = false;
}
