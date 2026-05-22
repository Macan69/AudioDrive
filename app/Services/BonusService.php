<?php

namespace App\Services;

use App\Models\BonusSetting;
use App\Models\BonusTransaction;
use App\Models\Order;
use App\Models\User;

class BonusService
{
    public function pointsPerRuble(): float
    {
        return (float) BonusSetting::get('points_per_ruble', '1');
    }

    public function rublePerPoint(): float
    {
        return (float) BonusSetting::get('ruble_per_point', '1');
    }

    public function maxBonusPercent(): int
    {
        return (int) BonusSetting::get('max_bonus_percent', '30');
    }

    public function registrationBonus(): int
    {
        return (int) BonusSetting::get('registration_bonus', '500');
    }

    public function calculateEarned(float $orderTotal, int $bonusReward = 0): int
    {
        $earned = (int) floor($orderTotal * $this->pointsPerRuble());

        return $earned + $bonusReward;
    }

    public function calculateMaxUsable(float $subtotal, int $userPoints): int
    {
        $maxByPercent = (int) floor($subtotal * $this->maxBonusPercent() / 100 / $this->rublePerPoint());

        return min($userPoints, $maxByPercent);
    }

    public function bonusToMoney(int $points): float
    {
        return $points * $this->rublePerPoint();
    }

    public function grantRegistrationBonus(User $user): void
    {
        $bonus = $this->registrationBonus();
        if ($bonus <= 0 || $user->bonusTransactions()->where('type', 'registration')->exists()) {
            return;
        }

        $user->increment('bonus_points', $bonus);
        BonusTransaction::create([
            'user_id' => $user->id,
            'points' => $bonus,
            'type' => 'registration',
            'description' => 'Бонус за регистрацию',
        ]);
    }

    public function applyToOrder(Order $order, User $user): void
    {
        if ($order->bonus_used > 0) {
            $user->decrement('bonus_points', $order->bonus_used);
            BonusTransaction::create([
                'user_id' => $user->id,
                'order_id' => $order->id,
                'points' => -$order->bonus_used,
                'type' => 'spent',
                'description' => "Списание за заказ {$order->number}",
            ]);
        }

        if ($order->bonus_earned > 0) {
            $user->increment('bonus_points', $order->bonus_earned);
            BonusTransaction::create([
                'user_id' => $user->id,
                'order_id' => $order->id,
                'points' => $order->bonus_earned,
                'type' => 'earned',
                'description' => "Начисление за заказ {$order->number}",
            ]);
        }
    }
}
