<?php

namespace App\Services;

use App\Models\Subscription;
use App\Models\Tariff;
use Illuminate\Support\Carbon;

class SubscriptionService
{
    const STATUS_ACTIVE = 'active';
    const STATUS_PENDING = 'pending';
    const PAYMENT_FREQUENCY_MONTHLY = 'monthly';
    const PAYMENT_FREQUENCY_YEARLY = 'yearly';

    public function getCurrentSubscription()
    {
        return Subscription::where('status', self::STATUS_ACTIVE)->first();
    }

    public function getLastSubscription()
    {
        return Subscription::where('status', self::STATUS_PENDING)
            ->orderBy('created_at', 'DESC')
            ->first();
    }

    public function create(array $data): Subscription
    {
        $tariff = Tariff::find($data['tariff_id']);
        $totalCost = $tariff->price_per_user * $data['user_count'];

        return Subscription::create([
            'status' => self::STATUS_PENDING,
            'tariff_id' => $data['tariff_id'],
            'user_count' => $data['user_count'],
            'total_cost' => $totalCost,
            'payment_frequency' => $data['payment_frequency'],
            'valid_until' => $this->getValidUntilDate($data['payment_frequency']),
        ]);
    }

    public function update(Subscription $subscription, array $data): Subscription
    {
        $tariff = Tariff::find($data['tariff_id']);
        $totalCost = $this->getTotalCost($data['payment_frequency'], $tariff->price_per_user, $data['user_count']);
        $subscription->update([
            'status' => self::STATUS_PENDING,
            'tariff_id' => $data['tariff_id'],
            'user_count' => $data['user_count'],
            'total_cost' => $totalCost,
            'payment_frequency' => $data['payment_frequency'],
            'valid_until' => $this->getValidUntilDate($data['payment_frequency']),
        ]);

        return $subscription;
    }

    public function delete(Subscription $subscription): ?bool
    {
        return $subscription->delete();
    }

    public function getById(int $id): ?Subscription
    {
        return Subscription::find($id);
    }

    private function getValidUntilDate(string $paymentFrequency): Carbon
    {
        $validUntil = Carbon::now();
        switch ($paymentFrequency) {
            case self::PAYMENT_FREQUENCY_YEARLY:
                $validUntil->addYear();
                break;

            case self::PAYMENT_FREQUENCY_MONTHLY:
            default:
                $validUntil->addMonth();
                break;
        }

        return $validUntil;
    }

    private function getTotalCost(string $paymentFrequency, $pricePerUser, $usersCount): float|int
    {
        $multiplier = ($paymentFrequency == self::PAYMENT_FREQUENCY_MONTHLY) ? 1 : 12;

        $totalCost = $multiplier * $pricePerUser * $usersCount;

        return $totalCost;
    }
}
