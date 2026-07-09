<?php

namespace App\Http\Controllers;

use App\Support\DriftingValue;
use App\Support\TimeSeededRandom;

class CrowdController extends Controller
{
    public function getCrowd($location)
    {
        if (!preg_match('/^[a-zA-Z0-9\s\-]{2,50}$/', $location)) {
            return response()
                ->json([
                    'error' => 'Invalid input',
                    'details' => 'Location must be 2-50 characters, letters, numbers, spaces and dashes only'
                ], 422);
        }

        $capacity = 20 + (crc32('capacity:'.$location) % 281); // 20-300

        $targetFraction = TimeSeededRandom::smooth('crowd:'.$location, 24 * 3600, 0.05, 0.95);
        $target = $targetFraction * $capacity;

        $currentCount = (int) round(DriftingValue::next(
            key: 'crowd:'.$location,
            target: $target,
            min: 0,
            max: $capacity,
            maxStepPerMinute: $capacity * 0.08,
            jitter: $capacity * 0.02
        ));

        $occupancyPercentage = round(($currentCount / $capacity) * 100, 1);

        $status = match (true) {
            $occupancyPercentage >= 90 => 'full',
            $occupancyPercentage >= 60 => 'busy',
            $occupancyPercentage >= 25 => 'moderate',
            default => 'quiet',
        };

        return response()
            ->json([
                'location' => $location,
                'capacity' => $capacity,
                'current_count' => $currentCount,
                'occupancy_percentage' => $occupancyPercentage,
                'status' => $status,
                'updated_at' => now()->toIso8601String(),
            ]);
    }
}
