<?php

namespace App\Support;

class TimeSeededRandom
{
    /**
     * A smooth, deterministic pseudo-random value that drifts continuously over time.
     *
     * Same (seed, timestamp) always produces the same value, so it won't jump around
     * on every page reload, but it moves organically as time passes. Built from two
     * sine waves at different frequencies, phase-shifted per seed so different seeds
     * don't move in lockstep.
     */
    public static function smooth(string $seed, float $periodSeconds, float $min, float $max, ?int $timestamp = null): float
    {
        $timestamp ??= time();
        $hash = crc32($seed);

        $phase1 = (($hash % 1000) / 1000) * 2 * M_PI;
        $phase2 = ((intdiv($hash, 1000) % 1000) / 1000) * 2 * M_PI;

        $t = $timestamp / $periodSeconds;

        $wave = 0.65 * sin(2 * M_PI * $t + $phase1)
            + 0.35 * sin(2 * M_PI * $t * 2.7 + $phase2);

        $normalized = ($wave + 1) / 2;

        return $min + $normalized * ($max - $min);
    }
}
