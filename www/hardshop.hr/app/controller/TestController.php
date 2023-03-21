<?php

class TestController
{
    public function lozinka()
    {
        echo password_hash('password',PASSWORD_BCRYPT);
    }

    public function email()
    {
        echo Util::is_email('fbalen@gmail.com') ? 'OK' : 'NE';
    }

    public function dodajkupce()
    {
        for($i=0;$i<300;$i++){
            Kupac::create([
                'ime'=>'Kupac ' . $i,
                'prezime'=>'Prezime',
                'email'=>'',
                'adresazaracun'=>'',
                'adresazadostavu'=>'',
                'brojtelefona'=>''
            ]);
            echo $i . '<br>';
        }
    }

    public function dodajproizvode()
    {
        for($i=0;$i<200;$i++){
            Proizvod::create([
                'naziv'=>'Proizvod ' . $i,
                'proizvodjac'=>'Proizvodjac',
                'jedinicnacijena'=>'',
                'opis'=>'',
                'dostupnost'=>''
            ]);
            echo $i . '<br>';
        }
    }
}