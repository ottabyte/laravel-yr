<?php declare(strict_types=1);

/**
 * Copyright (C) BaseCode Oy - All Rights Reserved
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 */

namespace BaseCodeOy\Yr\Location\Data\Forecast;

use BaseCodeOy\Yr\Location\Data\CurrentHour\SymbolCode;
use BaseCodeOy\Yr\Location\Data\CurrentHour\Wind;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Spatie\LaravelData\Data;

final class LongInterval extends Data
{
    /**
     * @param Collection<int, SymbolCode> $symbolCodes
     */
    private function __construct(
        public readonly Symbol $symbol,
        public readonly Collection $symbolCodes,
        public readonly float $precipitation,
        public readonly Temperature $temperature,
        public readonly Wind $wind,
        public readonly float $feelsLike,
        public readonly float $pressure,
        public readonly CloudCover $cloudCover,
        public readonly float $humidity,
        public readonly float $dewPoint,
        public readonly CarbonImmutable $start,
        public readonly CarbonImmutable $end,
        public readonly CarbonImmutable $nominalStart,
        public readonly CarbonImmutable $nominalEnd,
    ) {
        //
    }

    public static function fromResponse(array $data): self
    {
        return new self(
            symbol: Symbol::fromResponse($data['symbol']),
            symbolCodes: collect($data['symbolCode'])
                ->mapWithKeys(fn (string $value, string $key) => [$key => SymbolCode::fromResponse([
                    'key' => $key,
                    'value' => $value,
                ])])
                ->values(),
            precipitation: $data['precipitation']['value'],
            temperature: Temperature::fromResponse($data['temperature']),
            wind: Wind::fromResponse($data['wind']),
            feelsLike: $data['feelsLike']['value'],
            pressure: $data['pressure']['value'],
            cloudCover: CloudCover::fromResponse($data['cloudCover']),
            humidity: $data['humidity']['value'],
            dewPoint: $data['dewPoint']['value'],
            start: CarbonImmutable::parse($data['start']),
            end: CarbonImmutable::parse($data['end']),
            nominalStart: CarbonImmutable::parse($data['nominalStart']),
            nominalEnd: CarbonImmutable::parse($data['nominalEnd']),
        );
    }
}
