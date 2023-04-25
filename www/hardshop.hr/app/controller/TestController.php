<?php

class TestController
{
    public function lozinka()
    {
        echo password_hash('password', PASSWORD_BCRYPT);
    }

    public function email()
    {
        echo Util::is_email('fbalen@gmail.com') ? 'OK' : 'NE';
    }

    public function dodajkupce()
    {
        for ($i = 0; $i < 200; $i++) {
            Kupac::create([
                'ime' => 'Kupac ' . $i,
                'prezime' => 'Prezime',
                'email' => '',
                'adresazaracun' => '',
                'adresazadostavu' => '',
                'brojtelefona' => ''
            ]);
            echo $i . '<br>';
        }
    }

    public function dodajproizvode()
    {
        for ($i = 0; $i < 200; $i++) {
            Proizvod::create([
                'naziv' => 'Proizvod ' . $i,
                'proizvodjac' => 'Proizvodjac',
                'jedinicnacijena' => '',
                'opis' => '',
                'dostupnost' => ''
            ]);
            echo $i . '<br>';
        }
    }

    public function fakerKupci()
    {
        $faker = Faker\Factory::create('hr_HR');

        for ($i = 0; $i < 200; $i++) {
            Kupac::create([
                'ime' => $faker->firstname(),
                'prezime' => $faker->lastname(),
                'email' => $faker->unique()->email,
                'adresazaracun' => $faker->address(),
                'adresazadostavu' => $faker->address(),
                'brojtelefona' => $faker->phoneNumber()
            ]);
        }
    }

    public function fakerProizvodiFalse()
    {
        $faker = Faker\Factory::create('hr_HR');

        for ($i = 0; $i < 100; $i++) {
            Proizvod::create([
                'naziv' => $faker->bothify('Proizvod ##??????#####'),
                'proizvodjac' => ucfirst($faker->unique()->word()),
                'jedinicnacijena' => $faker->unique()->numberBetween(100, 2000),
                'opis' => $faker->realText(),
                'dostupnost' => $faker->boolean(false)
            ]);
        }
    }

    public function fakerProizvodiTrue()
    {
        $faker = Faker\Factory::create('hr_HR');

        for ($i = 0; $i < 100; $i++) {
            Proizvod::create([
                'naziv' => $faker->bothify('Proizvod ##??????#####'),
                'proizvodjac' => ucfirst($faker->unique()->word()),
                'jedinicnacijena' => $faker->unique()->numberBetween(100, 2000),
                'opis' => $faker->realText(),
                'dostupnost' => $faker->boolean(true)
            ]);
        }
    }
}
