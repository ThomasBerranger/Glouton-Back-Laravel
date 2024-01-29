<?php

namespace App\Models\Scopes;

trait User
{
    const EXPIRATION_DATES_MAXIMUM_NUMBER = 10;

    public function scopeExpireThisWeek($query): void
    {
        $query->havingRaw("LEAST(" . $this->getSqlForExpirationDates() . ") < DATE_ADD(CURDATE(), INTERVAL 1 WEEK)");
    }

    public function scopeExpireThisMonth($query): void
    {
        $query->havingRaw("LEAST(" . $this->getSqlForExpirationDates() . ") < DATE_ADD(CURDATE(), INTERVAL 1 MONTH)");
    }

    public function scopeOrderedByClosestExpirationDate($query): void
    {
        $query->orderByRaw("LEAST(" . $this->getSqlForExpirationDates() . ")");
    }

    private function getSqlForExpirationDates(): string
    {
        return substr(
            str_repeat(
                "COALESCE(STR_TO_DATE(JSON_UNQUOTE(JSON_EXTRACT(expiration_dates, '$[0]')), '%d/%m/%Y'), '9999-12-31'),",
                self::EXPIRATION_DATES_MAXIMUM_NUMBER
            ),
            0,
            -1
        );
    }
}
