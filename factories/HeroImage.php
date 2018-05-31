<?php

namespace Factories;

use Contracts\Factories\FactoryContract;
use Faker\Factory;

class HeroImage implements FactoryContract
{
    /**
     * Construct the factory.
     *
     * @param Factory $faker
     */
    public function __construct(Factory $faker)
    {
        $this->faker = $faker->create();
    }

    /**
     * {@inheritdoc}
     */
    public function create($limit = 1)
    {
        for ($i = 1; $i <= $limit; $i++) {
            $data[$i] = [
                'link' => $this->faker->url,
                'excerpt' => $this->faker->sentence,
                'relative_url' => '/styleguide/image/1600x580?text=1600x580%20('.$i.')',
                'title' => $this->faker->sentence,
            ];
        }

        return $data;
    }
}
