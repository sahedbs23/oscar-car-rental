<?php

namespace Validation;

use App\Exceptions\InvalidRuleException;
use App\Validation\Validator;
use Faker\Factory;
use Faker\Generator;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{
    /**
     * @var Generator|null
     */
    public ?Generator $faker;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->faker = Factory::create();
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        $this->faker = null;
    }

    /**
     * @return void
     * @throws InvalidRuleException
     */
    public function test__construct()
    {
        $validator = Validator::vlaidate([], []);
        $this->assertInstanceOf(Validator::class, $validator);
    }

    /**
     * @return void
     * @throws InvalidRuleException
     */
    public function testFailsAndMessage(): void
    {
        $rules = [
            'location' => ['required', 'string'],
            'license_plate' => ['required', 'string'],
            'car_year' => ['required', 'numeric'],
            'number_of_doors' => ['required', 'numeric'],
            'number_of_seats' => ['required', 'numeric']
        ];

        $input = [
            'location' => $this->faker->city(),
            'license_plate' => $this->faker->unique()->text(10),
            'car_year' => $this->faker->numberBetween(1950, 2022),
            'number_of_doors' => $this->faker->randomNumber(2),
            'number_of_seats' => $this->faker->randomNumber(1)
        ];

        $validator = Validator::vlaidate($input, $rules);
        $this->assertFalse($validator->fails());
        $this->assertCount(0, $validator->message());
    }


    /**
     * @return void
     * @throws InvalidRuleException
     */
    public function testFailsAndMessageWithInvalidData(): void
    {
        $rules = [
            'car_brand' => ['required', 'string'],
            'car_year' => ['required', 'numeric'],
            'car_km' => ['required', 'numeric'],
            'car_type_group' => ['required', 'string'],
            'car_type' => ['required', 'string'],
        ];

        $input = [
            'car_brand' => $this->faker->randomDigit(),
            'car_year' => $this->faker->optional()->text(),
            'car_km' => $this->faker->numberBetween(0, 1000000),
            'car_type_group' => $this->faker->optional()->text(),
            'car_type' => $this->faker->optional()->text()
        ];
        $validator = Validator::vlaidate($input, $rules);
        $this->assertTrue($validator->fails());
        $this->assertIsArray($validator->message());
        $this->assertArrayHasKey('car_brand', $validator->message());
    }

    /**
     * @return void
     */
    public function testInvalidRuleException(): void
    {
        $rules = [
            'car_type' => ['nullable'],
        ];

        $input = [
            'car_type' => $this->faker->optional()->text()
        ];
        $this->expectException(InvalidRuleException::class);
        Validator::vlaidate($input, $rules);
    }

}
