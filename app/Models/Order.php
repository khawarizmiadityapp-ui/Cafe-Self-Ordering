<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_number',
        'table_id',
        'customer_name',
        'total_amount',
        'payment_method',
        'payment_status',
        'order_status',
        'is_archived',
        'paid_at',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'is_archived' => 'boolean',
        'paid_at' => 'datetime',
    ];

    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function isTakeaway(): bool
    {
        return empty($this->table_id);
    }

    public function getTableDisplayNameAttribute(): string
    {
        if ($this->table) {
            return 'Meja ' . $this->table->table_number;
        }

        return 'Takeaway / Bungkus';
    }
}
