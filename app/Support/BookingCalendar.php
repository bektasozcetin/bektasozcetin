<?php

namespace App\Support;

use App\Models\Meeting;
use Carbon\CarbonImmutable;

/**
 * Site içi görüşme takvimi: panelde seçilen gün ve saatlerden uygun aralıkları üretir.
 * Saatler Türkiye saatine göredir.
 */
class BookingCalendar
{
    public const TZ = 'Europe/Istanbul';

    /** @return array<int, array{date: string, times: array<int, array{0: string, 1: bool}>}> */
    public static function days(): array
    {
        $days = array_map('strval', setting('booking_days', []));
        $times = setting('booking_times', []);
        $now = CarbonImmutable::now(self::TZ);
        $earliest = $now->addHours((int) setting('booking_notice_hours', 12));
        $horizon = (int) setting('booking_horizon', 14);

        $taken = Meeting::active()
            ->whereBetween('starts_at', [$now->startOfDay(), $now->addDays($horizon + 1)])
            ->pluck('starts_at')
            ->map(fn ($d) => $d->copy()->setTimezone(self::TZ)->format('Y-m-d H:i'))
            ->flip();

        $result = [];
        for ($i = 0; $i <= $horizon; $i++) {
            $date = $now->startOfDay()->addDays($i);
            if (! in_array((string) $date->dayOfWeek, $days, true)) {
                continue;
            }
            $slots = [];
            foreach ($times as $time) {
                $at = CarbonImmutable::createFromFormat('Y-m-d H:i', $date->format('Y-m-d').' '.$time, self::TZ);
                if ($at->lt($earliest)) {
                    continue;
                }
                $slots[] = [$time, ! isset($taken[$at->format('Y-m-d H:i')])];
            }
            if ($slots) {
                $result[] = ['date' => $date->format('Y-m-d'), 'times' => $slots];
            }
        }

        return $result;
    }

    public static function isAvailable(string $date, string $time): bool
    {
        foreach (self::days() as $day) {
            if ($day['date'] === $date) {
                foreach ($day['times'] as [$t, $free]) {
                    if ($t === $time) {
                        return $free;
                    }
                }
            }
        }

        return false;
    }
}
