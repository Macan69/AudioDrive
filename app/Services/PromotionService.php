<?php

namespace App\Services;

use App\Models\Promotion;

class PromotionService
{
    public function findByCode(?string $code): ?Promotion
    {
        if (! $code) {
            return null;
        }

        return Promotion::active()
            ->where('code', strtoupper(trim($code)))
            ->first();
    }

    public function getAutoApplied(float $subtotal): ?Promotion
    {
        return Promotion::active()
            ->where('auto_apply', true)
            ->orderByDesc('value')
            ->get()
            ->first(fn (Promotion $p) => $p->isValidForAmount($subtotal));
    }

    public function calculateDiscount(Promotion $promotion, float $subtotal): float
    {
        if (! $promotion->isValidForAmount($subtotal)) {
            return 0;
        }

        return match ($promotion->type) {
            'percent' => round($subtotal * ($promotion->value / 100), 2),
            'fixed' => min((float) $promotion->value, $subtotal),
            default => 0,
        };
    }

    public function resolvePromotion(?string $code, float $subtotal): ?Promotion
    {
        if ($code) {
            $promo = $this->findByCode($code);

            return $promo && $promo->isValidForAmount($subtotal) ? $promo : null;
        }

        return $this->getAutoApplied($subtotal);
    }
}
