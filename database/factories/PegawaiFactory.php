<?php

use Faker\Generator as Faker;

$factory->define(Model::class, function (Faker $faker) {
    return [
        'nama' => $faker->name,
        'nip' => $faker->numerify(str_repeat('#', 30)),
        'jabatan' => $faker->randomElement(['kabalmon', 'katim', 'ppk', 'staff', 'staff_pelayanan', 'security', 'cs', 'driver', 'magang']),
        'tim' => $faker->randomElement(['monev', 'penerbitan', 'pkip', 'tu']),
    ];
});
