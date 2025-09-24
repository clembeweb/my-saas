<?php

namespace Modules\GoogleSearchConsole\DTOs;

class SearchConsoleDataDTO
{
    public function __construct(
        public readonly ?string $date = null,
        public readonly int $clicks = 0,
        public readonly int $impressions = 0,
        public readonly float $ctr = 0.0,
        public readonly float $position = 0.0
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            date: $data['date'] ?? null,
            clicks: $data['clicks'] ?? 0,
            impressions: $data['impressions'] ?? 0,
            ctr: $data['ctr'] ?? 0.0,
            position: $data['position'] ?? 0.0
        );
    }

    public function toArray(): array
    {
        return [
            'date' => $this->date,
            'clicks' => $this->clicks,
            'impressions' => $this->impressions,
            'ctr' => $this->ctr,
            'position' => $this->position
        ];
    }

    public function getFormattedCtr(): string
    {
        return round($this->ctr * 100, 2) . '%';
    }

    public function getFormattedPosition(): string
    {
        return round($this->position, 1);
    }
}