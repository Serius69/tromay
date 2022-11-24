<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Photo;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Latest>
 */
class PhotoFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Photo::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'path' => 'about-img.jpg',
            'status' => 1
        ];
    }
}
