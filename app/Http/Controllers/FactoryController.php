<?php

namespace App\Http\Controllers;

use App\Support\DriftingValue;
use Illuminate\Support\Facades\Cache;

class FactoryController extends Controller
{
    public $machines = [
        'conveyor-1' => [
            'name' => 'Conveyor Belt 1',
            'description' => 'Moves Lego bricks from the sorting bin to the assembly station',
            'temp_range' => [25, 40],
            'vibration_range' => [0.5, 2.5],
            'belt_speed_range' => [30, 60],
        ],
        'press-2' => [
            'name' => 'Brick Press 2',
            'description' => 'Molds raw plastic into Lego bricks',
            'temp_range' => [60, 90],
            'vibration_range' => [1.5, 5],
            'belt_speed_range' => [10, 25],
        ],
        'painter-3' => [
            'name' => 'Spray Painter 3',
            'description' => 'Applies color coating to molded bricks',
            'temp_range' => [20, 35],
            'vibration_range' => [0.2, 1.5],
            'belt_speed_range' => [15, 30],
        ],
        'packer-4' => [
            'name' => 'Box Packer 4',
            'description' => 'Packs finished bricks into boxes for shipping',
            'temp_range' => [18, 28],
            'vibration_range' => [1, 3.5],
            'belt_speed_range' => [20, 45],
        ],
        'welder-5' => [
            'name' => 'Frame Welder 5',
            'description' => 'Welds metal frames for the factory conveyor rigs',
            'temp_range' => [70, 120],
            'vibration_range' => [2, 6],
            'belt_speed_range' => [5, 15],
        ],
    ];

    public function getMachines()
    {
        $machines = [];

        foreach ($this->machines as $id => $machine) {
            $machines[] = [
                'id' => $id,
                'name' => $machine['name'],
                'description' => $machine['description'],
            ];
        }

        return response()
            ->json([
                'machines' => $machines,
                'total_machines' => count($machines),
            ]);
    }

    public function getSensorData($machine)
    {
        if (!isset($this->machines[$machine])) {
            return response()
                ->json([
                    'error' => 'Invalid input',
                    'details' => 'Machine not found'
                ], 404);
        }

        if ($machine === 'conveyor-1' && $this->isConveyorOneDown()) {
            return response()
                ->json([
                    'error' => 'Service unavailable',
                    'details' => 'conveyor-1 sensor bus timed out',
                ], 503);
        }

        $profile = $this->machines[$machine];

        $status = $this->currentStatus($machine);

        $isRunning = $status === 'running';

        [$tempMin, $tempMax] = $profile['temp_range'];
        [$vibMin, $vibMax] = $profile['vibration_range'];
        [$speedMin, $speedMax] = $profile['belt_speed_range'];

        $tempTarget = $isRunning ? ($tempMin + $tempMax) / 2 : $tempMin;
        $vibTarget = $isRunning ? ($vibMin + $vibMax) / 2 : 0;
        $speedTarget = $isRunning ? ($speedMin + $speedMax) / 2 : 0;

        $temperature = round(DriftingValue::next(
            key: 'factory:temp:'.$machine,
            target: $tempTarget,
            min: 0,
            max: $tempMax + 15,
            maxStepPerMinute: ($tempMax - $tempMin) * 0.2,
            jitter: 0.5
        ), 1);

        $vibration = round(DriftingValue::next(
            key: 'factory:vib:'.$machine,
            target: $vibTarget,
            min: 0,
            max: $vibMax + 3,
            maxStepPerMinute: ($vibMax - $vibMin) * 0.3,
            jitter: 0.1
        ), 2);

        $beltSpeed = round(DriftingValue::next(
            key: 'factory:speed:'.$machine,
            target: $speedTarget,
            min: 0,
            max: $speedMax,
            maxStepPerMinute: ($speedMax - $speedMin) * 0.4,
            jitter: 1
        ), 1);

        $unitsProduced = $this->accumulateUnitsProduced($machine, $beltSpeed, $isRunning);
        $errorCount = (int) Cache::get('factory:errors:'.$machine, 0);

        return response()
            ->json([
                'machine' => $machine,
                'name' => $profile['name'],
                'status' => $status,
                'temperature_c' => $temperature,
                'vibration_mm_s' => $vibration,
                'belt_speed_units_min' => $beltSpeed,
                'units_produced' => $unitsProduced,
                'error_count' => $errorCount,
                'updated_at' => now()->toIso8601String(),
            ]);
    }

    private function isConveyorOneDown(): bool
    {
        // Fails for the first 3 seconds of every 15-second window, so
        // consumers see at least two failures every 30 seconds regardless
        // of poll timing.
        return (time() % 15) < 3;
    }

    private function currentStatus(string $machine): string
    {
        $cacheKey = 'factory:status:'.$machine;
        $state = Cache::get($cacheKey);
        $nowTimestamp = time();

        if ($state && $nowTimestamp < $state['until']) {
            return $state['status'];
        }

        $previousStatus = $state['status'] ?? 'running';

        $roll = mt_rand(1, 100);
        $status = match (true) {
            $roll <= 3 => 'error',
            $roll <= 10 => 'idle',
            default => 'running',
        };

        if ($status === 'error' && $previousStatus !== 'error') {
            $errorKey = 'factory:errors:'.$machine;
            Cache::put($errorKey, (int) Cache::get($errorKey, 0) + 1, now()->addHours(6));
        }

        $durationSeconds = match ($status) {
            'error' => mt_rand(20, 60),
            'idle' => mt_rand(15, 45),
            default => mt_rand(60, 180),
        };

        Cache::put($cacheKey, [
            'status' => $status,
            'until' => $nowTimestamp + $durationSeconds,
        ], now()->addHours(6));

        return $status;
    }

    private function accumulateUnitsProduced(string $machine, float $beltSpeed, bool $isRunning): int
    {
        $cacheKey = 'factory:units:'.$machine;
        $state = Cache::get($cacheKey, ['count' => 0, 't' => microtime(true)]);
        $now = microtime(true);

        if ($isRunning) {
            $elapsedMinutes = max(0, ($now - $state['t']) / 60);
            $state['count'] += $elapsedMinutes * $beltSpeed;
        }

        $state['t'] = $now;

        Cache::put($cacheKey, $state, now()->addHours(6));

        return (int) round($state['count']);
    }
}
