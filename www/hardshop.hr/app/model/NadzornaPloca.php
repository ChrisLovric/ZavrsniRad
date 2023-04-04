<?php

class NadzornaPloca
{
    public static function pretraga($uvjet)
    {
        $veza=DB::getInstance();
        $izraz=$veza->prepare('
        
        select sifra, \'Kupac\' as vrsta,
        concat(ime, \' \', prezime) as tekst from kupac
        where concat(ime, \' \', prezime) like :uvjet
        union 
        select sifra, \'Proizvod\' as vrsta,
        naziv as tekst from proizvod 
        where naziv like :uvjet
        union 
        select brojnarudzbe, \'Broj narudžbe\' as vrsta,
        brojnarudzbe as tekst from narudzba 
        where brojnarudzbe like :uvjet
        
        ');
        $izraz->execute(['uvjet'=>'%' . $uvjet . '%']);
        return $izraz->fetchAll();
    }
}