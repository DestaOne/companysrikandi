<?php

namespace Database\Factories;

use App\Enums\ServiceStatus;
use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = 'Perdagangan Besar '.fake()->unique()->words(3, true);

        return [
            'slug' => Str::slug($title),
            'title' => $title,
            'description' => fake()->paragraph(),
            'image' => 'images/home/layanan-mesin.jpg',
            'products' => [fake()->words(3, true), fake()->words(3, true)],
            'status' => ServiceStatus::Published,
            'sort_order' => $this->nextSortOrder(),
        ];
    }

    /** Penghitung agar layanan yang dibuat sekaligus (belum tersimpan) tetap berurutan berbeda. */
    private function nextSortOrder(): int
    {
        static $counter = 0;

        return ++$counter;
    }

    public function draft(): static
    {
        return $this->state(['status' => ServiceStatus::Draft]);
    }
}
