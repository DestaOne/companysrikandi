<?php

namespace Database\Factories;

use App\Models\Gallery;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Gallery>
 */
class GalleryFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->paragraph(),
            'image' => 'galleries/'.fake()->uuid().'.jpg',
            'sort_order' => $this->nextSortOrder(),
        ];
    }

    /** Penghitung agar galeri yang dibuat sekaligus (belum tersimpan) tetap berurutan berbeda. */
    private function nextSortOrder(): int
    {
        static $counter = 0;

        return ++$counter;
    }
}
