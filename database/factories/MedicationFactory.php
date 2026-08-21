<?php

namespace Database\Factories;

use App\Models\Medication;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Medication>
 */
class MedicationFactory extends Factory
{
  /**
   * Define the model's default state.
   *
   * @return array<string, mixed>
   */
  public function definition(): array
  {
    $lotNumbers = [
      '951357',
      '951358',
      '951359',
    ];

    return [
      'name' => $this->faker->randomElement([
        'Ibuprofeno 400mg',
        'Amoxaxilina 500mg',
        'Paracetamol 500mg',
        'Omeprazol 20mg',
        'Metformina 850mg',
        'Losartán 50mg',
        'Simvastatina 20mg',
        'Aspirina 100mg',
        'Claritromicina 500mg',
        'Ciprofloxacino 500mg'
      ]),
      'lot_number' => $this->faker->randomElement($lotNumbers),
    ];
  }
}
