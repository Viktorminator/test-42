<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'status',
        'tariff_id',
        'user_count',
        'total_cost',
        'payment_frequency',
        'valid_until',
    ];

    protected $casts = ['valid_until' => 'date'];

    protected $appends = ['tariff_name'];

    public function tariff()
    {
        return $this->belongsTo(Tariff::class);
    }

    public function getTariffNameAttribute()
    {
        return $this->tariff ? $this->tariff->name : null;
    }
}
