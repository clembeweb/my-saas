<?php

namespace Modules\GoogleAds\DTOs;

class CampaignDTO
{
    public function __construct(
        public readonly string $customerId,
        public readonly string $campaignId,
        public readonly string $name,
        public readonly string $status,
        public readonly string $channel,
        public readonly float $impressions,
        public readonly float $clicks,
        public readonly float $cost,
        public readonly float $conversions,
        public readonly float $ctr,
        public readonly float $averageCpc,
        public readonly string $startDate,
        public readonly string $endDate
    ) {}

    public static function fromGoogleAdsData(array $data): self
    {
        return new self(
            customerId: $data['customer_id'],
            campaignId: $data['campaign_id'],
            name: $data['campaign_name'],
            status: $data['campaign_status'],
            channel: $data['advertising_channel_type'],
            impressions: (float) $data['impressions'],
            clicks: (float) $data['clicks'],
            cost: (float) $data['cost_micros'] / 1000000,
            conversions: (float) $data['conversions'],
            ctr: (float) $data['ctr'],
            averageCpc: (float) $data['average_cpc'],
            startDate: $data['start_date'],
            endDate: $data['end_date']
        );
    }

    public function toArray(): array
    {
        return [
            'customer_id' => $this->customerId,
            'campaign_id' => $this->campaignId,
            'name' => $this->name,
            'status' => $this->status,
            'channel' => $this->channel,
            'impressions' => $this->impressions,
            'clicks' => $this->clicks,
            'cost' => $this->cost,
            'conversions' => $this->conversions,
            'ctr' => $this->ctr,
            'average_cpc' => $this->averageCpc,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
        ];
    }
}