<?php

namespace App\Support;

use Illuminate\Support\Facades\Cache;

class DriftingValue
{
    /**
     * A stateful value that drifts toward a target at a limited rate per minute,
     * persisted between requests via cache. Rapid polling only allows small nudges,
     * while the value still visibly trends toward its (possibly time-varying) target
     * over tens of seconds/minutes, instead of jumping instantly.
     */
    public static function next(string $key, float $target, float $min, float $max, float $maxStepPerMinute, float $jitter = 0.0): float
    {
        $cacheKey = 'drift:'.$key;
        $state = Cache::get($cacheKey);
        $now = microtime(true);

        if (!$state) {
            $current = max($min, min($max, $target));
        } else {
            $elapsedMinutes = max(0, ($now - $state['t']) / 60);
            $maxStep = $maxStepPerMinute * $elapsedMinutes;

            $diff = $target - $state['v'];
            $step = max(-$maxStep, min($maxStep, $diff));

            $noise = $jitter > 0
                ? (mt_rand(-1000, 1000) / 1000) * $jitter * min(1, $elapsedMinutes)
                : 0;

            $current = max($min, min($max, $state['v'] + $step + $noise));
        }

        Cache::put($cacheKey, ['v' => $current, 't' => $now], now()->addHours(6));

        return $current;
    }
}
