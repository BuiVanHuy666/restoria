<?php

namespace App\Services\Core;

use Illuminate\Support\Facades\Cache;

class LocationService
{
    protected array $data = [];

    public function __construct()
    {
        $this->data = Cache::rememberForever('vietnam_zones', function () {
            $path = database_path('data/vietnam-zone.json');
            return file_exists($path) ? json_decode(file_get_contents($path), true) : [];
        });
    }

    public function getProvinces(): array
    {
        return collect($this->data)
            ->sortBy('name')
            ->values()
            ->toArray();
    }

    public function getWards(int $provinceCode): array
    {
        $province = collect($this->data)->firstWhere('code', $provinceCode);
        $wards = $province['wards'] ?? [];

        return collect($wards)
            ->sortBy('name')
            ->values()
            ->toArray();
    }

    public function getProvinceName(int $code): string
    {
        $province = collect($this->data)->firstWhere('code', $code);
        return $province['name'] ?? 'N/A';
    }

    public function getWardName(int $provinceCode, int $wardCode): string
    {
        $province = collect($this->data)->firstWhere('code', $provinceCode);
        if (!$province) return 'N/A';

        $ward = collect($province['wards'])->firstWhere('code', $wardCode);
        return $ward['name'] ?? 'N/A';
    }
}
