<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    public const STATUS_DRAFT = 'draft';

    public const STATUS_PLACED = 'оформлен';

    public const STATUSES = [
        self::STATUS_PLACED => 'Оформлен',
        'processing' => 'В обработке',
        'shipped' => 'Отправлен',
        'completed' => 'Выполнен',
        'cancelled' => 'Отменён',
    ];

    protected $fillable = [
        'number', 'user_id', 'status', 'checkout_step', 'subtotal', 'discount',
        'bonus_used', 'bonus_earned', 'total', 'customer_name', 'customer_email',
        'customer_phone', 'shipping_city', 'shipping_address', 'shipping_method',
        'payment_method', 'promo_code', 'promotion_id', 'comment',
    ];

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:2',
            'discount' => 'decimal:2',
            'total' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function promotion(): BelongsTo
    {
        return $this->belongsTo(Promotion::class);
    }

    public static function generateNumber(): string
    {
        return 'AU-'.date('Ymd').'-'.str_pad((string) (static::count() + 1), 5, '0', STR_PAD_LEFT);
    }

    public function statusLabel(): string
    {
        if ($this->status === self::STATUS_DRAFT) {
            return 'Черновик';
        }

        if ($this->status === 'pending') {
            return self::STATUSES[self::STATUS_PLACED];
        }

        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function statusTone(): string
    {
        return match ($this->status) {
            'completed' => 'success',
            'shipped' => 'info',
            'processing' => 'warning',
            'cancelled' => 'danger',
            self::STATUS_PLACED, 'pending' => 'brand',
            default => 'muted',
        };
    }
}
