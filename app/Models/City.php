<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $fillable = ['name', 'country_code', 'usage_count'];

    /**
     * Search cities by name for a given country, ordered by popularity.
     */
    public static function search(string $query, ?string $countryCode = null, int $limit = 10): \Illuminate\Database\Eloquent\Collection
    {
        return static::query()
            ->when($countryCode, fn ($q) => $q->where('country_code', $countryCode))
            ->where('name', 'like', "{$query}%")
            ->orderByDesc('usage_count')
            ->limit($limit)
            ->get();
    }

    /**
     * Find or create a city, incrementing usage count if it already exists.
     */
    public static function findOrCreateAndIncrement(string $name, string $countryCode): self
    {
        $name = trim($name);
        if (empty($name)) {
            throw new \InvalidArgumentException('City name cannot be empty.');
        }

        $city = static::firstOrCreate(
            ['name' => $name, 'country_code' => strtoupper($countryCode)],
            ['usage_count' => 0]
        );

        $city->increment('usage_count');

        return $city;
    }
}
