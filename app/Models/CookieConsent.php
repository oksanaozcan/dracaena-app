<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CookieConsent extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'consent_given'];

    protected $table = 'cookie_consents';

     /**
     * Get the consent status for a customer.
     *
     * @param int $customerId
     * @return bool|null
     */
    public static function getConsentStatus(int $customerId): ?bool
    {
        $consent = self::where('customer_id', $customerId)->first();
        return $consent ? $consent->consent_given : null;
    }

    /**
     * Set the consent status for a customer.
     *
     * @param int $customerId
     * @param bool $consentGiven
     * @return self
     */
    public static function setConsentStatus(int $customerId, bool $consentGiven): self
    {
        $consent = self::updateOrCreate(
            ['customer_id' => $customerId],
            ['consent_given' => $consentGiven]
        );

        return $consent;
    }

    /**
     * Check if a customer has already given consent.
     *
     * @param int $customerId
     * @return bool
     */
    public static function hasGivenConsent(int $customerId): bool
    {
        return self::where('customer_id', $customerId)->exists();
    }
}
