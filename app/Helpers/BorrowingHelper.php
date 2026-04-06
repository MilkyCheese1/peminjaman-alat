<?php

namespace App\Helpers;

use DateTime;

class BorrowingHelper
{
    /**
     * Generate 8-digit verification code
     */
    public static function generateVerificationCode(): string
    {
        return str_pad(random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
    }

    /**
     * Check if date is holiday (weekend or public holiday)
     * In this case, we'll consider Saturday & Sunday as holidays
     */
    public static function isHoliday(\DateTime $date): bool
    {
        // 0 = Sunday, 6 = Saturday
        $dayOfWeek = (int)$date->format('w');
        
        // Define public holidays (adjust as needed)
        $publicHolidays = [
            '01-01', // New Year
            '12-25', // Christmas
            '01-01', // Add more Indonesian holidays as needed
        ];
        
        // Check if weekend
        if ($dayOfWeek === 0 || $dayOfWeek === 6) {
            return true;
        }
        
        // Check if public holiday
        $dateStr = $date->format('m-d');
        if (in_array($dateStr, $publicHolidays)) {
            return true;
        }
        
        return false;
    }

    /**
     * Get next available date (skip holidays)
     */
    public static function getNextAvailableDate(\DateTime $date): \DateTime
    {
        $nextDate = clone $date;
        
        while (self::isHoliday($nextDate)) {
            $nextDate->modify('+1 day');
        }
        
        return $nextDate;
    }

    /**
     * Calculate duration in hours and days
     */
    public static function calculateDuration(\DateTime $startDate, \DateTime $endDate): array
    {
        $interval = $startDate->diff($endDate);
        $hours = ($interval->days * 24) + $interval->h + ($interval->i > 0 ? 1 : 0);
        
        return [
            'hours' => $hours,
            'days' => $interval->days,
            'days_decimal' => $hours / 24,
        ];
    }

    /**
     * Validate borrowing duration
     * Min 1 hour, Max 14 days
     */
    public static function validateDuration(\DateTime $startDate, \DateTime $endDate): array
    {
        $duration = self::calculateDuration($startDate, $endDate);
        $errors = [];
        
        if ($duration['hours'] < 1) {
            $errors[] = 'Durasi peminjaman minimal 1 jam';
        }
        
        if ($duration['days'] > 14) {
            $errors[] = 'Durasi peminjaman maksimal 14 hari';
        }
        
        return $errors;
    }

    /**
     * Validate borrowing dates (no holidays)
     */
    public static function validateDates(\DateTime $startDate, \DateTime $endDate): array
    {
        $errors = [];
        
        if (self::isHoliday($startDate)) {
            $errors[] = 'Tanggal pengambilan tidak boleh pada hari libur';
        }
        
        if (self::isHoliday($endDate)) {
            $errors[] = 'Tanggal pengembalian tidak boleh pada hari libur';
        }
        
        return $errors;
    }
}
