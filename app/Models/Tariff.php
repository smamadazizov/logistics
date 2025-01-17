<?php

namespace App\Models;

use App\Models\StoredItems\Item;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string id
 * @property TariffPriceHistory|null lastPriceHistory
 * @property string description
 * @property string branch_id
 */
class Tariff extends BaseModel
{
    use SoftDeletes;

    protected $guarded = [];

//    public function items()
//    {
//        return $this->hasMany(Item::class, 'tariffId');
//    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }

    public function priceHistories()
    {
        return $this->hasMany(TariffPriceHistory::class);
    }

    public function lastPriceHistory()
    {
        return $this->hasOne(TariffPriceHistory::class)
            ->latest()
            ->where('created_at', '<', Carbon::now());
    }
}
